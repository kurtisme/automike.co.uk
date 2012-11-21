<title>AutoMike</title>
<!-- Add Bootstrap CSS classes -->
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/main.css">

<!-- Add basic spacing for entire page -->
<style>
	body {
		padding-top: 20px;
		padding-bottom: 10px;
	}
	
</style>
<!-- jQuery -->
<script src="js/vendor/jquery-1.8.2.min.js"></script>

<!-- Custom datepicker includes -->
<link href="css/datepicker.css" rel="stylesheet">
<script src="js/bootstrap-datepicker.js"></script>


<!-- All pages only have 2 datepickers. -->
<script>
	$(function() {
		$('#dp1').datepicker({
			format : 'yyyy-mm-dd'
		});
		$('#dp2').datepicker({
			format : 'yyyy-mm-dd'
		});

	}); 
</script>