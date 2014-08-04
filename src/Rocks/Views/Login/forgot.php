<?php	$user = $data["user"];	$errors = $data["errors"];?><div>	<h1>Forgot Password</h1>	<form method="POST" action="/login/forgot/?commit=true">
		<?=$htmlUtil->textbox(array("name"=>"email","value"=>$user["email"]))?><br/>		<?=$errors["@email_Error"]?><br/>		
		<button type="sbmit">Login</button><br/><br/>
	</form></div>