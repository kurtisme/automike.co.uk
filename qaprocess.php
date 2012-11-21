<?php
	REQUIRE_ONCE "php/dbfuncs.php";

	//The $points array sets the points value of each question on the form.
	//It's references match the answers provided in the $result array.
	$points = array(
		"1" => array(
			"1" => 2,
			"2" => 1,
			"3" => 1,
			"4" => 1,
			"5" => 2,
			"6" => 1,
		),
		"2" => array(
			"1" => 1,
			"2" => 1,
			"3" => 1,
			"4" => 1,
			"5" => 1,
			"6" => 0,
		),
		"3" => array(
			"1" => 6,
			"2" => 6,
		),
		"4" => array(
			"1" => 8,
			"2" => 6,
			"3" => 6,
			"4" => 4,
			"5" => 4,
			"6" => 0,
		),
		"5" => array(
			"1" => 5,
			"2" => 5,
			"3" => 5,
			"4" => 2,
			"5" => 5,
			"6" => 5,
			"7" => 4,
			"8" => 4,
			"9" => 5,
		),
		"6" => array(
			"1" => 0,
			"2" => 0,
			"3" => 0,
			"4" => 0,
		),
	);
	//$result array contains the answers to the QA questions, which can then be compared to the points array
	//The references are identical to the $points array.
	$result = array(
		"1" => array(
			"1" => $_POST['11'],
			"2" => $_POST['12'],
			"3" => $_POST['13'],
			"4" => $_POST['14'],
			"5" => $_POST['15'],
			"6" => $_POST['16'],
		),
		"2" => array(
			"1" => $_POST['21'],
			"2" => $_POST['22'],
			"3" => $_POST['23'],
			"4" => $_POST['24'],
			"5" => $_POST['25'],
			"6" => $_POST['26'],
		),
		"3" => array(
			"1" => $_POST['31'],
			"2" => $_POST['32'],
		),
		"4" => array(
			"1" => $_POST['41'],
			"2" => $_POST['42'],
			"3" => $_POST['43'],
			"4" => $_POST['44'],
			"5" => $_POST['45'],
			"6" => $_POST['46'],
		),
		"5" => array(
			"1" => $_POST['51'],
			"2" => $_POST['52'],
			"3" => $_POST['53'],
			"4" => $_POST['54'],
			"5" => $_POST['55'],
			"6" => $_POST['56'],
			"7" => $_POST['57'],
			"8" => $_POST['58'],
			"9" => $_POST['59'],
		),
		"6" => array(
			"1" => $_POST['61'],
			"2" => $_POST['62'],
			"3" => $_POST['63'],
			"4" => $_POST['64'],
		),
	);

	//A seperate array to hold the string information from the top of the form.
	$details = array(
		"1" => array(
			"1" => $_POST['com1'],
			"2" => $_POST['com2'],
			"3" => $_POST['com3'],
			"4" => $_POST['com4'],
			"5" => $_POST['com5'],
			"6" => $_POST['fincom'],
		),
		"2" => array(
			"1" => $_POST['agent'],
			"2" => $_POST['assesor'],
			"3" => $_POST['calldate'],
			"4" => $_POST['customer'],
			"5" => $_POST['domain'],
		)
	);

	//calcPoints calculates the final score, by adding the points matrix and answer matrix together.
	$finalScore = calcPoints($result, $points);

	//isPass contains the algorithm for determining if the QA is passed.
	$isPass = isPass($result, $finalScore);

	//ABANDON HOPE ALL YE WHO TRY TO EDIT BEYOND THIS POINT!

	//Reset array key reference variables
	$i = 1;
	$j = 1;

	//Open the query table
	$query = "INSERT INTO `qaresult`(";

	//For each SECTION of the form (1.x, 2.x.. n.x)
	//Uses array_count to make the form expandable.
	while ($i <= count($result))
	{
		//For each question within that section (1.1, 1.2.. 1.n)
		//Uses array_count to make the sections expandable.
		while ($j <= count($result[$i]))
		{
			//Adds the database column reference to the query
			//eg. `a42` for the "Answer to Section 4, Question 2"
			$query .= "`a$i$j`,";
			$j++;
		}
		//Reset $j to start again in the inner array.
		$j = 1;
		$i++;
	}

	//Static columns
	$query .= "`agentid`,`assesorid`,`calldate`,`subdate`,`custname`,`domain`,";
	$query .= "`com1`,`com2`,`com3`,`com4`,`com5`,`fincom`,";
	$query .= "`finalscore`,`pass`)";
	$query .= " VALUES(";

	//Reset array key iterators.
	$i = 1;
	$j = 1;

	//For each SECTION of the form (1.x, 2.x.. n.x)
	//Uses array_count to make the form expandable.
	while ($i <= count($result))
	{
		//For each question within that section (1.1, 1.2.. 1.n)
		//Uses array_count to make the sections expandable.
		while ($j <= count($result[$i]))
		{
			//Adds the answer (held at $i (Section ref), $j (Question ref))
			$query .= "'" . $result[$i][$j] . "',";
			$j++;
		}

		//Reset $j to start again in the inner array.
		$j = 1;
		$i++;
	}

	//Static columns from the $details array
	$query .= "'" . $details['2']['1'] . "','" . $details['2']['2'] . "','" . $details['2']['3'] . "','" . date("Y-m-d") . "','" . $details['2']['4'] . "','" . $details['2']['5'] . "',";
	$query .= "'" . htmlspecialchars($details['1']['1']) . "','" . htmlspecialchars($details['1']['2']) . "','" . htmlspecialchars($details['1']['3']) . "','" . htmlspecialchars($details['1']['4']) . "','" . htmlspecialchars($details['1']['5']) . "','" . htmlspecialchars($details['1']['6']) . "','";

	//Finally add the score and pass value
	$query .= "$finalScore','$isPass')";
	//RUN THAT QUERY!
	$result = mysql_query($query);

	//Immediately get the AutoIncrement ID so that we can later redirect to the Result page automatically
	$lastID = mysql_insert_id();

	if ($result)
	{
		//If we successfully added that record to the database
		$agent = $details['2']['1'];

		//Calculate this coming Friday
		$current_day = date("N");
		$days_to_friday = 5 - $current_day;
		$friday = date("Y-m-d", strtotime("+ {$days_to_friday} Days"));

		//Add the logid as this weeks qualifying Novatech QA.
		//NOTE: Qualifying Novatech QA will always be the first one of the week.
		//If another QA is performed, it will still be logged in the QA results table,
		//but the novatech table will NOT be updated, as the PKey is based on Date/UserID
		//and as such a Date/ID pair will already exist and will SOFT fail.

		
		$resultlog = mysql_query("INSERT INTO `novatech`(`userid`,`qascore`,`date`) VALUES('$agent','$lastID','$friday')");

		//Redirect to Result page.
		header("Location: ../qadone.php?id=$lastID");

	}
	else
	{
		echo "query error: " . mysql_error();
	}

	function calcPoints($points, $schema)
	{
		$i = 1;
		$j = 1;

		//$totalpts represents the current accrued points.
		//$possible represents the potential points earned if all non-N/A criteria were met.
		//Important for calculating percentage later
		$totalpts = 0;
		$possible = 0;

		while ($i <= count($points))
		{
			//For each Section - expandable.

			while ($j <= count($points[$i]))
			{
				//For each question within that section
				if (!($points[$i][$j] == 'a'))
				{
					//If the question was NOT answered with N/A (ie. There was the potential to score points on this question)
					//Then add the points to the possible count.
					$possible = $possible + $schema[$i][$j];

					if ($points[$i][$j] == 'y')
					{
						//If the question was answered yes, then add the points to the running total.
						//If the question was answered no, no points are awarded but are still added to possible.

						$totalpts = $totalpts + ($schema[$i][$j]);

					}
				}

				$j++;
			}
			$j = 1;
			$i++;

		}

		//Final Score is then calculated as Point Earned divided by Potential Points, then multiplied by 100 to achieve a percentage.
		return ($totalpts / $possible) * 100;
	}

	function isPass($result, $score)
	{
		if ($score >= 70)
		{
			//If they scored less than 80, FAIL, otherwise...
			if ($result['2']['6'] == 'y' || $result['2']['6'] == 'a')
			{
				//IF DPA was required AND not passed, FAIL, otherwise...
				if ($result['4']['1'] == 'y' || $result['4']['1'] == 'a')
				{
					//If the agent fails to leave CRM notes, FAIL, otherwise...
					if ($result['4']['6'] == 'y' || $result['4']['6'] == 'a')
					{

						//If the agent fails to provide the correct solution, FAIL, otherwise...
						if ($result['6']['1'] == 'y')
						{
							//If there was no upsell opportunity, automatically PASS, otherwise...
							if ($result['6']['2'] == 'n')
							{
								//If an attempt to sell was made, PASS otherwise FAIL.
								return false;
							}
							else
							{
								//UPSELL MADE
								return true;
							}
						}
						else
						{
							//NO UPSELL OPP.
							return true;
						}
					}
					else
					{
						return false;
					}
				}
				else
				{
					//SOLUTION FAIL
					return false;
				}
			}
			else
			{
				//DPA FAIL
				return false;
			}
		}
		else
		{
			//SCORE < 80
			return false;
		}

	}
?>