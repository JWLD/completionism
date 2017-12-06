// globals
var selectedType = null;
var categories = [
	'mounts',
	'pets',
	'alchemy',
	'blacksmithing',
	'enchanting',
	'engineering',
	'jewelcrafting',
	'inscription',
	'leatherworking',
	'tailoring',
	'cooking',
	'firstaid',
	'mining'
];

// on page load
window.onload = function() {
	document.getElementById('linkForm').reset();
	setUpPage();
	fillProgressBars();
}

function setUpPage() {
	// add character pictures if they exist
	for (var i = 0; i < categories.length; i++) {
		if (localStorage[categories[i] + '_pic'] != null)
			$('#' + categories[i] + 'Link').css('backgroundImage', localStorage[categories[i] + '_pic']);
	}

	// choose random background
	var rand = Math.floor(Math.random() * 30);
	$('#background').css('backgroundImage', 'url("../img/art/bg' + rand + '.jpg")');
}

function fillProgressBars() {
	for (var i = 0; i < categories.length; i++) {
		// continue if data doesn't exist for this category
		if (localStorage[categories[i] + '_ctotal'] == null)
			continue;

		// calculate and set fill percent
		var data_all = JSON.parse(localStorage[categories[i] + '_ctotal']);
		var data_own = JSON.parse(localStorage[categories[i] + '_cowned']);
		var percent = (data_own[0] / data_all[0]) * 100;
		$('.buttonProgFill.' + categories[i]).css('width', percent + '%');
	}
}

function selectCat(type) {
	// toggle left nav if needed
	if (type == selectedType || document.getElementById('leftNav').style.left != '0px')
		toggleNav('left');

	// update global
	selectedType = type;

	// load saved form values for this type
	var form = document.getElementById('linkForm');
	form.elements['type'].value = type;
	if (localStorage[type + '_formData_region'] != null) {
		form.elements['region'].value = localStorage[type + '_formData_region'];
		ajax_changeRegion(localStorage[type + '_formData_region'], type);
		form.elements['char'].value = localStorage[type + '_formData_char'];
	}
}

function toggleNav(side) {
	var navBar = $('#' + side + 'Nav');

	if (navBar.css(side) <= '-230px')
		navBar.css(side, '0px');
	else {
		navBar.css(side, '-230px');
		selectedType = null;
	}
}

// load realm lists from files
function ajax_changeRegion(region, type) {
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			// change inner html of 'Select Realm' list
			document.getElementById("linkForm").elements["realms"].innerHTML = this.responseText;
			// $('#linkForm select[name="realms"]').html(this.responseText); - jQuery version
			// set the value if it exists in storage (used when this is called from selectCat)
			if (type != null)
				document.getElementById('linkForm').elements['realms'].value = localStorage[type + '_formData_realm'];
		}
	};
	if (region == 'eu')
		xhttp.open('GET', '../data/eu_realms.txt');
	else
		xhttp.open('GET', '../data/us_realms.txt');
	xhttp.send();
}

// submit info from char link box
function ajax_pullData() {
	var form = document.getElementById('linkForm');

	if (form.elements['type'].value == '') {
		newMsg('Please select a category!');
		return;
	} else if (form.elements['region'].value == '') {
		newMsg('Please select a region!');
		return;
	} else if (form.elements['realms'].value == '') {
		newMsg('Please select a realm!');
		return;
	} else if (form.elements['char'].value == '') {
		newMsg('Please enter a character name!');
		return;
	}

	// get data from form and header
	var type = form.elements['type'].value;
	var region = form.elements['region'].value;
	var realm = form.elements['realms'].value;
	var char = form.elements['char'].value;

	// save this data to localStorage
	localStorage[type + '_formData_region'] = region;
	localStorage[type + '_formData_realm'] = realm;
	localStorage[type + '_formData_char'] = char;

	// temporary loading gif for background
	$('#' + type + 'Link').css('backgroundColor', 'white');
	$('#' + type + 'Link').css('backgroundImage', 'url(../img/loading.gif)');

	// set field for battle.net request
	var field = 'professions';
	if (type == 'mounts' || type == 'pets')
		field = type;

	// make AJAX request
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			// if character isn't found
			if (this.responseText == 'no_char') {
				$('#' + type + 'Link').css('backgroundImage', 'url(../img/pointer.png)'); // set to pic instead if in storage, and don't update formData name in LS either...
				$('#' + type + 'Link').css('backgroundColor', '#222');
				newMsg('Character not found!<br>Please try again.');
				return;
			} else {
				doStuff(JSON.parse(this.responseText), type);
			}
		}
	};
	xhttp.open('GET', '../scripts/pullData.php?region=' + region + '&realm=' + realm + '&char=' + char + '&fields=' + field + '&type=' + type, true);
	xhttp.send();
}

// save data to local storage
function doStuff(result, type) {
	// set background image, and store path in localStorage (full path: http://eu.battle.net/static-render/eu/sylvanas/92/99597404-avatar.jpg)
	document.getElementById(type + 'Link').style.backgroundImage = 'url("http://' + result['region'] + '.battle.net/static-render/' + result['region'] + '/' + result['pic'] + '")';
	localStorage.setItem(type + '_pic', 'url("http://' + result['region'] + '.battle.net/static-render/' + result['region'] + '/' + result['pic'] + '")');

	// set faction, class
	localStorage.setItem(type + '_faction', result['faction']);
	localStorage.setItem(type + '_class', result['class']);

	// set spec if relevant
	if (type == 'engineering')
		localStorage.setItem(type + '_spec', result['spec']);

	// set collect data (for use in right nav)
	localStorage[type + '_ctotal'] = JSON.stringify(result['count']['total']);
	localStorage[type + '_cowned'] = JSON.stringify(result['count']['owned']);

	// save ids
	localStorage[type + '_ids'] = JSON.stringify(result['ids']);

	// save qual if pets
	if (type == 'pets')
		localStorage['pets_quality'] = JSON.stringify(result['pqual']);

	// update progress bars
	fillProgressBars();

	// display message
	newMsg('Success!');
}

// load NEW PAGE for a specific type
function loadPage(type) {
	if (type == 'notReady')
		newMsg('Coming soon...');
	else
		location.assign('views/page.php?type=' + type);
}
