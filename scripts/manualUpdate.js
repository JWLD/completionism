$(document).ready(function() {
	$('#myFile').on('change', function() {
		// update file label when changed
		var x = $("#myFile").get(0).files[0];
		$('#fileLabel').html(x.name);
	});
});

function workData() {
	// check for complete form
	if (!$('#f_faction').val()) {
		newMsg('Please select a faction');
		return
	} else if (!$('#f_class').val()) {
		newMsg('Please select a class');
		return;
	} else if (!$('#myFile').get(0).files[0]) {
		newMsg('Please upload a file');
		return;
	}

	// check FileReader is supported
	if (!window.FileReader) {
		newMsg('Your browser is not supported. Sorry!');
		return false;
	}

	// store class and faction in local storage
	localStorage['toys_class'] = $("#f_class").val();
	localStorage['toys_faction'] = $("#f_faction").val();

	// read file
	var x = $("#myFile").get(0).files[0];
	var reader = new FileReader();
	reader.readAsText(x);
	$(reader).on('load', processFile);
}

function processFile(f) {
	var data_raw = f.target.result;
	var data = data_raw.match(/data = "(.*)"/)[1].split(",");

	// convert from strings to numbers
	for (var i = 0; i < data.length; i++)
		data[i] = parseInt(data[i]);

	// save ids to localStorage
	localStorage['toys_ids'] = JSON.stringify(data);
}
