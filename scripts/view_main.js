// globals
var interval;
var categories = [
	'AZEROTH',
	'OUTLAND',
	'NORTHREND',
	'CATACLYSM',
	'PANDARIA',
	'DRAENOR',
	'LEGION',
	'WORLD EVENTS',
	'BONUS'
];
var detailLevel = 1;

window.onload = function() {
	changeContent('current');

	// check for connected char, and show msg if null
	var type = $('#currentType').html();
	if (localStorage[type + '_ids'] == null && type != 'toys')
		newMsg('No character connected!<br>Go to previous page to link one.');
};

// add listener for keydown (left / right)
$(document).keydown(function() {
	if (event.which == 37) {
		changeContent('prev');
	} else if (event.which == 39) {
		changeContent('next');
	}
});

// switch main category (displayed at top of page)
function changeContent(dir) {
	var mainCat = document.getElementById('mainCatText');
	var type = ($('#currentType').html()).toLowerCase();
	var trav = categories.indexOf(mainCat.innerHTML);

	// update title text and rebuild innerHTML
	if (dir == 'prev') {
		mainCat.innerHTML = categories[modulo(trav-1, categories.length)];
		rebuild(categories.indexOf(mainCat.innerHTML)+1, type);
	}
	else if (dir == 'next') {
		mainCat.innerHTML = categories[modulo(trav+1, categories.length)];
		rebuild(categories.indexOf(mainCat.innerHTML)+1, type);
	}
	else if (dir == 'current') {
		mainCat.innerHTML = categories[modulo(trav, categories.length)];
		rebuild(categories.indexOf(mainCat.innerHTML)+1, type);
	}

	changeBackground();
}

// rebuild innerHTML of mainWrapper
function rebuild(content, type) {
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			doStuff(JSON.parse(this.responseText));
		}
	};
	xhttp.open('POST', '../scripts/builder.php');
	xhttp.send(JSON.stringify({
		type: type,
		content: content,
		faction: localStorage.getItem(type + '_faction'),
		class: localStorage.getItem(type + '_class'),
		spec: localStorage.getItem(type + '_spec'),
		ids: localStorage.getItem(type + '_ids'),
		pqual: localStorage.getItem(type + '_quality'),
		depth: detailLevel
	}));
}

function doStuff(result)
{
	// load main page content
	document.getElementById('mainWrapper').innerHTML = result['string'];
	// change progress count (top right)
	document.getElementById('progress').innerHTML = result['countInfo'][1] + ' / ' + result['countInfo'][2];
	// fill progress bar under main title, and change background
	fillProgressBar(result['countInfo'][0]);
	changeBackground();
}

// fill progress bar
function fillProgressBar(collected) {
	var bar = document.getElementById('progBar');
	progBar.style.width = '0px';
	var width = 0;

	// reset interval
	clearInterval(interval);
	interval = null;
	interval = setInterval(frame, 5);

	function frame() {
		if (width >= collected) {
			clearInterval(interval);
		} else {
			width++;
			bar.style.width = width + '%';
		}
	}
}

// change background image
function changeBackground() {
	var mainWrapper = document.getElementById('background');
	var content = document.getElementById('mainCatText').innerHTML;

	switch(content) {
		case categories[0]:
			mainWrapper.style.backgroundImage = 'url("../img/maps/azeroth_old.jpg")';
			break;
		case categories[1]:
			mainWrapper.style.backgroundImage = 'url("../img/maps/outland.jpg")';
			break;
		case categories[2]:
			mainWrapper.style.backgroundImage = 'url("../img/maps/northrend.jpg")';
			break;
		case categories[3]:
			mainWrapper.style.backgroundImage = 'url("../img/maps/maelstrom.jpg")';
			break;
		case categories[4]:
			mainWrapper.style.backgroundImage = 'url("../img/maps/pandaria.jpg")';
			break;
		case categories[5]:
			mainWrapper.style.backgroundImage = 'url("../img/maps/draenor.jpg")';
			break;
		case categories[6]:
			mainWrapper.style.backgroundImage = 'url("../img/maps/broken_isles.jpg")';
			break;
		case categories[7]:
			mainWrapper.style.backgroundImage = 'url("../img/maps/azeroth.jpg")';
			break;
		default:
			mainWrapper.style.backgroundImage = 'url("../img/maps/universe.jpg")';
			break;
	}
}

// change the content type being displayed
function changeType(type) {
	document.getElementById('currentType').innerHTML = type;
	toggleNav('left');
	changeContent('current');
}

// toggle side navs
function toggleNav(side) {
	var navBar = $('#' + side + 'Nav');

	if (navBar.css(side) <= '-230px')
		navBar.css(side, '0px');
	else {
		navBar.css(side, '-230px');
		selectedType = null;
	}
}

// cycle detail level
function cycleDepth() {
	var temp = modulo(detailLevel, 2);
	detailLevel = temp + 1;

	var mainCat = document.getElementById('mainCatText');
	var type = (document.getElementById('currentType').innerHTML).toLowerCase();
	var content = categories.indexOf(mainCat.innerHTML)+1;
	rebuild(content, type);
}

// toggle filters
function filter(button) {
	if ($(button).css("border-color") == "rgb(255, 255, 255)") {
		$(button).css("border-color", "#ccac00");
	} else {
		$(button).css("border-color", "white")
	}
}
