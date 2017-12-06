<!DOCTYPE html>

<html>
	<head>
		<!-- meta -->
		<title>Update</title>
		<link rel="icon" type="image/png" href="../img/favicon/favicon-16x16.png" sizes="16x16"/>
		<link rel="icon" type="image/png" href="../img/favicon/favicon-32x32.png" sizes="32x32"/>
		<!-- style -->
		<link rel="stylesheet" type="text/css" href="../css/update.css">
		<!-- libraries -->
		<script type="text/javascript" src="../includes/jquery-3.1.1.js"></script>
		<script type="text/javascript" src="//wow.zamimg.com/widgets/power.js"></script>
		<!-- scripts -->
		<script type="text/javascript" src="../scripts/manualUpdate.js"></script>
		<script type="text/javascript" src="../scripts/msgBox.js"></script>
	</head>
	<body>
		<div id="msgBox"></div>
		<div id="header">Update From File</div>
		<div id="notHeader">
			<form id="updateForm" action="Javascript:workData()">
				<select name="faction" id="f_faction">
					<option value="" selected>Select Faction</option>
					<option value="0">Alliance</option>
					<option value="1">Horde</option>
				</select>
				<select name="class" id="f_class">
					<option value="" selected>Select Class</option>
					<option value="6">Death Knight</option>
					<option value="12">Demon Hunter</option>
					<option value="11">Druid</option>
					<option value="3">Hunter</option>
					<option value="8">Mage</option>
					<option value="10">Monk</option>
					<option value="2">Paladin</option>
					<option value="5">Priest</option>
					<option value="4">Rogue</option>
					<option value="7">Shaman</option>
					<option value="9">Warlock</option>
					<option value="1">Warrior</option>
				</select>
				<label for="myFile" id="fileLabel">Select File</label>
				<input type="file" id="myFile" accept=".lua"/>
				<input type="submit" id="form_submit" value="Submit">
			</form>
		</div>
	</body>
</html>

<!-- Macintosh HD / Applications / World of Warcraft / WTF / Account / NUMBER / SavedVariables / Completionism.lua -->