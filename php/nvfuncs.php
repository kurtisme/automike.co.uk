<?php

	require_once "php/dbfuncs.php";
	function getAgentNovatech($id, $datefrom, $dateto)
	{
		$data = getInfo($id, $datefrom, $dateto);

		return $data;
	}

	function getInfo($id, $datefrom = 0, $dateto = 0)
	{

		if ($datefrom == 0)
		{
			$datefrom = date("Y-m-d", strtotime("Last Monday"));
		}
		if ($dateto == 0)
		{
			$dateto = date("Y-m-d", strtotime("Last Friday"));
		}
		
		$results = array();

		$callquery = "SELECT * FROM `stats` WHERE `userid` = '$id' AND (`date` BETWEEN '$datefrom' AND '$dateto')";
		$result = mysql_query($callquery);
		while ($info = mysql_fetch_array($result))
		{
			$callLoggingPoints = $callLoggingPoints + calcLogCalls($info);
			$upsellLoggingPoints = $upsellLoggingPoints + calcUpsell($info);
			$rseTicketPoints = $rseTicketPoints + calcRseTicket($info);
			$tktResponsePoints = $tktResponsePoints + calcTktResponses($info);
			$ahtPoints = $ahtPoints + calcAHT($info);
		}

		$qaquery = "SELECT * FROM `novatech` 
		JOIN `users` ON `novatech`.`userid`=`users`.`userid`
		JOIN `qaresult` ON `novatech`.`qascore`=`qaresult`.`logid`
		WHERE `novatech`.`userid` = '$id' AND (`novatech`.`date` BETWEEN '$datefrom' AND '$dateto')";
		
		$result = mysql_query($qaquery);

		while ($info = mysql_fetch_array($result))
		{
			$qaPoints = calcQA($info);
		}

		$p10query = "SELECT * FROM `p10s` WHERE `userid` = '$id' AND (`date` BETWEEN '$datefrom' AND '$dateto')";

		$result = mysql_query($p10query);
		while ($info = mysql_fetch_array($result))
		{
			$p10Points = calcP10s($info);
		}
		$results = array(
			'agentid' => $id,
			'call' => $callLoggingPoints,
			'upsell' => $upsellLoggingPoints,
			'qa' => $qaPoints,
			'rsetick' => $rseTicketPoints,
			'p10' => $p10Points,
			'tresponse' => $tktResponsePoints,
			'aht' => $ahtPoints,
		);
		return $results;

	}

	function calcTotals($data)
	{
		$total = array();
		foreach ($data as $agent)
		{
			$total[$agent['agentid']] = $agent['call'] + $agent['upsell'] + $agent['qa'] + $agent['p10'] + $agent['rsetick'] + $agent['aht'] + $agent['tresponse'];
		}

		return $total;
	}

	function calcLogCalls($data)
	{
		//$data MUST be of type mysql::mysql_result

		if (($data['calls'] - $data['calls_logged'] <= 3) && $data['calls'] != 0)
		{
			return 1;

		}
		else
		{
			return 0;
		}
	}

	function calcUpsell($data)
	{

		if ($data['upsell'] >= 80)
		{
			return 2;

		}
		else
		{
			return 0;
		}
	}

	function calcQA($data)
	{

		if (($data['finalscore'] >= 70) && ($data['pass'] = 1))
		{
			return 3;

		}
		else
		{
			return 0;
		}
		
		return 0;
	}

	function calcRseTicket($data)
	{

		if ((strtotime($data['rseticket']) <= strtotime("00:45:00")) && (strtotime($data['rseticket']) != strtotime("00:00:00")))
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}

	function calcP10s($data)
	{

		return 2 * $data['p10s'];

	}

	function calcTktResponses($data)
	{

		return (int)($data['ticket_responses'] / 10);

	}

	function calcAHT($data)
	{

		if (strtotime($data['aht']) <= strtotime("00:07:00") && strtotime($data['aht']) != strtotime("00:00:00"))
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}


	function getname($id1)
	{
		$nameq = "SELECT `name` FROM `users` WHERE `userid` = '$id1' ";
		$query1 = mysql_query($nameq);
		
		echo $query1;
		
		
	}
?>