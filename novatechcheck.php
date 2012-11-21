<!DOCTYPE html>
<?php
	require_once "php/dbfuncs.php";
	require_once "php/nvfuncs.php";
?>
<html>
	<head>

		<?php
			include_once "php/stdhead.php";
		?>
	</head>

	<body>
		<?php
			include_once ('navbar.html');
		?>
		<br>

		<div class="container">
			<div class="span12">
				<h2> Novatech Results </h2>
			</div>
			<div class="span12">

				<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
					<input id="dp1" type="text" name="datefrom" />
					<input id="dp2" type="text" name="dateto" />
					<input type="submit" name="go" />
				</form>
			</div>
			<table> <td>
			<?php
				if (isset($_POST['go']))
				{
					//If form has been submitted

					//Select all agents that are both active and not pod leaders
					$query = "SELECT * FROM `users` WHERE `dead`='0' AND `level`='NORM'";
					$result = mysql_query($query);

					//Initialise the mega-array
					$alldata = array();

					//Counter to create array elements
					$i = 0;
					while ($agent = mysql_fetch_array($result))
					{
						//For each agent returned, calculate their Novatech points. See php/nvfuncs.php for info
						$alldata[$i] = getAgentNovatech($agent['userid'], $_POST['datefrom'], $_POST['dateto']);
						$i++;
					}

					//$totalPoints is the array that stores the winning order
					//calcTotals returns the final point score for each agent.
					$totalPoints = calcTotals($alldata);

					//arsort - Maintains key/value pairs and sorts from highest to lowest.
					//Key/value pairs important as key is agentID
					arsort($totalPoints);

					echo '<div class="span4"><table class="table table-condensed">
							<tr>
							<th>Name</th>
							<th>Novatech Points</th>
							</tr>';

					while ($agent = current($totalPoints))
					{//Strange syntax required to be able to use key() to retrieve agentID.
						//Select agent name using ID from array key.
						$query = "SELECT `name` FROM `users` WHERE `userid`='" . key($totalPoints) . "'";
						$result = mysql_fetch_array(mysql_query($query));
						
						//Output their row of the league.
						echo '<tr><td>' . $result['name'] . '</td><td>' . $agent . ' pts.</td> <td></tr>';
						
						//Select next agent from array.
						next($totalPoints);
					}
					
					//Resets key pointer on array so that we can re-iterate over it
					reset( $totalPoints );
					echo '</table></div>';
				




			?>
			</td><td>
			<div class="span7">
				<!-- TODO: Add breakdown table -->
				<table class="table table-condensed">
					<tr>
						<th>Call Logging</th>
						<th>Whois Logging</th>
						<th>RaiseTicket</th>
						<th>Responses</th>
						<th>AHT</th>
						<th>QA</th>
						<th>Perfect 10</th>

					</tr>
					<?php
						//var_dump($alldata);
						while ($agent = current($totalPoints))
						{
							//Strange syntax required to be able to use key() to retrieve agentID.
							//Select agent name using ID from array key.
							$agentid = key($totalPoints);
					
							foreach ($alldata as $agent)
							{
								if ($agent['agentid'] == $agentid)
								{	
									echo '<tr>';
									//echo '<td>' . $agent['name'] . '</td>';
									echo '<td>' . $agent['call'] . '</td>';
									echo '<td>' . $agent['upsell'] . '</td>';
									echo '<td>' . $agent['rsetick'] . '</td>';
									echo '<td>' . $agent['tresponse'] . '</td>';
									echo '<td>' . $agent['aht'] . '</td>';
									echo '<td>';
									if(is_null($agent['qa'])){echo "-"; } else { echo $agent['qa']; }
									echo '</td>';
									echo '<td>';
									if(is_null($agent['p10'])){echo "0"; } else { echo $agent['p10']; }
									echo '</td>';
									echo '</tr>';
								}
							}

							next($totalPoints);
						}
					?>
				</table>
			</div>
			<?php } ?>
		</div>
		</td></table>
	</body>
</html>