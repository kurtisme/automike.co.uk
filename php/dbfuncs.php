<?php
	define("HOST", "localhost");
	define("USER", "web50-123stats");
	define("PASSWORD", "123stats");
	define("DATABASE", "web50-123stats");

	mysql_connect(HOST, USER, PASSWORD);
	mysql_select_db(DATABASE);

	function select_stats_by_date($id, $datefrom = 0, $dateto = 0)
	{
		if ($datefrom == 0)
		{
			$datefrom = date("Y-m-d", mktime(0, 0, 0, date('m'), 1, date('Y')));
		}
		if ($dateto == 0)
		{
			$dateto = date("Y-m-d", strtotime('yesterday'));
		}

		if ($id == 'all')
		{
			$numAgentsQuery = mysql_query("SELECT * FROM `users`");
			$numAgents = mysql_num_rows($numAgentsQuery);

			$query = "SELECT `date`, 
						SUM(`calls`) AS calls, 
						SUM(`calls_logged`) AS calls_logged, 
						SUM(`comments`) AS comments,
						SUM(`ticket_responses`) AS ticket_responses, 
						SUM(`notes`) AS notes, 
						SUM(`tickets_logged`) AS tickets_logged,
						SEC_TO_TIME(SUM(TIME_TO_SEC(`rseticket`))) AS rseticket,
						SEC_TO_TIME((AVG(`calls` * TIME_TO_SEC(`aht`)))/$numAgents) AS aht,
						CAST(AVG(`upsell`) AS DECIMAL(5,2)) AS upsell
						FROM `stats`
						WHERE `date` 
							BETWEEN '$datefrom' AND '$dateto' 
						GROUP BY `stats`.`date`";
		}
		else
		{
			$query = "SELECT * FROM `stats` INNER JOIN `users` ON `stats`.`userid`=`users`.`userid` WHERE `stats`.`userid`='$id' AND `date` BETWEEN '$datefrom' AND '$dateto' ORDER BY `stats`.`date` ASC";
		}

		$result = mysql_query($query);
		return $result;

	}

	function getTotals($id, $datefrom, $dateto)
	{
		if ($id == 'all')
		{
			$query = "SELECT 
					SUM(`calls`) AS calls, 
					SUM(`calls_logged`) AS logged, 
					SUM(`comments`) AS comments, 
					SUM(`ticket_responses`) AS responses,
					SUM(`notes`) AS notes,SUM(`tickets_logged`) AS tlogged, 
					SEC_TO_TIME( SUM( TIME_TO_SEC(  `rseticket` ) ) ) AS raising_time, 
					SUM(`upsell`) AS upsell 
					FROM `stats` 
					WHERE `date` 
						BETWEEN '" . $datefrom . "' AND '" . $dateto . "'";
		}
		else
		{
			//Query to total all relevant colums. For time values, convert to seconds -> sum -> convert to time
			$query = "SELECT 
					SUM(`calls`) AS calls, 
					SUM(`calls_logged`) AS logged, 
					SUM(`comments`) AS comments, 
					SUM(`ticket_responses`) AS responses,
					SUM(`notes`) AS notes,SUM(`tickets_logged`) AS tlogged, 
					SEC_TO_TIME( SUM( TIME_TO_SEC(  `rseticket` ) ) ) AS raising_time, 
					SUM(`upsell`) AS upsell 
					FROM `stats` 
					WHERE `userid` = '" . $_POST['agent'] . "'	
					AND `date` 
						BETWEEN '" . $datefrom . "' AND '" . $dateto . "'";
		}
		//Run the beast.
		$result = mysql_query($query);

		return $result;
	}

	function getAvgs($id, $datefrom, $dateto)
	{
		//Query to average all relevant colums. For time values, convert to seconds -> sum -> convert to time
		//Each one has been CAST as DECIMAL(5,2) meaning that no more than 5 digits will be displayed (ie. 100.00)
		//and no more than 2 decimal places.
		if ($id == 'all')
		{
			$query = "SELECT 
					CAST(AVG(`calls`) AS DECIMAL(5, 2)) AS calls,
					CAST(AVG(`calls_logged`) AS DECIMAL(5, 2)) AS logged, 
					CAST(AVG(`comments`) AS DECIMAL(5, 2)) AS comments, 
					SEC_TO_TIME( AVG( TIME_TO_SEC(  `aht` ) ) ) AS aht, 
					CAST(AVG(`ticket_responses`) AS DECIMAL(5, 2)) AS responses,
					CAST(AVG(`notes`) AS DECIMAL(5,2)) AS notes,
					CAST(AVG(`tickets_logged`) AS DECIMAL(5, 2)) AS tlogged, 
					SEC_TO_TIME( AVG( TIME_TO_SEC(  `rseticket` ) ) ) AS raising_time, 
					CAST(AVG(`upsell`) AS DECIMAL(5, 2)) AS upsell 
					FROM `stats` 
					WHERE `date` 
					BETWEEN '" . $datefrom . "' AND '" . $dateto . "'";
		}
		else
		{
			$query = "SELECT 
					CAST(AVG(`calls`) AS DECIMAL(5, 2)) AS calls,
					CAST(AVG(`calls_logged`) AS DECIMAL(5, 2)) AS logged, 
					CAST(AVG(`comments`) AS DECIMAL(5, 2)) AS comments, 
					SEC_TO_TIME( AVG( TIME_TO_SEC(  `aht` ) ) ) AS aht, 
					CAST(AVG(`ticket_responses`) AS DECIMAL(5, 2)) AS responses,
					CAST(AVG(`notes`) AS DECIMAL(5,2)) AS notes,
					CAST(AVG(`tickets_logged`) AS DECIMAL(5, 2)) AS tlogged, 
					SEC_TO_TIME( AVG( TIME_TO_SEC(  `rseticket` ) ) ) AS raising_time, 
					CAST(AVG(`upsell`) AS DECIMAL(5, 2)) AS upsell 
					FROM `stats` 
					WHERE `userid` = '" . $_POST['agent'] . "'
					AND `date` 
					BETWEEN '" . $datefrom . "' AND '" . $dateto . "'";
		}
		//Run the beast.
		$result = mysql_query($query);
		return $result;
	}

	function getTotalP10s($id, $datefrom, $dateto)
	{
		$query = "SELECT SUM(`p10s`) AS totalp10 FROM `p10s` WHERE `userid`='$id' AND (`date` BETWEEN '$datefrom' AND '$dateto')";
		$result = mysql_query($query);
		$total = mysql_fetch_array($result);

		return $total['totalp10'];
	}

	function getTotalQA($id, $datefrom, $dateto)
	{
		//Select all QA forms for one agent between two dates and return the average of scores. Again, cast as DECIMAL(5,2)
		//to include 0.00 <= x <= 100.00
		$query = "SELECT CAST(AVG(`finalscore`) AS DECIMAL(5, 2)) AS scoreavg FROM `qaresult` WHERE `agentid`='$id' AND (`calldate` BETWEEN '$datefrom' AND '$dateto')";

		$result = mysql_query($query);

		$total = mysql_fetch_array($result);
		
		return $total['scoreavg'];
	}
?>