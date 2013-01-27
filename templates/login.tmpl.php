<?php if (!defined('TMPL_DIR')) return; ?>
<div id="mainBody">
	<form id="inputArea" action="index.php?action=login" method="post">
		<p>
	        <label>Email</label>
	        <input type="text" name="email" value="<?php echo $HTML['email'];?>" />

	        <label>Date of Birth (mm-dd-yyyy)</label>
	        <input type="text" name="birth" id="datepicker" value="<?php echo $HTML['birth'];?>" />

	        <span><?php echo $HTML['login_error'];?></span>

			<input class="submit" type="submit" value="Login" />
			<input type="hidden" name="submitted" value="yes" />
		</p>
	</form>
</div>