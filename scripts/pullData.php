<?php

ini_set("display_errors", "on");
error_reporting(E_ALL);
require "../scripts/functions.php";
include_once "../scripts/sql.php";

// make the request from battle.net
if ($_GET["region"] == "eu")
	$locale = "en_GB";
else
	$locale = "en_US";

$request = "https://" . $_GET["region"] . ".api.battle.net/wow/character/" . $_GET["realm"] . "/" . $_GET["char"] . "?fields=" . $_GET["fields"] . "&locale=" . $locale . "&apikey=" . $API_key;
$headers = get_headers($request);
if ($headers[0] == "HTTP/1.1 404 Not Found") {
	echo "no_char";
	die();
}

$data_raw = file_get_contents($request);
$data = json_decode($data_raw, true);

// global variables
$spec = 0;
$ids = array();
$pqual = array();

// get ids for collected mounts
if ($_GET["fields"] == "mounts")
	for ($i = 0; $i < count($data["mounts"]["collected"]); $i++)
		array_push($ids, $data["mounts"]["collected"][$i]["spellId"]);

// get ids for collected pets
if ($_GET["fields"] == "pets")
{
	for ($i = 0; $i < count($data["pets"]["collected"]); $i++)
	{
		$id = $data["pets"]["collected"][$i]["creatureId"];
		$pqual[$id] = $data["pets"]["collected"][$i]["qualityId"];
		array_push($ids, $id);
	}
}

// get ids for collected profession recipes
if ($_GET["fields"] == "professions")
{
	$type = $_GET["type"];

	// set profession level (primary or secondary)
	if ($type == "cooking" || $type == "firstaid")
		$profRank = "secondary";
	else
		$profRank = "primary";

	// find specific profession index in $data["professions"]["primary"]
	$index = 0;
	for ($i = 0; $i < count($data["professions"][$profRank]); $i++)
		if (strtolower($data["professions"][$profRank][$i]["name"]) == $type)
			$index = $i;

	// update 'owned' in database for each spellID found in the data set
	for ($i = 0; $i < count($data["professions"][$profRank][$index]["recipes"]); $i++)
		array_push($ids, $data["professions"][$profRank][$index]["recipes"][$i]);

	// legion ranks - save previous ranks as owned
	if ($_GET["fields"] == "professions")
	{
		// get legion recipes with ranks
		$ranked_2 = run_query("SELECT * FROM $type WHERE content = 7 AND rank = 2", $type, "select");
		$ranked_3 = run_query("SELECT * FROM $type WHERE content = 7 AND rank = 3", $type, "select");

		for ($i = 0; $i < count($ranked_2); $i++)
		{
			if (in_array($ranked_2[$i]["id"], $ids))
			{
				$name = $ranked_2[$i]["name"];
				$prevRankID = run_query("SELECT * FROM $type WHERE name = '$name' AND rank = 1", null, 'select');
				array_push($ids, $prevRankID[0]["id"]);
			}
		}

		for ($i = 0; $i < count($ranked_3); $i++)
		{
			if (in_array($ranked_3[$i]["id"], $ids))
			{
				$name = $ranked_3[$i]["name"];
				$prevRankIDs = run_query("SELECT * FROM $type WHERE name = '$name' AND (rank = 1 || rank = 2)", null, 'select');
				array_push($ids, $prevRankIDs[0]["id"], $prevRankIDs[1]["id"]);
			}
		}
	}

	// check for specialisation recipes
	if ($type == "engineering")
	{
		if (in_array(12895, $data["professions"]["primary"][0]["recipes"]))
			$spec = 1;
		else if (in_array(12715, $data["professions"]["primary"][0]["recipes"]))
			$spec = 2;
	}
}

function countData($ids)
{
	global $data;
	global $spec;
	$type = $_GET["type"];
	$class = $data["class"];
	$faction = $data["faction"];

	$count_data = array("total" => array(), "owned" => array());

	$total_all = 0;
	$total_owned = 0;

	// save data for each content type
	for ($i = 1; $i < 10; $i++)
	{
		$roles = [
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
		$role = $roles[$class-1];

		// professions with specialisation-specific recipes
		if ($type == "engineering")
			$result = run_query("SELECT * FROM $type WHERE content = $i AND (class LIKE '%$role%' || class is NULL) AND (faction = $faction || faction = 2) AND (spec = $spec || spec = 0) ", $type, "select");
		// every other type
		else
			$result = run_query("SELECT * FROM $type WHERE content = $i AND (class LIKE '%$role%' || class is NULL) AND (faction = $faction || faction = 2)", $type, "select");

		// save total number of recipes for this type and content
		$total = count($result);

		// save number of owned recipes for this type and content
		$owned = 0;
		for ($j = 0; $j < count($result); $j++)
			if (in_array($result[$j]["id"], $ids))
				$owned++;

		array_push($count_data["total"], $total);
		array_push($count_data["owned"], $owned);

		// add to overall totals
		$total_all += $total;
		$total_owned += $owned;
	}

	// add overall totals to arrays
	array_unshift($count_data["total"], $total_all);
	array_unshift($count_data["owned"], $total_owned);

	return $count_data;
}

// return the data
echo json_encode(array(
	"pic" => $data["thumbnail"],
	"region" => $_GET["region"],
	"faction" => $data["faction"],
	"class" => $data["class"],
	"spec" => $spec,
	"count" => countData($ids),
	"ids" => $ids,
	"pqual" => $pqual
));

?>
