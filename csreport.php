<!DOCTYPE html>
<html>
	<head>
		<?php
			include_once ('navbar.html');
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

				if (isset($_POST['go']))
				{
					
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

					//If no dates were provided in the form...
					if (empty($_POST['datefrom']) && empty($_POST['dateto']))
					{
						//Beginning date is set to 1st of the current month
						$datefrom = date("Y-m-d", mktime(0, 0, 0, date('m'), 1, date('Y')));
						//End date is set to yesterday, as data should be available
						$dateto = date("Y-m-d", strtotime('yesterday'));
					}
					else
					{

						$datefrom = $_POST['datefrom'];
						$dateto = $_POST['dateto'];

					}

					//SELECTs stats between certain dates.
					$stats = select_stats_by_date($_POST['agent'], $datefrom, $dateto);

					$totalcalltimes = 0;
					$frequency = 0;

					if (isset($_POST))
						while ($row = mysql_fetch_array($stats))
						{

							//For each day returned from the database...
							//Output the relevant stats for this agent.
							$message .= "<tr>";

							$message .= "<td>" . date("d-m-Y", strtotime($row['date'])) . "</td>";
							if (($row['calls'] - $row['calls_logged']) > 3 && $_POST['agent'] != 'all')
							{
								$message .= "<td class=\"atarget\">" . $row['calls'] . "</td>";
								$message .= "<td class=\"atarget\">" . $row['calls_logged'] . "</td>";
							}
							else
							{
								$message .= "<td>" . $row['calls'] . "</td>";
								$message .= "<td>" . $row['calls_logged'] . "</td>";
							}
							$message .= "<td>" . $row['comments'] . "</td>";
							if (strtotime($row['aht']) >= strtotime("00:07:00") && $_POST['agent'] != 'all')
							{
								$message .= "<td class=\"atarget\">" . $row['aht'] . "</td>";
							}
							else
							{
								$message .= "<td>" . $row['aht'] . "</td>";
							}
							$message .= "<td>" . $row['ticket_responses'] . "</td>";
							$message .= "<td>" . $row['notes'] . "</td>";
							$message .= "<td>" . $row['tickets_logged'] . "</td>";
							if (strtotime($row['rseticket']) >= strtotime("00:45:00") && $_POST['agent'] != 'all')
							{
								$message .= "<td class=\"atarget\">" . $row['rseticket'] . "</td>";
							}
							else
							{
								$message .= "<td>" . $row['rseticket'] . "</td>";
							}
							if ($row['upsell'] <= 80 && $_POST['agent'] != 'all')
							{
								$message .= "<td class=\"atarget\">" . $row['upsell'] . "</td>";
							}
							else
							{
								$message .= "<td>" . $row['upsell'] . "</td>";
							}

							$message .= "</tr>";

							$totalcalltimes += $fixedtotaltime;
							$frequency = $frequency + ($row['calls'] * (strtotime($row['aht']) - strtotime('today')));
						}

					$result = getTotals($_POST['agent'], $datefrom, $dateto);
					$totals = mysql_fetch_array($result);

					//Output the data retrieved.
					$message .= "<tr class=\"totals\">";
					$message .= "<td><b>Totals</b></td>";

					$message .= "<td>" . $totals['calls'] . "</td>";
					$message .= "<td>" . $totals['logged'] . "</td>";
					$message .= "<td>" . $totals['comments'] . "</td>";
					$message .= "<td>" . "-" . "</td>";
					$message .= "<td>" . $totals['responses'] . "</td>";
					$message .= "<td>" . $totals['notes'] . "</td>";
					$message .= "<td>" . $totals['tlogged'] . "</td>";
					$message .= "<td>" . $totals['raising_time'] . "</td>";
					$message .= "<td>" . $totals['upsell'] . "</td>";

					$message .= "</tr>";

					$result = getAvgs($_POST['agent'], $datefrom, $dateto);
					$avgs = mysql_fetch_array($result);

					$rAHT = format_time($frequency / $totals['calls']);
					//Output
					$message .= "<tr class=\"totals\">";
					$message .= "<td><b>Averages</b></td>";

					$message .= "<td>" . $avgs['calls'] . "</td>";
					$message .= "<td>" . $avgs['logged'] . "</td>";
					$message .= "<td>" . $avgs['comments'] . "</td>";
					$message .= "<td>" . $rAHT . "</td>";
					$message .= "<td>" . $avgs['responses'] . "</td>";
					$message .= "<td>" . $avgs['notes'] . "</td>";
					$message .= "<td>" . $avgs['tlogged'] . "</td>";
					$message .= "<td>" . $avgs['raising_time'] . "</td>";
					$message .= "<td>" . $avgs['upsell'] . "</td>";

					$message .= "</tr>";
					$message .= "</table>";

					$message .= "<br />";

					//Echo it all out!

					$message .= '
			<table class="table table-striped table-hover">
				<tr>
					<th>Perfect 10\'s</th>
					<th>Average QA Score</th>

				</tr>
				<tr>
					<td> ';
					//Total Perfect 10's for one agent between two dates.
					$message .= getTotalP10s($_POST['agent'], $datefrom, $dateto);
					
					$message .= ' </td>
					<td>';
					

					$message .= getTotalQA($_POST['agent'], $datefrom, $dateto);
					$message .= '</td>
				</tr>
			</table>
			';
				echo $message;	

				}

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
					$email = '<html><head><link rel="stylesheet" href="http://automike.co.uk/css/bootstrap.min.css" type="text/css"></head><body><div class="container">';
					$email .= $message;
					$email .= '</div></body></html>';

					//Send that mail!
					mail($sendto, $subject, $email, $headers);
				}

				function format_time($t, $f = ':')// t = seconds, f = separator
				{
					return sprintf("%02d%s%02d%s%02d", floor($t / 3600), $f, ($t / 60) % 60, $f, $t % 60);
				}

			?>
		</div>
	</body>
</html>