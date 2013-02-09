var xhr = new Array();
var xi = new Array();
xi[0] = 1;
var cp_update = 0;
function xhrRequest(type) {

	var xhrsend = xi.length; 

	for (var i=0; i<xi.length; i++) {
		if (xi[i] == 1) {
			xi[i] = 0;
			xhrsend = i;
			break;
		}
	}
	xi[xhrsend] = 0;

	if (window.ActiveXObject) {
		try {
			xhr[xhrsend] = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try {
				xhr[xhrsend] = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e) {}
		}
	} else if (window.XMLHttpRequest) {

		xhr[xhrsend] = new XMLHttpRequest();
		if (xhr[xhrsend].overrideMimeType) {
			xhr[xhrsend].overrideMimeType('text/' + type);
		}
	}
	return (xhrsend);
}

function AjaxRequest(x,method,url,text) {
	if (!(x == 'cp_update' || x == 'cp_update_mes' || x == 'cp_update_button' || x == 'cp_update_status' || x == 'clear_cp_update'))
		document.getElementById('cploading').innerHTML= "<TABLE width=\"242\" height=\"58\" style=\"border:1px solid #cccccc;background-color:#FCF8C7;\"><TR align=\"center\"><TD class=\"loading\"><img src=\"theme/"+theme+"/images/loading.gif\" align=\"absmiddle\"> Loading. Please Wait...</TD></TR></TABLE>";
	var xhri = xhrRequest();
	xhr[xhri].open(method, url, true);
	xhr[xhri].onreadystatechange = function() {
		if (xhr[xhri].readyState == 4) {
			var result = xhr[xhri].responseText;
			document.getElementById('cploading').innerHTML= '';
			if (xhr[xhri].status == 200) {
				if (x == 'userid' || x == 'pass' || x == 'pass2' || x == 'slspass' || x == 'slspass2' || x == 'email') {
					check_reg_(x,result);
				 } else if (x == 'check_cp_update') {
					refresh_cp_update(result);
				} else if (x == 'clear_cp_update') {
					cp_update = 0;
				} else if (x == 'cp_update_status') {
					if (result == -1 || result == 3 || result == 4 || result == 5 || result == 6) {
						var url = 'ajax.php?module=clear_cp_update';
						AjaxRequest('clear_cp_update','get',url);
					}
				} else if (x != 'do_cp_update') {
					document.getElementById(x).innerHTML= result;
				}
				xi[xhri] = 1;
				xhr[xhri] = null;
			}
		}
	};
	if(method == 'post')
		xhr[xhri].setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded' );

	if(text)
		xhr[xhri].send(text);
	else
		xhr[xhri].send(null);
}

function check_post(val) {
while (val.indexOf('&') != -1)
	val = val.replace('&','symbol_and');
while (val.indexOf('+') != -1)
	val = val.replace('+','symbol_plus');
return val;
}
function preview_post(x) {
var value = check_post(document.getElementById(x).t_mes.value);
var text = "val="+value+"";
var url = 'ajax.php?module=post_preview';
AjaxRequest('post_preview','post',url,text);
}

function show_poll_form() {
var url = 'ajax.php?module=show_poll_form';
AjaxRequest('poll_form','get',url);
document.getElementById("t_post_form").newpoll.value = 1;
}

function close_poll_form() {
var result = "<a href=\"#\" onClick=\"show_poll_form(); return false;\">Click here to manage this topic's poll</a>";
document.getElementById("t_post_form").newpoll.value = 0;
document.getElementById("poll_form").innerHTML = result;
}

function page_select(name,get) {
var url = 'ajax.php?module=page_select&name='+name+'&'+get+'';
AjaxRequest(''+name+'','get',url);
}

function do_bb_code(x,name) {
var url = 'ajax.php?module=do_bb_code&form_id='+x+'&name='+name+'';
AjaxRequest(''+name+'','get',url);
}

function check_reg(x) {
var obj_ta = document.regis_form;
var val;
	switch(x) {
		case "userid":
			val = 'val='+obj_ta.userid.value+'';
			break;
		case "pass":
			val = 'val='+obj_ta.userpass.value+'';
			break;
		case "pass2":
			val = 'val='+obj_ta.userpass2.value+'&val2='+obj_ta.userpass.value+'';
			break;
		case "slspass":
			val = 'val='+obj_ta.userslspass.value+'';
			break;
		case "slspass2":
			val = 'val='+obj_ta.userslspass.value+'&val2='+obj_ta.userslspass2.value+'';
			break;
		case "email":
			val = 'val='+obj_ta.email.value+'';
			break;
	}
var url = 'ajax.php?module=check_reg&check='+x+'';
AjaxRequest(x,'post',url,val);
}

function check_reg_(x,result) {
	if(result) {
		document.getElementById("attn_"+x+"_").innerHTML = result;
		document.getElementById("attn_"+x+"").style.display = "";
		document.getElementById("_attn_"+x+"").innerHTML = "<img src=\"theme/"+theme+"/images/aff_cross.gif\" align=\"absmiddle\">";
	} else {
		document.getElementById("attn_"+x+"_").innerHTML = "";
		document.getElementById("attn_"+x+"").style.display = "none";
		document.getElementById("_attn_"+x+"").innerHTML = "<img src=\"theme/"+theme+"/images/aff_tick.gif\" align=\"absmiddle\">";
	}
}

function quick_edit(x,get) {
var url = 'ajax.php?module=quick_edit_post&'+get+'';
eval(x + "_old = document.getElementById(x).innerHTML");
AjaxRequest(''+x+'','get',url);
}

function save_quick_edit(x,name) {
var value = check_post(document.getElementById(x).t_mes.value);
var text = "val="+value+"&p="+document.getElementById(x).p.value+"";
var url = 'ajax.php?module=save_quick_edit';
AjaxRequest(name,'post',url,text);
}

function restore_post(x) {
document.getElementById(x).innerHTML = eval(x + "_old");
}

function resize_img() {
	for(var i=1;i<=total_img_resize;i++) {
		var img_obj = eval('document.images.user_posted_image_'+i+'');
		var img_url = img_obj.src;
		var width = parseInt(img_obj.width)?parseInt(img_obj.width):parseInt(img_obj.style.width);
		var height = parseInt(img_obj.height)?parseInt(img_obj.height):parseInt(img_obj.style.height);
		if (width > max_width && max_width) {
			var new_width = parseInt(80/100*max_width);
			var new_height = parseInt(new_width*height/width);
			if (new_height > max_height && max_height) {
				var new_height_ = new_height;
				new_height = parseInt(80/100*max_height);
				new_width = parseInt(new_height*new_width/new_height_);
			}
			img_obj.width = new_width;
			img_obj.height = new_height;
			var span_obj = eval("document.getElementById('image_"+i+"')");
			var resize_percent = parseInt(new_width/width*100);
			original_src = span_obj.innerHTML;
			span_obj.innerHTML = "<table border='0' cellspacing='0' cellpadding='0'><tr style='background-color:#000000;cursor: pointer;cursor: hand;' onclick=\"window.open('"+img_url+"')\"><td style='padding:3px;color:#ffffff;'><img src='theme/"+theme+"/images/img-resized.png'> Reduced: "+resize_percent+"% of original size [ "+width+" x "+height+" ] - Click to view full image</td></tr><tr><td>"+original_src+"</td></tr></table>";
		} else if (height > max_height && max_height) {
			var new_height = parseInt(80/100*max_height);
			var new_width = parseInt(new_height*width/height);
			if (new_width > max_width && max_width) {
				var new_width_ = new_width;
				new_width = parseInt(80/100*max_width);
				new_height = parseInt(new_width*new_height/new_width_);
			}
			img_obj.width = new_width;
			img_obj.height = new_height;
			var span_obj = eval("document.getElementById('image_"+i+"')");
			var resize_percent = parseInt(new_width/width*100);
			original_src = span_obj.innerHTML;
			span_obj.innerHTML = "<table border='0' cellspacing='0' cellpadding='0'><tr style='background-color:#000000;cursor: pointer;cursor: hand;' onclick=\"window.open('"+img_url+"')\"><td style='padding:3px;color:#ffffff;'><img src='theme/"+theme+"/images/img-resized.png'> Reduced: "+resize_percent+"% of original size [ "+width+" x "+height+" ] - Click to view full image</td></tr><tr><td>"+original_src+"</td></tr></table>";
		}
	}
}

function check_cp_update() {
var url = 'ajax.php?module=check_cp_update';
AjaxRequest('check_cp_update','get',url);
}

function refresh_cp_update(result) {
result = parseInt(result);
var url = 'ajax.php?module=refresh_cp_update&code='+result+'';
AjaxRequest('cp_update','get',url+'&position=0');
AjaxRequest('cp_update_mes','get',url+'&position=1');
AjaxRequest('cp_update_button','get',url+'&position=2');
}

function do_cp_update() {
var url = 'ajax.php?module=do_cp_update';
AjaxRequest('do_cp_update','get',url);
cp_update = 1;
setTimeout('process_cp_update()', 1000);
}

function process_cp_update() {
	if (cp_update) {
		var url = 'ajax.php?module=status_cp_update';
		AjaxRequest('cp_update_status','get',url);

		var url = 'ajax.php?module=refresh_cp_update&code=2';
		AjaxRequest('cp_update_mes','get',url+'&position=1');
		AjaxRequest('cp_update_button','get',url+'&position=2');
	setTimeout('process_cp_update()', 500);
	}
}