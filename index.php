<!DOCTYPE html>

<html class="no-js">

	<head>
		<?php
		require_once "php/stdhead.php";
 ?>
	</head>
	<body>
		<?php
			include_once ('navbar.html');
		?>

		<div class="container">

			<!-- Main hero unit for a primary marketing message or call to action -->
			<div class="hero-unit">
				<h1>Auto Mike</h1>
				<p>
					Generate reports for Call Stats, QA, Novatech and 123qanda.co.uk
				</p>

			</div>

			<!-- Example row of columns -->
			<div class="row">
				<div class="span4">
					<h2>Call Stats</h2>
					<p>
						Input daily call stats and generate reports.
					</p>
					<p>
						<a class="btn" href="callstatsinput.php">Inpoot Daily Stats &raquo;</a>
					</p>
					<p>
						<a class="btn" href="csreport.php">Generate Report &raquo;</a>
					</p>
				</div>
				<div class="span4">
					<h2>QA</h2>
					<p>
						QA a call for an agent and add result to the database.
					</p>
					<p>
						<a class="btn" href="qainput.php">Fill in a QA Form &raquo;</a>
					</p>
					<p>
						<a class="btn" href="qarecall.php">View Previous Results &raquo;</a>
					</p>
					<p>
						<a class="btn" href="weeklyqa.php">Check Missing QA's &raquo;</a>
					</p>
					
				</div>
				<div class="span4">
					<h2>Novatech League</h2>
					<p>
						Retrieve and view previous Novatech results.
					</p>
					<p>
						<a class="btn" href="novatechcheck.php">View Novatech League &raquo;</a>
					</p>
				</div>
			</div>

			<hr>

			<footer>
				<!--   <p>&copy; 123notts</p> -->
			</footer>

		</div>
		<!-- /container -->

		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<script>
			window.jQuery || document.write('<script src="js/vendor/jquery-1.8.2.min.js"><\/script>')
		</script>

		<script src="js/vendor/bootstrap.min.js"></script>

		<script src="js/main.js"></script>

	</body>
</html>
