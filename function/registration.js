function CheckRegis(){
	var R1 = document.regis_form.userid.value;
	var R2 = document.regis_form.userpass.value;
	var R3 = document.regis_form.userpass2.value;
	var R4 = document.regis_form.userslspass.value;
	var R5 = document.regis_form.userslspass2.value;
	var R6 = document.regis_form.sex.value;
	var R7 = document.regis_form.email.value;
	if (R1.length < 4 || R1.length > 24) {
		alert("Please enter your ID between 4 - 24 characters.");
		document.regis_form.userid.focus();
		return false;
	} else if (!isAlphaNumeric(R1)) {
		alert("Your ID must be alphanumeric!");
		document.regis_form.userid.focus();
		return false;
	} else if (R2.length < 4 || R2.length > 24) {
		alert("Please enter your password between 4 - 24 characters.");
		document.regis_form.userpass.focus();
		return false;
	} else if (!isAlphaNumeric(R2)) {
		alert("Your password must be alphanumeric!");
		document.regis_form.userpass.focus();
		return false;
	} else if (R3.length < 4 || R3.length > 24) {
		alert("Please enter your retry password between 4 - 24 characters.");
		document.regis_form.userpass2.focus();
		return false;
	} else if (R2 != R3) {
		alert("Please enter your password to be like retry password.");
		document.regis_form.userpass.focus();
		return false;
	} else if (R4.length < 4 || R4.length > 24) {
		alert("Please enter your SLS password between 4 - 24 characters.");
		document.regis_form.userslspass.focus();
		return false;
	} else if (!isAlphaNumeric(R4)) {
		alert("Your SLS password must be alphanumeric!");
		document.regis_form.userslspass.focus();
		return false;
	} else if (R5.length < 4 || R5.length > 24) {
		alert("Please enter your retry SLS password between 4 - 24 characters.");
		document.regis_form.userslspass2.focus();
		return false;
	} else if (R4 != R5) {
		alert("Please enter your SLS password to be like retry SLS password.");
		document.regis_form.userslspass.focus();
		return false;
	} else if (!R6) {
		alert("Please select your gender.");
		return false;
	} else if (R7.indexOf('@') == -1) {
		alert("E-Mail isn't right.");
		document.regis_form.email.focus();
		return false;
	} else {
		document.regis_form.Submit.disabled=true;
		return true;
	}
}
function CheckLogin(){
	var L1 = document.login_form.LG_USER.value;
	var L2 = document.login_form.LG_PASS.value;
	if (L1.length < 4) {
		alert("Please enter your ID at least 4 characters.");
		document.login_form.LG_USER.focus(); return false;
	} else if (!isAlphaNumeric(L1)) {
		alert("Your ID must be alphanumeric!");
		document.login_form.LG_USER.focus(); return false;
		return false;
	} else if (L2.length < 4) {
		alert("Please enter your password at least 4 characters.");
		document.login_form.LG_PASS.focus();
		return false;
	} else if (!isAlphaNumeric(L2)) {
		alert("Your password must be alphanumeric!");
		document.login_form.LG_PASS.focus();
		return false;
	} else {
		document.login_form.Submit.disabled=true;
		return true;
	}
}
function CheckLogin2(){
	var L1 = document.login_form2.LG_USER.value;
	var L2 = document.login_form2.LG_PASS.value;
	if (L1.length < 4) {
		alert("Please enter your ID at least 4 characters.");
		document.login_form2.LG_USER.focus();
		return false;
	} else if (!isAlphaNumeric(L1)) {
		alert("Your password must be alphanumeric!");
		document.login_form2.LG_USER.focus();
		return false;
	} else if (L2.length < 4) {
		alert("Please enter your password at least 4 characters.");
		document.login_form2.LG_PASS.focus();
		return false;
	} else if (!isAlphaNumeric(L2)) {
		alert("Your password must be alphanumeric!");
		document.login_form2.LG_PASS.focus();
		return false;
	} else {
		document.login_form2.Submit.disabled=true;
		return true;
	}
}
function CheckID(){with (document.regis_form)  {if (userid.value == "") {alert("Please enter your ID for check."); document.regis_form.userid.focus(); return false;}else if (userid.value.length < 4) {alert("Please enter your ID at least 4 characters."); document.regis_form.userid.focus(); return false;}window.open("check_register_user.php?userid="+userid.value, "check_id", "menubar=no,scrollbars=no,width=300,height=20");} return true;}
function ViewSC_Code(code){window.open("viewcode.php?sc="+code, "view_security_code", "menubar=no,scrollbars=no,width=270,height=20");}