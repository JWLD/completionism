<?php

ini_set("display_errors", "On");
error_reporting(E_ALL);
include_once "../scripts/sql.php";

// get variables to pull sql data and build html string
$post_data = json_decode(file_get_contents("php://input"), true);
$type = $post_data["type"];
$content = intval($post_data["content"]);

$faction = intval($post_data["faction"]);
$class = intval($post_data["class"]);
$spec = intval($post_data["spec"]);

$ids = json_decode($post_data["ids"]);
$qual = json_decode($post_data["pqual"], true);
$depth = intval($post_data["depth"]);

// do stuff
$result = getData($content, $type, $faction, $class, $spec, $ids, $qual, $depth);
echo json_encode(array("string" => $result[0], "countInfo" => $result[1]));

// pull data from database
function getData($content, $type, $faction, $class, $spec, $ids, $qual, $depth)
{
	// determine class
	if ($ids != null) {
		$classes = [
		'C01',	// warrior
		'C02',	// paladin
		'C03',	// hunter
		'C04',	// rogue
		'C05',	// priest
		'C06',	// death knight
		'C07',	// shaman
		'C08',	// mage
		'C09',	// warlock
		'C10',	// monk
		'C11',	// druid
		'C12'	// demon hunter
	];
		$class = $classes[$class-1];
	}

	// world events
	if ($content == 8)
	{
		$queryP1 = "SELECT * FROM $type WHERE source = 108 AND (faction = $faction || faction = 2) AND (class LIKE '%$class%' || class is NULL) AND sub1 = ";

		// pets sorting
		if ($type == 'pets')
			$queryP2 = " ORDER BY sub1, sub2, name ASC";
		// mounts and toys sorting
		else if ($type == 'mounts' || $type == 'toys')
			$queryP2 = " ORDER BY sub1, sub2, quality, name ASC";
		// professions sorting
		else
			$queryP2 = " ORDER BY sub1, sub2, skill, quality, name ASC";

		$data = [
			"BRAWLER'S GUILD"	=> run_query($queryP1 . "'Brawler\'s Guild'" 		. $queryP2, $type, "select"),
			"BREWFEST"			=> run_query($queryP1 . "'Brewfest'" 				. $queryP2, $type, "select"),
			"CHILDREN'S WEEK"	=> run_query($queryP1 . "'Children\'s Week'" 		. $queryP2, $type, "select"),
			"DARKMOON FAIRE"	=> run_query($queryP1 . "'Darkmoon Faire'" 			. $queryP2, $type, "select"),
			"DAY OF THE DEAD"	=> run_query($queryP1 . "'Day of the Dead'" 		. $queryP2, $type, "select"),
			"HALLOW'S END"		=> run_query($queryP1 . "'Hallow\'s End'"	 		. $queryP2, $type, "select"),
			"LOVE IS IN THE AIR"=> run_query($queryP1 . "'Love is in the Air'" 		. $queryP2, $type, "select"),
			"LUNAR FESTIVAL"	=> run_query($queryP1 . "'Lunar Festival'" 			. $queryP2, $type, "select"),
			"MIDSUMMER"			=> run_query($queryP1 . "'Midsummer Fire Festival'"	. $queryP2, $type, "select"),
			"NOBLEGARDEN"		=> run_query($queryP1 . "'Noblegarden'" 			. $queryP2, $type, "select"),
			"PILGRIM'S BOUNTY"	=> run_query($queryP1 . "'Pilgrim\'s Bounty'" 		. $queryP2, $type, "select"),
			"PIRATE'S DAY"		=> run_query($queryP1 . "'Pirate\'s Day'" 			. $queryP2, $type, "select"),
			"WINTER VEIL"		=> run_query($queryP1 . "'Feast of Winter Veil'" 	. $queryP2, $type, "select"),
			"TIMEWALKING"		=> run_query($queryP1 . "'Timewalking'" 			. $queryP2, $type, "select")
		];
	}
	// bonus
	else if ($content == 9)
	{
		$queryP1 = "SELECT * FROM $type WHERE content = $content AND (faction = $faction || faction = 2) AND (class LIKE '%$class%' || class is NULL) AND source = ";
		$queryP2 = " ORDER BY added, quality, name ASC";

		if ($type == 'mounts' || $type == 'pets' || $type == 'toys')
			$queryV3 = " ORDER BY sub1, sub2, quality, name ASC";
		else
			$queryV3 = " ORDER BY sub1, sub2, skill, quality, name ASC";

		$data = [
			"CLASS"				=> run_query($queryP1 . 301 . $queryV3, $type, 'select'),
			"PVP"				=> run_query($queryP1 . 104 . $queryV3, $type, 'select'),
			"IN-GAME EVENTS"	=> run_query($queryP1 . 204 . $queryV3, $type, 'select'),
			"PROMOTIONS"		=> run_query($queryP1 . 201 . $queryV3, $type, 'select'),
			"STORE"				=> run_query($queryP1 . 202 . $queryP2, $type, 'select'),
			"TCG"				=> run_query($queryP1 . 203 . $queryP2, $type, 'select'),
			"LEGACY"			=> run_query($queryP1 . 100 . $queryV3, $type, 'select')
		];
	}
	// xpacs (mounts, pets, toys)
	else if ($type == 'mounts' || $type == 'pets' || $type == 'toys')
	{
		$queryP1 = "SELECT * FROM $type WHERE content = $content AND (faction = $faction || faction = 2) AND source = ";

		if ($depth == 2)
		{
			if ($type == 'pets')
				$queryP2 = " ORDER BY sub1, sub2, name ASC";
			else
				$queryP2 = " ORDER BY sub1, sub2, quality, name ASC";
		}
		else
		{
			if ($type == 'pets')
				$queryP2 = " ORDER BY sub1, name ASC";
			else
				$queryP2 = " ORDER BY sub1, quality, name ASC";
		}

		$data = [
			"ACHIEVEMENT"	=> run_query($queryP1 . 101 . $queryP2, $type, 'select'),
			"DROP"			=> run_query($queryP1 . 102 . $queryP2, $type, 'select'),
			"GARRISON"	 	=> run_query($queryP1 . 303 . $queryP2, $type, 'select'),
			"ORDER HALL" 	=> run_query($queryP1 . 304 . $queryP2, $type, 'select'),
			"PET BATTLE" 	=> run_query($queryP1 . 305 . $queryP2, $type, 'select'),
			"PROFESSION" 	=> run_query($queryP1 . 103 . $queryP2, $type, 'select'),
			"PVP"  			=> run_query($queryP1 . 104 . $queryP2, $type, 'select'),
			"QUEST" 		=> run_query($queryP1 . 105 . $queryP2, $type, 'select'),
			"REPUTATION"	=> run_query($queryP1 . 106 . $queryP2, $type, 'select'),
			"TREASURE"		=> run_query($queryP1 . 307 . $queryP2, $type, 'select'),
			"VENDOR"		=> run_query($queryP1 . 107 . $queryP2, $type, 'select'),
			"WORLD EVENT"	=> run_query($queryP1 . 108 . $queryP2, $type, 'select'),
			"UNASSIGNED"	=> run_query($queryP1 . 10 	. $queryP2, $type, 'select')
		];
	}
	// xpacs (professions)
	else
	{
		// professions with specialisation-specific recipes
		if ($type == 'engineering')
			$queryP1 = "SELECT * FROM $type WHERE content = $content AND (faction = $faction || faction = 2) AND (class LIKE '%$class%' || class is NULL) AND (spec = $spec || spec = 0) AND source = ";
		// professions with class-specific recipes
		else if ($type == 'cooking')
			$queryP1 = "SELECT * FROM $type WHERE content = $content AND (faction = $faction || faction = 2) AND (class LIKE '%$class%' || class is NULL) AND source = ";
		// all other professions
		else
			$queryP1 = "SELECT * FROM $type WHERE content = $content AND (faction = $faction || faction = 2) AND source = ";

		// order legion content differently
		if ($content == 7)
			$queryP2 = " ORDER BY sub1, sub2, quality, name, rank ASC";
		else {
			// if ($depth == 1)
				// $queryP2 = " ORDER BY sub1, skill, quality, name ASC";
			// else
				$queryP2 = " ORDER BY sub1, skill, quality, name ASC"; //sub2
		}

		// trainer sorting order (change this - add sorting value to SQL database instead)
		if ($type == 'engineering')
			$queryP3 = " ORDER BY sub1 DESC, sub2, skill, quality, name ASC";
		else
			$queryP3 = " ORDER BY sub1, sub2, skill, quality, name ASC";

		$data = [
			"TRAINER"		=> run_query($queryP1 . 109 . $queryP3, $type, 'select'),
			"SPECIALISATION"=> run_query($queryP1 . 308 . $queryP2, $type, 'select'),
			"ACHIEVEMENT"	=> run_query($queryP1 . 101 . $queryP2, $type, 'select'),
			"DISCOVERY"		=> run_query($queryP1 . 302 . $queryP2, $type, 'select'),
			"DROP"			=> run_query($queryP1 . 102 . $queryP2, $type, 'select'),
			"PROFESSION" 	=> run_query($queryP1 . 103 . $queryP2, $type, 'select'),
			"PVP" 			=> run_query($queryP1 . 104 . $queryP2, $type, 'select'),
			"QUEST" 		=> run_query($queryP1 . 105 . $queryP2, $type, 'select'),
			"REPUTATION"	=> run_query($queryP1 . 106 . $queryP2, $type, 'select'),
			"TREASURE"		=> run_query($queryP1 . 307 . $queryP2, $type, 'select'),
			"VENDOR"		=> run_query($queryP1 . 107 . $queryP2, $type, 'select'),
			"WORLD EVENT"	=> run_query($queryP1 . 108 . $queryP2, $type, 'select'),
			"UNASSIGNED"	=> run_query($queryP1 . 10  . $queryP2, $type, 'select')
		];
	}

	// create a big HTML string to return
	$keys = array_keys($data);
	$string = "<div id='mainBlock'>";

	// iterate through categories, adding the non-empty ones to the string
	$loopIndex = -1;

	foreach ($data as $category)
	{
		$loopIndex++;

		if (count($category) > 0)
		{
			$string .= buildHTML($keys[$loopIndex], $category, $type, $content, $qual, $ids, $depth);
		}
	}

	$string .= "</div>";

	// return
	$countInfo = countCollected($data, $ids);
	$result = array($string, $countInfo);
	return $result;
}

// format data into webpage
function buildHTML($catName, $category, $type, $content, $qual, $ids, $depth)
{
	// main heading (DROP, PROF etc.)
	$string = "<div class='category' id='" . $catName . "'>";
	$string .= "<h1>" . $catName . "</h1>";

	// trackers
	$sub1 = NULL;
	$sub2 = NULL;
	$count = 0;

	// for each item in a category
	foreach ($category as $record)
	{
		$count++;

		if ($record["sub1"] == NULL && $sub1 != "none") {
			$string .= "<table class='itemTable'>";
			$sub1 = "none";
		}

		// add new sub1 if required (if different to current sub1, and not null)
		if ($record["sub1"] != NULL && $sub1 != $record["sub1"])
		{
			// if this is not the first element, close the table before adding a new heading
			if ($count > 1 && $sub1 != "none")
				$string .= "</table>";

			// alter and add subheading, and reset sub2
			$split = explode("_", $record["sub1"]);
			count($split) > 1 ? $h2 = $split[1] : $h2 = $split[0];
			$string .= "<h2>" . strtoupper($h2) . "</h2>";
			$sub2 = null;

			// open new table if this record doesn't have a new sub2 heading
			// if ($sub2 == $record["sub2"] || $record["sub2"] == NULL)
			if ($sub1 != "none")
				$string .= "<table class='itemTable'>";

			$sub1 = $record["sub1"];
		}

		// add new sub2 if required
		// if ($record["sub2"] != NULL && $sub2 != $record["sub2"])
// 		{
// 			$string .= "</table>";
//
// 			// alter and add subheading (allows sub2 sorting within database)
// 			$split = explode("_", $record["sub2"]);
// 			count($split) > 1 ? $h3 = $split[1] : $h3 = $split[0];
// 			$string .= "<h3>" . strtoupper($h3) . "</h3>";
//
// 			// open new table
// 			$string .= "<table class='itemTable'>";
// 			$sub2 = $record["sub2"];
// 		}

		// new table row
		$string .= "<tr>";

		// add skill level if profession (not incl. draenor or legion, except for legion first aid)
		if (($type != 'mounts' && $type != 'pets' && $type != 'toys') && (($content != 6 && $content != 7) || ($content == 7 && $type == 'firstaid')))
		{
			// if null
			if ($record["skill"] == NULL) {
				$string .= "<td class='skillBox'>" . "?" . "</td>";
			} else {
				$string .= "<td class='skillBox'>" . $record["skill"] . "</td>";
			}
		}

		// add classes, quality (more complex for pets), and item name
		if ($type == "pets")
		{
			if ($qual != null)
			{
				if (array_key_exists($record["id"], $qual))
					$string .= "<td class='mount'>" . "<a class='mount " . "q0" . $qual[$record["id"]] . "' href='" .
					"http://wowhead.com/npc=" . $record["id"] . "' target='_blank' rel='npc=" . $record["id"] . "'>" . $record["name"]. "</a></td>";
				else
					$string .= "<td class='mount'>" . "<a class='mount " . "q0" . "' href='" .
					"http://wowhead.com/npc=" . $record["id"] . "' target='_blank' rel='npc=" . $record["id"] . "'>" . $record["name"]. "</a></td>";
			}
			else
			{
				$string .= "<td class='mount'>" . "<a class='mount " . "q0" . "' href='" .
				"http://wowhead.com/npc=" . $record["id"] . "' target='_blank' rel='npc=" . $record["id"] . "'>" . $record["name"]. "</a></td>";
			}
		}
		else if ($type == "toys")
		{
			$string .= "<td class='mount'>" . "<a class='mount " . "q0" . $record["quality"] . "' href='" .
			"http://wowhead.com/item=" . $record["id"] . "' target='_blank'>" . $record["name"]. "</a></td>";
		}
		else
		{
			$string .= "<td class='mount'>" . "<a class='mount " . "q0" . $record["quality"] . "' href='" .
			"http://wowhead.com/spell=" . $record["id"] . "' target='_blank' rel='spell=" . $record["id"] . "'>" . $record["name"]. "</a></td>";
		}

		// add ranks for legion recipes
		if ($content == 7 && $type != 'mounts' && $type != 'pets' && $type != 'toys' && $type != 'firstaid')
		{
			if ($record["rank"] == NULL)
				$string .= "<td class='squareBox'> - </td>";
			else
				$string .= "<td class='squareBox'>" . $record["rank"] . "</td>";
		}

		// add tick or cross depending on whether id exists in $ids array
		if ($ids != null)
		{
			if (in_array($record["id"], $ids))
				$string .= "<td class='tick'>&#10004;</td></tr>";
			else
				$string .= "<td class='cross'>&#10008;</td></tr>";
		}
		else
		{
			$string .= "<td class='cross'>&#10008;</td></tr>";
		}

		// if this is the last element, close the table
		if ($count === count($category))
			$string .= "</table>";
	}

	// close category div
	$string .= "</div>";

	return $string;
}

// return completion percentage
function countCollected($data, $ids)
{
	$keys = array_keys($data);

	// count the number of items owned within $data
	$count = 0;
	$total = 0;

	for ($i = 0; $i < count($data); $i++)
	{
		$key = $keys[$i];

		for ($j = 0; $j < count($data[$key]); $j++)
		{
			$total++;

			if ($ids != null)
				if (in_array($data[$key][$j]["id"], $ids))
					$count++;
		}
	}

	// calculate and return percentage
	if ($total > 0)
		$percent = ($count / $total) * 100;
	else
		$percent = 0;

	$countInfo = [$percent, $count, $total];

	return $countInfo;
}

?>
