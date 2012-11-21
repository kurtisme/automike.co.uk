<!DOCTYPE html>
<html class="no-js">
	<head>
		<?php
			REQUIRE_ONCE "php/dbfuncs.php";
			REQUIRE_ONCE "php/stdhead.php";
		?>

	</head>
	<body>
		<?php include_once "navbar.html"; ?>
		<div class="container">
			
			<?php 
			echo '<br />';
			include_once "php/agentdatesearch.php";
			
			if(isset($_POST['go']))
			{
				//If the form has been submitted, select name to header the form.
				$result=mysql_query("SELECT `name` FROM `users`  WHERE `userid`='" . $_POST['agent'] . "'");
				
				$row = mysql_fetch_array($result);
				
			?>
			<div class="span12">
				<h2><?php
				echo $row['name'] . " :: ";
				if (empty($_POST['datefrom']) && empty($_POST['dateto']))
				{
					echo "All Historical QA's";
				}
				else
				{
					echo $_POST['datefrom'] . " - " . $_POST['dateto'];
				}
				?></h2>
				<table class="table table-hover">
					<tr>
					<th>Date</th>
					<th>Score</th>
					<th>Pass/Fail</th>
					<th>Options</th>
					</tr>
					
				<?php

					if (empty($_POST['datefrom']) && empty($_POST['dateto']))
					{
						//SELECT all historical QA's for the agent
						$query = "SELECT * 
						FROM `qaresult` 
						JOIN `users` 
							ON `qaresult`.`agentid`=`users`.`userid`
						WHERE `agentid`='" . $_POST['agent'] . "'
						ORDER BY `qaresult`.`calldate` DESC";
					}
					else
					{
						//If dates have been set
						//SELECT all QA's to have been done for that agent between those dates
						$query = "SELECT * 
						FROM `qaresult` 
						JOIN `users` 
							ON `qaresult`.`agentid`=`users`.`userid` 
						WHERE (`qaresult`.`calldate` 
							BETWEEN '" . $_POST['datefrom'] . "' AND '" . $_POST['dateto'] . "' ) 
						AND `agentid`='" . $_POST['agent'] . "'
						ORDER BY `qaresult`.`calldate` DESC";
						
					}
					
					//Run the query generated above.
					$result = mysql_query($query);

					while ($row = mysql_fetch_array($result))
					{
						//For each QA retrieved
						if ($row['pass'] == 1)
						{
							//If its a pass, make a green row
							echo '<tr class="success">';
						}
						else
						{
							//If its a fail, make a red row
							echo '<tr class="error">';
						}
						//Output the QA date, Pass/Fail and the score of the QA.
						echo "<td>" . $row['calldate'] . "</td>";
						echo "<td>" . $row['finalscore'] . "</td>";
						if ($row['pass'] == 1)
						{
							echo '<td>Pass</td>';
						}
						else
						{
							echo '<td>Fail</td>';
						}
						//Provide a link to the QA
						echo '<td><a href="qadone.php?id=' . $row['logid'] . '">View</a></td>';
						echo "</tr>";
					}
				?>
				</table>
			</div>
			<?php } ?>
		</div>
	</body>
</html>