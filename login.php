<script type="text/javascript" src="js/sha512.js"></script>
<script type="text/javascript" src="js/forms.js"></script>
<?php
	if (isset($_GET['error']))
	{
		echo 'Error Logging In!';
	}
?>
<form action="php/process_login.php" method="post" name="login_form">
	Username:
	<input type="text" name="email" />
	<br />..
	Password:..
	<input type="password" name="password" id="password"/>
	<br />
	<input type="button" value="Login" onclick="formhash(this.form, this.form.password);" />
</form>