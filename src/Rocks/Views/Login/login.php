<?php	$user = $data["user"];	$errors = $data["errors"];?><div>	<h1>Login</h1>	<form method="POST" action="/login/?commit=true">
		<?=$htmlUtil->textbox(array("name"=>"email","value"=>$user["email"]))?><br/>		<?=$errors["@email_Error"]?><br/>		
		<?=$htmlUtil->password(array("name"=>"password"))?><br/>
		<button type="sbmit">Login</button><br/><br/>				<a href="/login/forgot/">Forgot Password?</a>
	</form></div>