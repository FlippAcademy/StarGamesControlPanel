function do_bbcode(x) {
do_bb_code(x,'ffont');
do_bb_code(x,'fsize');
do_bb_code(x,'fcolor');
document.onclick=function(){ShowHide('hide_bbcode');}
}
function switchbg(x,style){
var color = '#ebebeb';
if(style)
	document.getElementById(x).style.background = '';
else
	document.getElementById(x).style.background = color;
}
function switchbg_(x,style){
var color = '#d3d3d3';
if(style)
	document.getElementById(x).style.background = '';
else
	document.getElementById(x).style.background = color;
}
function switchbd(x,y){
if(y == '0')
	document.getElementById(x).style.border = '#000000 1px solid';
else
	document.getElementById(x).style.border = '#ffffff 1px solid';
}
function resize_post_form(x,i){
var rows = document.getElementById(x).t_mes.rows;
	if(i == '-') {
		if(rows <= 15)
			return;
		document.getElementById(x).t_mes.rows -= 5;
	} else
		document.getElementById(x).t_mes.rows += 5;
}
function Showbbcode(x) {
	var ffont = "";
	var fsize = "";
	var fcolor = "";

	var ffontid = document.getElementById('ffont');
	var fsizeid = document.getElementById('fsize');
	var fcolorid = document.getElementById('fcolor');

	switch(x) {
		case "ffont":
			if(ffontid.style.visibility == "visible")
				ffont = "hidden";
			else
				ffont = "visible";
			break;
		case "fsize":
			if(fsizeid.style.visibility == "visible")
				fsize = "hidden";
			else
				fsize = "visible";
			break;
		case "fcolor":
			if(fcolorid.style.visibility == "visible")
				fcolor = "hidden";
			else
				fcolor = "visible";
			break;
	}
	ffont = ffont?ffont:"hidden";
	fsize = fsize?fsize:"hidden";
	fcolor = fcolor?fcolor:"hidden";

	ffontid.style.visibility = ffont;
	fsizeid.style.visibility = fsize;
	fcolorid.style.visibility = fcolor;
}