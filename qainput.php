<!DOCTYPE html>
<html>
	<head>
		<title>QA Form</title>
		<link rel="stylesheet" href="css/qa.css" type="text/css" />


		<?php
			require_once "php/qafuncs.php";
		?>
	</head>
	<body>
		<div class="main-wrapper">
			<form action="qaprocess.php" method="post">
				<div class="form-header">
					<div class="form-element">
						Assessor Name:
						<select name="assesor">
							<?php
								//Returns a list of all agents.
								$agents = getAllAgents();

								while ($row = mysql_fetch_array($agents))
								{
									//For each agent retrieved
									if ($row['level'] == 'ASS' && $row['dead'] == 0)
									{
										//If the agent is classified as an assessor and is still in 'active' status, add them to the list
										echo '<option value="' . $row['userid'] . '">' . $row['name'] . '</option>';
									}
								}
							?>
						</select>

					</div>

					<div class="form-element">

						Agent Name:
						<select name="agent">
							<?php
								//Returns a list of all agents.
								$agents = getAllAgents();

								while ($row = mysql_fetch_array($agents))
								{
									//For each agent retrieved
									if ($row['level'] == 'NORM' && $row['dead'] == 0)
									{
										//If the agent is classified as a Support Agent and is in 'active' status, add them to the list
										echo '<option value="' . $row['userid'] . '">' . $row['name'] . '</option>';
									}
								}
							?>
						</select>
					</div>
					<!-- Each point on the QA form's variable is reference as xy, where x is the section and y is the individual question.
					i.e. element 42 represents Section 4, Question 2.
					When adding to the database, an 'a' needs to be appended (eg. a42) otherwise array conflicts occur later
					-->
					<div class="form-element">
						Date of Call:
						<input type="date" id="dp1" name="calldate" />
						(YYYY-MM-DD)
					</div>

					<div class="form-element">
						Call ID:
						<input type="text" name="customer" />
					</div>
					<div class="form-element">
						Domain:
						<input type="text" name="domain" />
					</div>
				</div>
				<div class="qasectionwrap">
					<div class="qasectionhead">
						<h2>Greeting</h2>
					</div>

					<div class="qatable">
						<table class="qatable">
							<tr>
								<td class="qacriteria">1.1: Prepared for call</td>
								<td class="qaanswer">
								<input type="radio" name="11" value="y">
								Yes
								<input type="radio" name="11" value="n">
								No </td>
							</tr>
							<tr>
								<td class="qacriteria">1.2: Used 'time of day' Salutation</td>
								<td class="qaanswer">
								<input type="radio" name="12" value="y">
								Yes
								<input type="radio" name="12" value="n">
								No</td>
							</tr>
							<tr>
								<td class="qacriteria">1.3: Used branded scripting / department name</td>
								<td class="qaanswer">
								<input type="radio" name="13" value="y">
								Yes
								<input type="radio" name="13" value="n">
								No</td>
							</tr>
							<tr>
								<td class="qacriteria">1.4: Provided Name</td>
								<td class="qaanswer">
								<input type="radio" name="14" value="y">
								Yes
								<input type="radio" name="14" value="n">
								No</td>
							</tr>
							<tr>
								<td class="qacriteria">1.5: Offers Additional Assistance</td>
								<td class="qaanswer">
								<input type="radio" name="15" value="y">
								Yes
								<input type="radio" name="15" value="n">
								No
								<input type="radio" name="15" value="a">
								N/A </td>

							</tr>
							<tr>
								<td class="qacriteria">1.6: Used thanks with branded close / department name</td>
								<td class="qaanswer">
								<input type="radio" name="16" value="y">
								Yes
								<input type="radio" name="16" value="n">
								No
								<input type="radio" name="16" value="a">
								N/A </td>
							</tr>
						</table>
						<br />
						<span class="qacriteria">Comments:</span>
						<br />
						<textarea name="com1" cols="100" rows="6"></textarea>
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
								<td class="qaanswer">
								<input type="radio" name="21" value="y">
								Yes
								<input type="radio" name="21" value="n">
								No
								<input type="radio" name="21" value="a">
								N/A </td>
							</tr>
							<tr>
								<td class="qacriteria">2.2: Verified Address (incl. Postcode)</td>
								<td class="qaanswer">
								<input type="radio" name="22" value="y">
								Yes
								<input type="radio" name="22" value="n">
								No
								<input type="radio" name="22" value="a">
								N/A </td>
							</tr>
							<tr>
								<td class="qacriteria">2.3: Verified Telephone Number</td>
								<td class="qaanswer">
								<input type="radio" name="23" value="y">
								Yes
								<input type="radio" name="23" value="n">
								No
								<input type="radio" name="23" value="a">
								N/A </td>
							</tr>
							<tr>
								<td class="qacriteria">2.4: Verified Account Password</td>
								<td class="qaanswer">
								<input type="radio" name="24" value="y">
								Yes
								<input type="radio" name="24" value="n">
								No
								<input type="radio" name="24" value="a">
								N/A </td>
							</tr>
							<tr>
								<td class="qacriteria">2.5: Verified Last 4 Digits / Invoice Number</td>
								<td class="qaanswer">
								<input type="radio" name="25" value="y">
								Yes
								<input type="radio" name="25" value="n">
								No
								<input type="radio" name="25" value="a">
								N/A </td>
							</tr>
							<tr>
								<td class="qacriteria">2.6: DPA Complete</td>
								<td class="qaanswer">
								<input type="radio" name="26" value="y">
								Yes
								<input type="radio" name="26" value="n">
								No
								<input type="radio" name="26" value="a">
								N/A </td>
							</tr>
						</table>
						<br />
						<span class="qacriteria">Comments:</span>
						<br />
						<textarea name="com2" cols="100" rows="6"></textarea>
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
								<td class="qaanswer">
								<input type="radio" name="31" value="y">
								Yes
								<input type="radio" name="31" value="n">
								No
								<input type="radio" name="31" value="a">
								N/A </td>
							</tr>
							<tr>
								<td class="qacriteria">3.2: Uncovered root cause of call</td>
								<td class="qaanswer">
								<input type="radio" name="32" value="y">
								Yes
								<input type="radio" name="32" value="n">
								No
								<input type="radio" name="32" value="a">
								N/A </td>
							</tr>
						</table>
						<br />
						<span class="qacriteria">Comments:</span>
						<br />
						<textarea name="com3" cols="100" rows="6"></textarea>
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
								<td class="qaanswer">
								<input type="radio" name="41" value="y">
								Yes
								<input type="radio" name="41" value="n">
								No
								<input type="radio" name="41" value="a">
								N/A </td>
							</tr>
							<tr>
								<td class="qacriteria">4.2: Provides all relevant information</td>
								<td class="qaanswer">
								<input type="radio" name="42" value="y">
								Yes
								<input type="radio" name="42" value="n">
								No
								<input type="radio" name="42" value="a">
								N/A </td>
							</tr>
							<tr>
								<td class="qacriteria">4.3: Completes all tasks relevant to the call</td>
								<td class="qaanswer">
								<input type="radio" name="43" value="y">
								Yes
								<input type="radio" name="43" value="n">
								No
								<input type="radio" name="43" value="a">
								N/A </td>
							</tr>
							<tr>
								<td class="qacriteria">4.4: Confirms completion of actions taken to the customer, and confirms any further action necessary from the customer.</td>
								<td class="qaanswer">
								<input type="radio" name="44" value="y">
								Yes
								<input type="radio" name="44" value="n">
								No
								<input type="radio" name="44" value="a">
								N/A </td>
							</tr>
							<tr>
								<td class="qacriteria">4.5: Took ownership of call</td>
								<td class="qaanswer">
								<input type="radio" name="45" value="y">
								Yes
								<input type="radio" name="45" value="n">
								No
								<input type="radio" name="45" value="a">
								N/A </td>
							</tr>
							<tr>
								<td class="qacriteria">4.6: Call Logged Correctly</td>
								<td class="qaanswer">
								<input type="radio" name="46" value="y">
								Yes
								<input type="radio" name="46" value="n">
								No </td>
							</tr>

						</table>
						<br />
						<span class="qacriteria">Comments:</span>
						<br />
						<textarea name="com4" cols="100" rows="6"></textarea>
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
								<td class="qaanswer">
								<input type="radio" name="51" value="y">
								Yes
								<input type="radio" name="51" value="n">
								No </td>
							</tr>
							<tr>
								<td class="qacriteria">5.2: Professionalism and Courtesy</td>
								<td class="qaanswer">
								<input type="radio" name="52" value="y">
								Yes
								<input type="radio" name="52" value="n">
								No </td>
							</tr>
							<tr>
								<td class="qacriteria">5.3: Builds rapport with customer</td>
								<td class="qaanswer">
								<input type="radio" name="53" value="y">
								Yes
								<input type="radio" name="53" value="n">
								No </td>
							</tr>
							<tr>
								<td class="qacriteria">5.4: Customer Addressing</td>
								<td class="qaanswer">
								<input type="radio" name="54" value="y">
								Yes
								<input type="radio" name="54" value="n">
								No </td>
							</tr>
							<tr>
								<td class="qacriteria">5.5: Positivity</td>
								<td class="qaanswer">
								<input type="radio" name="55" value="y">
								Yes
								<input type="radio" name="55" value="n">
								No </td>
							</tr>
							<tr>
								<td class="qacriteria">5.6: Used Listening Skills</td>
								<td class="qaanswer">
								<input type="radio" name="56" value="y">
								Yes
								<input type="radio" name="56" value="n">
								No </td>
							</tr>
							<tr>
								<td class="qacriteria">5.7: Correct Transfer and Hold Procedure</td>
								<td class="qaanswer">
								<input type="radio" name="57" value="y">
								Yes
								<input type="radio" name="57" value="n">
								No
								<input type="radio" name="57" value="a">
								N/A </td>
							</tr>
							<tr>
								<td class="qacriteria">5.8: Checked Understanding of Customer / Summarised</td>
								<td class="qaanswer">
								<input type="radio" name="58" value="y">
								Yes
								<input type="radio" name="58" value="n">
								No
								<input type="radio" name="58" value="a">
								N/A </td>
							</tr>
							<tr>
								<td class="qacriteria">5.9: Referred Customer to Support Site</td>
								<td class="qaanswer">
								<input type="radio" name="59" value="y">
								Yes
								<input type="radio" name="59" value="n">
								No
								<input type="radio" name="59" value="a">
								N/A </td>
							</tr>

						</table>
						<br />
						<span class="qacriteria">Comments:</span>
						<br />
						<textarea name="com5" cols="100" rows="6"></textarea>
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
								<td class="qaanswer">
								<input type="radio" name="61" value="y">
								Yes
								<input type="radio" name="61" value="n">
								No
							</tr>
							<tr>
								<td class="qacriteria">6.2: Attempt to Sell</td>
								<td class="qaanswer">
								<input type="radio" name="62" value="y">
								Yes
								<input type="radio" name="62" value="n">
								No </td>
							</tr>
							<tr>
								<td class="qacriteria">6.3: Promotion of Product</td>
								<td class="qaanswer">
								<input type="radio" name="63" value="y">
								Yes
								<input type="radio" name="63" value="n">
								No </td>
							</tr>
							<tr>
								<td class="qacriteria">6.4: Made the Sale</td>
								<td class="qaanswer">
								<input type="radio" name="64" value="y">
								Yes
								<input type="radio" name="64" value="n">
								No </td>
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
						<textarea name="fincom" cols="100" rows="6"></textarea>																																																









						<center>
							<br />
							<input type="submit" name="go" value="Submit" />
							<input type="button" name="reset" value="Reset Form" />
						</center>
					</div>
				</div>
			</form>
		</div>

	</body>
</html>