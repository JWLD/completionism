window.timeout;

function newMsg(msg) {
	document.getElementById("msgBox").innerHTML = msg;
	
	// reset timeout
	clearTimeout(window.timeout);
	window.timeout = null;
	
	// start new animation
	var x = document.getElementById("msgBox");
	x.className = "show";

	// remove after 3 sec
	window.timeout = setTimeout(function() { x.className = x.className.replace("show", ""); }, 3000);
}