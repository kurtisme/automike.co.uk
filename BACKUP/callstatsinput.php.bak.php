<html>
	<head>
		<title>AutoMike v0.3</title>
		<?php
		require_once "php/dbfuncs.php";
		require_once "php/stdhead.php";
		?>
	</head>
	<body>
		<h1>AutoBusinessMike - Call Stats Input</h1>
		<?php
if (!isset($_POST['go']))
{
		?>
		<p>
			<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
				<div class="leftcol">
					Date:
					<input type="date" name="date">
					<br />
					Data:
					<br />
					<textarea name="input" rows="20" cols="80"></textarea>
					<br />
				</div>
				<?php
				if (date('D') == "Mon") {
					$query = "SELECT * FROM `users`";
					$result = mysql_query($query);
					echo '<div class="rightcol">';
					echo "<table style=>";
					echo "<tr>";
					echo "<th>Name</th>";
					echo "<th>Perfect 10's</th>";
					echo "<th>QA Scores</th>";
					echo "</tr>";

					while ($row = mysql_fetch_array($result)) {
						echo "<tr>";
						echo "<td>" . $row['name'] . "</td>";
						echo '<td><input type="text" name="' . $row['userid'] . 'a" /></td>';
						echo '<td><input type="text" name="' . $row['userid'] . 'b" /></td>';
						echo "</tr>";
					}
					echo "</table>";
					echo "</div>";
				}
				?>

				<input type="submit" name="go" />
			</form>
		</p>
		<p>

		</p>
		<?php } ?>
		<?php
		if (isset($_POST['go'])) {

			$input = $_POST['input'];

			$agent = explode("\r\n", $input);

			foreach ($agent as $line) {

				$data = explode("\t", $line);
				if ($data[0] != '') {
					$query = "SELECT * FROM `users` WHERE `name`='" . $data[0] . "'";
					//echo $query;
					$result = mysql_query($query);

					if (mysql_num_rows($result) == '1') {

						$row = mysql_fetch_array($result);
						$id = $row['userid'];

					}
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
					if (isset($_POST['date'])) {
						$date = $_POST['date'];
					} else {

						if (date('D') == "Mon") {
							$date = date("Y-m-d", mktime(0, 0, 0, date('m'), date('d') - 3, date('Y')));
						} else {
							$date = date("Y-m-d", strtotime("yesterday"));
						}
					}

					$query = "INSERT INTO stats(`userid`,`date`,`calls`,`calls_logged`,`comments`,`aht`,`ticket_responses`,`notes`,`tickets_logged`,`rseticket`,`upsell`) VALUES('" . $id . "','" . $date . "','" . $calls . "','" . $calls_logged . "','" . $comments . "','" . $aht . "','" . $ticket_response . "','" . $notes . "','" . $tickets_logged . "','" . $rseticket . "','" . $upsell . "')";

					$result = mysql_query($query);



				}
			}
			if (date("D") == "Mon") {
				$weekending = date("Y-m-d", mktime(0, 0, 0, date('m'), date('d') - 3, date('Y')));

				$query = "SELECT * FROM `users`";
				$results = mysql_query($query);

				while ($row = mysql_fetch_array($results)) {
					$id = $row['userid'];
					$p1 = $id . 'a';
					$p2 = $id . 'b';
					$query = "INSERT INTO `novatech` (`userid`,`p10`,`qascore`, `date`) VALUES ('$id','" . $_POST[$p1] . "','" . $_POST[$p2] . "','" . $weekending . "')";

					$result1 = mysql_query($query);
					
					

					if ($result || $result1 ) {header("Location: ../callstatsinput.php");

					}
				}
			}
		}
		?>
	</body>
</html>