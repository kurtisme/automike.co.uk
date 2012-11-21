<!DOCTYPE html>
<html>
	<head>
		<?php
			require_once "php/qafuncs.php";
		?>
		<title>QA Result</title>
		<link rel="stylesheet" href="css/qa.css" type="text/css" />
		<link rel="stylesheet" href="css/div.css" type="text/css" />

	</head>
	<body>
		<?php
			if (isset($_GET['id']))
			{//If a specific ID is picked up in the URL because an entry has been completed or it has been called up.
			//SQL Query selects relevant columns and renames them as necessary
			//Joins both the assessorID and agentID on the users table (assessor is aliased as `u2`)
			//Search is then narrowed to the logID
				$query = "SELECT `qaresult`.*, `users`.`name` AS `agentName`, `u2`.`name` AS `assesorName`
							FROM `qaresult`
							JOIN `users`
								ON `qaresult`.`agentid`=`users`.`userid`
							JOIN `users` AS `u2`
								ON `qaresult`.`assesorid`=`u2`.`userid`
							WHERE `logid`='" . $_GET['id'] . "'";

				$result = mysql_query($query);
				$i = mysql_fetch_array($result);

			}
		?>
		<!-- For each section of the QA, the output from the database is selected. getIt(x) returns 
			the text equivalent of 'y' 'n' or 'a' as stored in the database.
			getResult computes the passing or failing of a QA as laid down by the required Yes
		-->
		<div class="main-wrapper">
			<div class="leftcol">
				<div class="leftcol">
					<ul class="mylist">
						<li>
							Assessor Name:
						</li>
						<li>
							Agent Name
						</li>
						<li>
							Date of Call
						</li>
						<li>
							Customer's Name:
						</li>
						<li>
							Domain:
						</li>

					</ul>
				</div>
				<div class="rightcol">
					<ul class="mylist">
						<li>
							<?= $i['assesorName']; ?>
						</li>
						<li>
							<?= $i['agentName']; ?>
						</li>
						<li>
							<?= $i['calldate']; ?>
						</li>
						<li>
							<?= $i['custname']; ?>
						</li>
						<li>
							<?= $i['domain']; ?>
						</li>

					</ul>
				</div>

				<div class="clear"></div>
			</div>
			<div class="rightcol">
				<ul class="mylist">
					<li>
						SCORE: <?= $i['finalscore']; ?> %
					</li>
					<li>
						PASS: <?= getResult($i['pass']); ?>
					</li>

				</ul>
			</div>
			<div class="clear"></div>
			<div class="qasectionwrap">
				<div class="qasectionhead">
					<h2>Greeting</h2>
				</div>

				<div class="qatable">
					<table class="qatable">
						<tr>
							<td class="qacriteria">1.1: Prepared for call</td>
							<td class="qaanswer"><?= getIt($i['a11']); ?></td>
						</tr>
						<tr>
							<td class="qacriteria">1.2: Used 'time of day' Salutation</td>
							<td class="qaanswer"><?= getIt($i['a12']); ?></td>
						</tr>
						<tr>
							<td class="qacriteria">1.3: Used branded scripting / department name</td>
							<td class="qaanswer"><?= getIt($i['a13']); ?></td>
						</tr>
						<tr>
							<td class="qacriteria">1.4: Provided Name</td>
							<td class="qaanswer"><?= getIt($i['a14']); ?></td>
						</tr>
						<tr>
							<td class="qacriteria">1.5: Offers Additional Assistance</td>
							<td class="qaanswer"><?= getIt($i['a15']); ?>
						</tr>
						<tr>
							<td class="qacriteria">1.6: Used thanks with branded close / department name</td>
							<td class="qaanswer"><?= getIt($i['a16']); ?></td>
						</tr>
					</table>
					<br />
					<span class="qacriteria">Comments:</span>
					<br />
					<?= $i['com1']; ?>
				</div>
			</div>
			<div class="qasectionwrap">
				<div class="qasectionhead">
					<h2>Verification</h2>
				</div>

				<div class="qatable">
					<table class="qatable">
						<tr>
							<td class="qacriteria">2.1: Verified Name</td>
							<td class="qaanswer"> <?= getIt($i['a21']); ?></td>
						</tr>
						<tr>
							<td class="qacriteria">2.2: Verified Address (incl. Postcode)</td>
							<td class="qaanswer"> <?= getIt($i['a22']); ?></td>
						</tr>
						<tr>
							<td class="qacriteria">2.3: Verified Telephone Number</td>
							<td class="qaanswer"> <?= getIt($i['a23']); ?></td>
						</tr>
						<tr>
							<td class="qacriteria">2.4: Verified Account Password</td>
							<td class="qaanswer"> <?= getIt($i['a24']); ?></td>
						</tr>
						<tr>
							<td class="qacriteria">2.5: Verified Last 4 Digits / Invoice Number</td>
							<td class="qaanswer"> <?= getIt($i['a25']); ?></td>
						</tr>
						<tr>
							<td class="qacriteria">2.6: DPA Complete</td>
							<td class="qaanswer"> <?= getIt($i['a26']); ?></td>
						</tr>
					</table>
					<br />
					<span class="qacriteria">Comments:</span>
					<br />
					<?= $i['com2']; ?>
				</div>
			</div>
			<div class="qasectionwrap">
				<div class="qasectionhead">
					<h2>Establishing Needs</h2>
				</div>

				<div class="qatable">
					<table class="qatable">
						<tr>
							<td class="qacriteria">3.1: Asking open-ended questions</td>
							<td class="qaanswer"> <?= getIt($i['a31']); ?></td>
						</tr>
						<tr>
							<td class="qacriteria">3.2: Uncovered root cause of call</td>
							<td class="qaanswer"> <?= getIt($i['a32']); ?> </td>
						</tr>
					</table>
					<br />
					<span class="qacriteria">Comments:</span>
					<br />
					<?= $i['com3']; ?>
				</div>
			</div>
			<div class="qasectionwrap">
				<div class="qasectionhead">
					<h2>Solution / Explanation</h2>
				</div>

				<div class="qatable">
					<table class="qatable">
						<tr>
							<td class="qacriteria">4.1: Offered Correct Solution</td>
							<td class="qaanswer"> <?= getIt($i['a41']); ?> </td>
						</tr>
						<tr>
							<td class="qacriteria">4.2: Provides all relevant information</td>
							<td class="qaanswer"> <?= getIt($i['a42']); ?></td>
						</tr>
						<tr>
							<td class="qacriteria">4.3: Completes all tasks relevant to the call</td>
							<td class="qaanswer"> <?= getIt($i['a43']); ?></td>
						</tr>
						<tr>
							<td class="qacriteria">4.4: Confirms completion of actions taken to the customer, and confirms any further action necessary from the customer.</td>
							<td class="qaanswer"> <?= getIt($i['a44']); ?></td>
						</tr>
						<tr>
							<td class="qacriteria">4.5: Took ownership of call</td>
							<td class="qaanswer"> <?= getIt($i['a45']); ?></td>
						</tr>
						<tr>
							<td class="qacriteria">4.6: Call Logged Correctly</td>
							<td class="qaanswer"> <?= getIt($i['a46']); ?></td>
						</tr>

					</table>
					<br />
					<span class="qacriteria">Comments:</span>
					<br />
					<?= $i['com4']; ?>
				</div>
			</div>

			<div class="qasectionwrap">
				<div class="qasectionhead">
					<h2>Customer Satisfaction</h2>
				</div>

				<div class="qatable">
					<table class="qatable">
						<tr>
							<td class="qacriteria">5.1: Managing and structuring the call</td>
							<td class="qaanswer"> <?= getIt($i['a51']); ?> </td>
						</tr>
						<tr>
							<td class="qacriteria">5.2: Professionalism and Courtesy</td>
							<td class="qaanswer"> <?= getIt($i['a52']); ?></td>
						</tr>
						<tr>
							<td class="qacriteria">5.3: Builds rapport with customer</td>
							<td class="qaanswer"> <?= getIt($i['a53']); ?></td>
						</tr>
						<tr>
							<td class="qacriteria">5.4: Customer Addressing</td>
							<td class="qaanswer"> <?= getIt($i['a54']); ?></td>
						</tr>
						<tr>
							<td class="qacriteria">5.5: Positivity</td>
							<td class="qaanswer"> <?= getIt($i['a55']); ?></td>
						</tr>
						<tr>
							<td class="qacriteria">5.6: Used Listening Skills</td>
							<td class="qaanswer"> <?= getIt($i['a56']); ?></td>
						</tr>
						<tr>
							<td class="qacriteria">5.7: Correct Transfer and Hold Procedure</td>
							<td class="qaanswer"> <?= getIt($i['a57']); ?></td>
						</tr>
						<tr>
							<td class="qacriteria">5.8: Checked Understanding of Customer / Summarised</td>
							<td class="qaanswer"> <?= getIt($i['a58']); ?></td>
						</tr>
						<tr>
							<td class="qacriteria">5.9: Referred Customer to Support Site</td>
							<td class="qaanswer"> <?= getIt($i['a59']); ?></td>
						</tr>

					</table>
					<br />
					<span class="qacriteria">Comments:</span>
					<br />
					<?= $i['com5'] ?>
				</div>
			</div>
			<div class="qasectionwrap">
				<div class="qasectionhead" style="background: #E00847;">
					<h2>Upsell</h2>
				</div>

				<div class="qatable">
					<table class="qatable">
						<tr>
							<td class="qacriteria">6.1: Opportunity to Upsell</td>
							<td class="qaanswer"> <?= getIt($i['a61']); ?>
						</tr>
						<tr>
							<td class="qacriteria">6.2: Attempt to Sell</td>
							<td class="qaanswer"> <?= getIt($i['a62']); ?></td>
						</tr>
						<tr>
							<td class="qacriteria">6.3: Promotion of Product</td>
							<td class="qaanswer"> <?= getIt($i['a63']); ?></td>
						</tr>
						<tr>
							<td class="qacriteria">6.4: Made the Sale</td>
							<td class="qaanswer"> <?= getIt($i['a64']); ?></td>
						</tr>

					</table>
					<br />

				</div>
			</div>
			<div class="qasectionwrap">
				<div class="qasectionhead">
					<h2>Additional Comments</h2>
				</div>
				<div class="qatable">
					<?= $i['fincom']; ?>

				</div>
			</div>
			</form>
		</div>

	</body>
</html>

