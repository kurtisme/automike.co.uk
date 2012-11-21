<div class="span12">
	<form class="form-search" action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
		<label for="agent">Agent</label>
		<select name="agent">
			<option value="all"> -All Agents- </option>
			<?php
				$result = mysql_query("SELECT * FROM `users` WHERE `level`='NORM' ORDER BY `name` ASC");

				while ($row = mysql_fetch_array($result))
				{
					echo '<option ';
					if (isset($_POST['go']) && $_POST['agent'] == $row['userid'])
					{
						echo 'selected="selected" ';
					}
					echo 'value="' . $row['userid'] . '">' . $row['name'] . '</option>';
				}
			?>
		</select>
		<input type="text" id="dp1" name="datefrom"/>
		<input type="text" id="dp2" name="dateto"/>
		<input type="submit" name="go" class="btn btn-inverse" />
	</form>
</div>