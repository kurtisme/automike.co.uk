<!DOCTYPE html>
<html>
	<head>
		<title>AutoMike v0.3</title>
		<?php
		require_once "php/dbfuncs.php";
		//require_once "php/stdhead.php";
		?>
		<style type="text/css">
			.working {border-collapse: collapse;font-family:"Calibri";}
.main-wrapper{	width: 1100px;	margin:0 auto;}
.innerbody{	width: 550px;margin:0 auto;}
.working td, th {padding: 5px;text-align: center;}
.working td{}
.working th{background-color: #000000;color:white;}
.name{	width:150px;}
tr:nth-of-type(odd){background-color: #FFFFFF;color:black ;}
tr:nth-of-type(even){background-color:#708090;}

		</style>
		<link href="css/datepicker.css" rel="stylesheet">
		<link rel="stylesheet" href="css/bootstrap-responsive.min.css">
		<link rel="stylesheet" href="css/main.css">
		<script src="js/vendor/jquery-1.8.2.min.js"></script>
		<script src="js/bootstrap-datepicker.js"></script>
		<script>
			$(function() {
				$('#dp1').datepicker({
					format : 'yyyy-mm-dd'
				});
				$('#dp2').datepicker({
					format : 'yyyy-mm-dd'
				});

			});
		</script>
	</head>
	<body>
		
		<div class="main-wrapper">
			<div class="innerbody">
		<h1>AutoBusinessMike - Call Stats Report</h1>
		
		<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
			Agent
			<select name="agent">
				<?php
					$agents = mysql_query("SELECT * FROM `users` ORDER BY `name` ASC");
					while ($row = mysql_fetch_array($agents))
					{
						?>
						<option value="<?= $row['userid']; ?>"><?= $row['name']; ?></option>
						<?php } ?>
			</select><!--
			<input type="date" class="datepicker" name="datefrom" />
			<input type="date" class="datepicker" name="dateto" />
			-->
			<input type="text" id="dp1" name"datefrom" />
			<input type="text" id="dp2" name"dateto" />
			<input type="submit" name="go" value="Report"/>
		</form>		
			</div>
		<?php
		$message = '';
		$message .= '<table class="working">';
		$message .= '<tr>';
		
		$message .= '<th>Date</th>';
		$message .= '<th>Calls Taken</th>';
		$message .= '<th>Calls Logged</th>';
		$message .= '<th>Comments</th>';
		$message .= '<th>AHT</th>';
		$message .= '<th>Responses</th>';
		$message .= '<th>Notes</th>';
		$message .= '<th>Tickets Logged</th>';
		$message .= '<th>Raising Ticket</th>';
		$message .= '<th>Upsell Logging</th>';
		$message .= '</tr>';
		if (isset($_POST['go'])) {
			$datefrom = 0;
			$dateto = 0;

			if (isset($_POST['datefrom']) && isset($_POST['dateto'])) {

				$stats = select_stats_by_date($_POST['agent'], $_POST['datefrom'], $_POST['dateto']);
				$datefrom = $_POST['datefrom'];
				$dateto = $_POST['dateto'];

				if (empty($_POST['datefrom']) && empty($_POST['dateto'])) {
					$datefrom = date("Y-m-d", mktime(0, 0, 0, date('m'), 1, date('Y')));
					$dateto = date("Y-m-d", strtotime('yesterday'));
				}

			}

			if (isset($_POST))
				while ($row = mysql_fetch_array($stats)) {

					$message .= "<tr>";
					
					$message .= "<td>" . date("d-m-Y", strtotime($row['date'])) . "</td>";
					$message .= "<td>" . $row['calls'] . "</td>";
					$message .= "<td>" . $row['calls_logged'] . "</td>";
					$message .= "<td>" . $row['comments'] . "</td>";
					$message .= "<td>" . $row['aht'] . "</td>";
					$message .= "<td>" . $row['ticket_responses'] . "</td>";
					$message .= "<td>" . $row['notes'] . "</td>";
					$message .= "<td>" . $row['tickets_logged'] . "</td>";
					$message .= "<td>" . $row['rseticket'] . "</td>";
					$message .= "<td>" . $row['upsell'] . "</td>";
					$message .= "</tr>";

				}
			$query = "SELECT SUM(`calls`) AS calls, SUM(`calls_logged`) AS logged, SUM(`comments`) AS comments, SEC_TO_TIME( SUM( TIME_TO_SEC(  `aht` ) ) ) AS aht, SUM(`ticket_responses`) AS responses,
				SUM(`notes`) AS notes,SUM(`tickets_logged`) AS tlogged, SEC_TO_TIME( SUM( TIME_TO_SEC(  `rseticket` ) ) ) AS raising_time, SUM(`upsell`) AS upsell FROM `stats` WHERE `userid` = '" . $_POST['agent'] . "'
				AND `date` BETWEEN '" . $datefrom . "' AND '" . $dateto . "'";

			//$message .= $query;

			$result = mysql_query($query);
			$totals = mysql_fetch_array($result);

			$message .= "<tr class=\"totals\">";
			$message .= "<td><b>Totals</b></td>";
			
			$message .= "<td>" . $totals['calls'] . "</td>";
			$message .= "<td>" . $totals['logged'] . "</td>";
			$message .= "<td>" . $totals['comments'] . "</td>";
			$message .= "<td>" . "</td>";
			$message .= "<td>" . $totals['responses'] . "</td>";
			$message .= "<td>" . $totals['notes'] . "</td>";
			$message .= "<td>" . $totals['tlogged'] . "</td>";
			$message .= "<td>" . $totals['raising_time'] . "</td>";
			$message .= "<td></td>";

			$message .= "</tr>";

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
				BETWEEN '" . $datefrom . "' 
				AND '" . $dateto . "'";

			$result = mysql_query($query);
			$avgs = mysql_fetch_array($result);

			$message .= "<tr class=\"totals\">";
			$message .= "<td><b>Averages</b></td>";
	
			$message .= "<td>" . $avgs['calls'] . "</td>";
			$message .= "<td>" . $avgs['logged'] . "</td>";
			$message .= "<td>" . $avgs['comments'] . "</td>";
			$message .= "<td>" . $avgs['aht'] . "</td>";
			$message .= "<td>" . $avgs['responses'] . "</td>";
			$message .= "<td>" . $avgs['notes'] . "</td>";
			$message .= "<td>" . $avgs['tlogged'] . "</td>";
			$message .= "<td>" . $avgs['raising_time'] . "</td>";
			$message .= "<td>" . $avgs['upsell'] . "</td>";

			$message .= "</tr>";
			$message .= "</table>";
		}
		
		
		$message .= "<br />";
		
		$message .= "<table class=\"working\">";
		$message .= "<tr><th>Perfect 10's</th><th>QA Score</th></tr>";
		
		$query = "SELECT SUM(`p10`) AS p10, AVG(`qascore`) AS qa FROM `novatech` WHERE `userid`='".$_POST['agent'] . "' AND `date` BETWEEN '" . $_POST['datefrom'] . "' AND '" . $_POST['dateto'] . "'";
		//echo $query;
		$result = mysql_query($query);
		$data = mysql_fetch_array($result);
		$message .= '<tr><td>' . $data['p10'] . '</td><td>' . $data['qa'] . '</td> </tr> </table>';
			
		echo $message;
		
		//MAIL SEGMENT
		if($_POST['mailer']=="yes")
		{
			$query = "SELECT * FROM `users` WHERE `userid`='" . $_POST['agent'] . "'";
			$result = mysql_query($query);
			$agent = mysql_fetch_array($result);
			$agentname = $agent['name'];
			$sendto ="kurtis.brown@heartinternet.co.uk, michael.coombs@heartinternet.co.uk";
			$subject = "Call Stats: $agentname for " . $_POST['datefrom'] . " until " . $_POST['dateto'];
			
			$headers = "From: AutoBusinessMike@AutoBusinessMike.com\r\n";
			
			$email = '<html><head><link rel="stylesheet" href="http://kurt.is/css/tables.css" type="text/css"></head><body>';
			$email .= $message;
			$email .= '</body></html>';
		$headers .= "Reply-To: kurtis.brown@heartinternet.co.uk\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html;charset=utf-8 \r\n";
		
		mail($sendto, $subject, $email, $headers);
		}
			?>
			

		
		</div>
	</body>
</html>