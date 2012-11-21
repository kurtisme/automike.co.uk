<!DOCTYPE html>
<html class="no-js">
	<head>
		<?php
		REQUIRE_ONCE "php/dbfuncs.php";
		REQUIRE_ONCE "php/stdhead.php";
		?>

	</head>
	<body>
		<?php
		include_once "navbar.html";
		?>
		<div class="container">

			<?php
			echo '
			<br />
			';
			#include_once "php/agentdatesearch.php";

			$current_day = date("N");
			$days_to_friday = 5 - $current_day;
			$friday = date("Y-m-d", strtotime("+ {$days_to_friday} Days"));

			$hasQA = array();
			$result = mysql_query("SELECT * FROM `novatech`
			JOIN `users` ON `novatech`.`userid` = `users`.`userid`
			WHERE `date`='" . $friday . "'  ORDER BY `novatech`.`userid` ASC") or die("error:" . mysql_error());
			while ($row = mysql_fetch_array($result)) {
				$hasQA[] = $row['userid'];
				$finalscore = $row['finalscore'];
			}
			print_r($finalscore);
			$userlist = mysql_query("SELECT * FROM `users` ORDER BY `pod`");

			echo '<table class="table">';

			while ($row = mysql_fetch_array($userlist)) {
				if ($row['level'] == 'NORM' && $row['dead'] == 0) {
					echo '<tr><td>' . $row['name'] . '</td>';
					if (array_search($row['userid'], $hasQA) !== FALSE) {

						echo '<td>  <img src="img/check.png" /> </td>';
						echo '<td>' . $qascore['finalscore'] . '</td>';
					} else {

						echo '<td> <a href="qainput.php"> <img src="img/busy.png" /> </a> </td>';
					}
					echo '</tr>';
				}
			}

			echo '
			</table>';
			?>
