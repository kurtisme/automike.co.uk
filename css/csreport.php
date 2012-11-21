<!DOCTYPE html>
<html>
	<head>
	<?php
	include_once('navbar.html');
		
	?>
	<br>
		<?php
			require_once "php/dbfuncs.php";
			require_once "php/stdhead.php";
		?>
	</head>
	<body>
		<div class="container">
			<h2>Call Stats Report</h2>
			<?php
				include_once "php/agentdatesearch.php";
				//Everything that would normally be echo'd is now being added to a $message variable so that it is easier to mail later.
				//$message is echo'd at the end of the script.
				$message = '<table class="table table-striped table-hover">
				<tr>
					<th>Name</th>
					<th>Calls Taken</th>
					<th>Calls Logged</th>
					<th>Comments</th>
					<th>AHT</th>
					<th>Ticket Reponses</th>
					<th>Notes</th>
					<th>Tickets Logged</th>
					<th>Ticket Time</th>
					<th>Upsell %</th>
				</tr>';

				if (isset($_POST['go']))
				{
					//If form has been submitted
					$datefrom = 0;
					$dateto = 0;

					//If the dates have been set, probably unnecessary as submit is checked and empty is done below, but just in case.
					//TODO: Optimise this section.
					if (isset($_POST['datefrom']) && isset($_POST['dateto']))
					{
						//SELECTs stats between certain dates.
						$stats = select_stats_by_date($_POST['agent'], $_POST['datefrom'], $_POST['dateto']);
						$datefrom = $_POST['datefrom'];
						$dateto = $_POST['dateto'];

						//If no dates were provided in the form...
						if (empty($_POST['datefrom']) && empty($_POST['dateto']))
						{
							//Beginning date is set to 1st of the current month
							$datefrom = date("Y-m-d", mktime(0, 0, 0, date('m'), 1, date('Y')));
							//End date is set to yesterday, as data should be available
							$dateto = date("Y-m-d", strtotime('yesterday'));
						}

					}

					if (isset($_POST))
						while ($row = mysql_fetch_array($stats))
						{
							//For each day returned from the database...
							//Output the relevant stats for this agent.
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
					//Query to total all relevant colums. For time values, convert to seconds -> sum -> convert to time
					$query = "SELECT 
					SUM(`calls`) AS calls, 
					SUM(`calls_logged`) AS logged, 
					SUM(`comments`) AS comments, 
					SEC_TO_TIME( SUM( TIME_TO_SEC(  `aht` ) ) ) AS aht, 
					SUM(`ticket_responses`) AS responses,
					SUM(`notes`) AS notes,SUM(`tickets_logged`) AS tlogged, 
					SEC_TO_TIME( SUM( TIME_TO_SEC(  `rseticket` ) ) ) AS raising_time, 
					SUM(`upsell`) AS upsell 
					FROM `stats` 
					WHERE `userid` = '" . $_POST['agent'] . "'	
					AND `date` 
					BETWEEN '" . $datefrom . "' AND '" . $dateto . "'";
					
					//Run the beast.
					$result = mysql_query($query);
					$totals = mysql_fetch_array($result);
					
					//Output the data retrieved.
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
					
					//Query to average all relevant colums. For time values, convert to seconds -> sum -> convert to time
					//Each one has been CAST as DECIMAL(5,2) meaning that no more than 5 digits will be displayed (ie. 100.00)
					//and no more than 2 decimal places.
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
					
					//Run the beast.
					$result = mysql_query($query);
					$avgs = mysql_fetch_array($result);
					
					//Output 
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
				
				//Echo it all out!
				echo $message;
			?>
			<table class="table table-striped table-hover">
			<tr>
			<th>Perfect 10's</th>
			<th>Average QA Score</th>

			</tr>
			<tr>
			<td>
			<?php
			//Total Perfect 10's for one agent between two dates.
				$result = mysql_query("SELECT SUM(`p10s`) AS totalp10 FROM `p10s` WHERE `userid`='" . $_POST['agent'] . "' AND (`date` BETWEEN '" . $_POST['datefrom'] . "' AND '" . $_POST['dateto'] . "')");
				$total = mysql_fetch_array($result);
				
				echo $total['totalp10'];
			?>
			</td>
			<td>
			<?php
			//Select all QA forms for one agent between two dates and return the average of scores. Again, cast as DECIMAL(5,2)
			//to include 0.00 <= x <= 100.00
				$result = mysql_query("SELECT CAST(AVG(`finalscore`) AS DECIMAL(5, 2)) AS scoreavg FROM `qaresult` WHERE `agentid`='" . $_POST['agent'] . "' AND (`calldate` BETWEEN '" . $_POST['datefrom'] . "' AND '" . $_POST['dateto'] . "')");

				$total = mysql_fetch_array($result);

				echo $total['scoreavg'];
			?>
			</td>
			</tr>
			</table>
			<?php
				//MAIL SEGMENT
				//If mailer option is flagged, not able to set this flag from the form, can only be forced through cUrl
				if ($_POST['mailer'] == "yes")
				{
					//SELECT agent's name
					$query = "SELECT * FROM `users` WHERE `userid`='" . $_POST['agent'] . "'";
					$result = mysql_query($query);
					$agent = mysql_fetch_array($result);
					$agentname = $agent['name'];
					
					//Select destination
					$sendto = "kurtis.brown@heartinternet.co.uk, michael.coombs@heartinternet.co.uk";
					
					//Set subject
					$subject = "Call Stats: $agentname for " . $_POST['datefrom'] . " until " . $_POST['dateto'];

					//Set standard headers
					$headers = "From: Robomike@automike.co.uk\r\n";
					$headers .= "Reply-To: kurtis.brown@heartinternet.co.uk\r\n";
					$headers .= "MIME-Version: 1.0\r\n";
					$headers .= "Content-Type: text/html;charset=utf-8 \r\n";
					
					//Output body of the email with external style sheet.
					$email = '<html><head><link rel="stylesheet" href="http://automike.co.uk/css/bootstrap.min.css" type="text/css"></head><body>';
					$email .= $message;
					$email .= '</body></html>';

					//Send that mail!
					mail($sendto, $subject, $email, $headers);
				}
			?>
		</div>
	</body>
</html>