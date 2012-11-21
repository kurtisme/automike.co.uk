<!DOCTYPE html>
<html>
	<head>
		<?php
			include_once ('navbar.html');
		?>

		<?php
			include_once "php/dbfuncs.php";
			include_once "php/stdhead.php";
		?>
	</head>
	<body>
		<div class="container">
		<h2>Call Stats Input</h2>
		<?php
			if (!isset($_POST['go']))
			{//If the form hasn't been submitted yet, show the input form.
			?>

			<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
				<div class="span7">
					<label for ="dp1">Date:</label>
					<input type="text" id="dp1" name="date">
					<br />
					<textarea name="input" class="input-xxlarge" rows="20" cols="150"></textarea>
					<br />
					<input type="submit" class="btn btn-inverse btn-large" name="go" value="Submit Data"/>
				</div>
				<div class="span3">
				<?php
					if (date('D') == "Mon")
					{
						//If today is Monday prepare the Perfect 10 table for input

						//Get a list of all current agents to display.
						$query = "SELECT * FROM `users` ORDER BY `name` ASC";
						$result = mysql_query($query);

						echo '<table class="table">';
						echo "<tr>";
						echo "<th>Name</th>";
						echo "<th>Perfect 10's</th>";
						echo "</tr>";

						while ($row = mysql_fetch_array($result))
						{
							//Output a row for each agent detected...
							if ($row['dead'] == 0 && $row['level'] == "NORM")
							{
								//...that is both currently active and a standard agent (ie. not a pod leader)
								echo "<tr>";
								echo "<td>" . $row['name'] . "</td>";
								echo '<td><input class="input-mini" type="text" name="' . $row['userid'] . 'a" /></td>';
								echo "</tr>";
							}
						}
						echo "</table>";
						echo "</div>";

					}
					}
				?>
		</div>

		</form>
		<?php

			if (isset($_POST['go']))
			{

				$input = $_POST['input'];

				//Make an array of agents by splitting by new lines. Each key in the array represents one agent's stats.
				$agent = explode("\r\n", $input);

				foreach ($agent as $line)
				{
					//For each agent in that array, split again by tab character to obtain individual stats.
					$data = explode("\t", $line);

					if ($data[0] != '')
					{

						//Check to make sure that it wasn't a blank line, ie. that [0] (the agent's name) is NOT blank.

						$query = "SELECT * FROM `users` WHERE `name`='" . $data[0] . "'";
						$result = mysql_query($query);

						//Verifies that the agent exists and retrieves their agent ID.
						//NOTE: The name in the database must EXACTLY match the input data, or it will not parse!!
						if (mysql_num_rows($result) == '1')
						{

							$row = mysql_fetch_array($result);
							$id = $row['userid'];

						}

						//Assign the relevant parts of the array to easier to call variables.
						$calls = $data[1];
						$calls_logged = $data[2];
						$comments = $data[3];
						$aht = $data[4];
						$ticket_response = $data[5];
						$notes = $data[6];
						$tickets_logged = $data[7];
						$rseticket = $data[8];
						$upsell = $data[9];

						echo "<br />";

						//If a data is set, use that. Otherwise....
						if (!empty($_POST['date']))
						{
							$date = $_POST['date'];
						}
						else
						{
							//If today is Monday, set the stats date to Friday. Otherwise....
							if (date('D') == "Mon")
							{
								$date = date("Y-m-d", mktime(0, 0, 0, date('m'), date('d') - 3, date('Y')));
							}
							else
							{
								//Set the date to yesterday.
								$date = date("Y-m-d", strtotime("yesterday"));
							}
						}

						//Structure the INSERT query.
						$query = "INSERT INTO stats(`userid`,`date`,`calls`,`calls_logged`,`comments`,`aht`,`ticket_responses`,`notes`,`tickets_logged`,`rseticket`,`upsell`) VALUES('" . $id . "','" . $date . "','" . $calls . "','" . $calls_logged . "','" . $comments . "','" . $aht . "','" . $ticket_response . "','" . $notes . "','" . $tickets_logged . "','" . $rseticket . "','" . $upsell . "')";
						print_r($query);
						$result = mysql_query($query) or die("Error: " . mysql_error());

						//Using mysql_query on INSERT produces Boolean result. If true, redirect back to top page, otherwise print error.
						if ($result)
						{
							if (date("D") == "Mon")
							{
								//Get a list of all current agents to display.
								$query = "SELECT * FROM `users` ORDER BY `name` ASC";
								$result = mysql_query($query);
								$date = date("Y-m-d", mktime(0, 0, 0, date('m'), date('d') - 3, date('Y')));
								while ($row = mysql_fetch_array($result))
								{
									//Output a row for each agent detected...
									if ($row['dead'] == 0 && $row['level'] == "NORM")
									{
										//...that is both currently active and a standard agent (ie. not a pod leader)
										
										$query = "INSERT INTO `p10s`(`userid`,`date`,`p10s`) VALUES('".$row['userid']."', '$date','". $row['userid'] . "a')";
										$result = mysql_query($query);
									}
								}

							}
							header("Location: ../index.php");
						}
						else
						{
							echo "Error: " . mysql_error();
						}

					}
				}
			}

		?>
		</div>
	</body>
</html>