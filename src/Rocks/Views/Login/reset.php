<?php
	$user = $data["user"];
	$errors = $data["errors"];
?>
<div>
	<h1>Reset Password</h1>	<div>		<?=$errors["@recovery_Error"]?>	</div>
	<form method="POST" action="/login/reset/?commit=true&userID=<?=$user["userID"]?>&recoveryKey=<?=$user["recoveryKey"]?>">
		<?=$htmlUtil->textbox(array("name"=>"email","value"=>$user["email"]))?><br/>
		<?=$errors["@email_Error"]?><br/>
		<?=$htmlUtil->textbox(array("name"=>"password","value"=>""))?><br/>
		<?=$errors["@password_Error"]?><br/>
		<?=$htmlUtil->textbox(array("name"=>"passwordConfirm","value"=>""))?><br/>
		<button type="sbmit">Reset</button><br/><br/>
	</form>
</div>