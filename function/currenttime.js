Current_Time();
function Current_Time() {
	var current = new Date();
	var currenttime = current.toString();
	var days = current.getDate();
	var months = current.getMonth()+1;
	var years = current.getFullYear();
	var hours = current.getHours();
	var mins = current.getMinutes();
	var secs = current.getSeconds();
if (months < 10) { var months = "0" +months+ ""; }
if (mins < 10) { var mins = "0" +mins+ ""; }
if (secs < 10) { var secs = "0" +secs+ ""; }
document.Current_Time.clock.value="Time: "+years+"-"+months+"-"+days+" "+hours+":"+mins+":"+secs+"";
setTimeout('Current_Time()', 100);
}