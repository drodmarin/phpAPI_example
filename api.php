<?php
	/*************************** SET ERROR DISPLAY ****************************/
	error_reporting(E_ALL);
	ini_set('display_errors','On');
	ini_set('display_startup_errors',1);
	/**************************************************************************/
	/******************************** INCLUDES ********************************/
	require_once ('mysqli_wrap/MysqliDb.php');
	$db = new MysqliDb ('HOST', 'USER', 'PASS', 'DB');
	/**************************************************************************/
	
	/******************************** FUNCTIONS *******************************/
	function get_film_list($page,$num)
	{
		global $db;
		
		if($page != null && $num != null)
		{
			$calc	= ($page*$num) - $num;
			$params = Array($calc,$num);
			$films  = $db->rawQuery("SELECT * from films limit ?,?", $params);
		}
		else
		{
			$films = $db->get('films',100); //contains an Array 100 films
		}
		return $films;
	}
	
	function get_film_by_id($id)
	{
		global $db;
		$db->where ("id", $id);
		$film = $db->get('films');
		return $film;

	}
	
	function get_film_by_faID($id)
	{
		global $db;
		$db->where ("faID", $id);
		$film = $db->get('films');
		return $film;
	}
	
	function seek_film($text)
	{
		global $db;
		$params = Array("%".$text."%");
		$films  = $db->rawQuery("SELECT film from fa_films where film like ?", $params);
		return $films; // contains Array of returned rows
	}
	
	/***************************************************************************/
	
	$possible_url = array("get_film_list"
						, "get_film"
						, "get_film_fa"
						, "seek_film");

	$value = "An error has occurred";

	if (isset($_GET["action"]) && in_array($_GET["action"], $possible_url))
	{
		switch ($_GET["action"])
		{
			case "get_film_list":
				if (isset($_GET["page"]) && isset($_GET["num"]))
					$value = get_film_list($_GET["page"],$_GET["num"]);
				else
					$value = get_film_list(null,null);
				break;
			case "get_film":
				if (isset($_GET["id"]))
				  $value = get_film_by_id($_GET["id"]);
				else
				  $value = "Missing argument";
				break;
			case "get_film_fa":
				if (isset($_GET["id"]))
				  $value = get_film_by_faID($_GET["id"]);
				else
				  $value = "Missing argument";
				break;
			case "seek_film":
				if (isset($_GET["text"]))
				  $value = seek_film($_GET["text"]);
				else
				  $value = "Missing argument";
				break;
		}
	}
	print_r(json_encode($value));
?>
