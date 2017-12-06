<?php

// update SQL database
function run_query($sql, $type, $query_type)
{
	$info_raw = file_get_contents("../../etc/config.json");
	$info = json_decode($info_raw, true);
	
	// create connection
	$conn = new mysqli($info["hostname"], $info["username"], $info["password"], $info["dbname"]);

	// check connection
	if ($conn->connect_error) 
	{
		die("Connection failed: " . $conn->connect_error);
	}
	
	// run query and store results
    $result = $conn->query($sql);

	// close connection
	$conn->close();

	// if running a SELECT query, format and return as associative array
	if ($query_type == 'select')
	{
		$rows = [];

		// professions
		if ($type != 'mounts' && $type != 'pets' && $type != 'toys')
		{
		    while($row = $result->fetch_assoc())
			{
		        $rows[] = [
		        	"id" => $row["id"],
					"name" => $row["name"],
					"quality" => $row["quality"],
					"skill" => $row["skill"],
					"rank" => $row["rank"],
					"sub1" => $row["sub1"],
					"sub2" => $row["sub2"],
		        ];
		    }
		}
		// mounts, pets, toys
		else
		{
		    while($row = $result->fetch_assoc())
			{
		        $rows[] = [
		        	"id" => $row["id"],
					// "item_id" => $row["item_id"],
					"name" => $row["name"],
					"source" => $row["source"],
					"content" => $row["content"],
					"quality" => $row["quality"],
					"sub1" => $row["sub1"],
					"sub2" => $row["sub2"]
		        ];
		    }
		}
	
		return $rows;
	}
	
	// if running a SELECT COUNT query, return as number
	if ($query_type == 'count')
	{
		$data = $result->fetch_array();
		return $data[0];
	}
}

?>