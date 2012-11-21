<?php

	require_once "dbfuncs.php";

	function getAllAgents()
	{

		$query = "SELECT * FROM `users` ORDER BY `name` ASC";
		$result = mysql_query($query);

		return $result;
	}

	function getIt($y)
	{
		if ($y == "y")
		{
			return '<span class="correct">Yes</span>';
		} elseif ($y == "n")
		{
			return '<span class="incorrect">No</span>';
		} else
		{
			return "N/A";
		}
	}
	
	function getResult($i)
	{
		if($i == 1)
		{
			return "Pass";
		}
		else
			{
				return "Fail";
			}
	}
?>