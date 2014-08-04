<?php
	$user = $data["user"];
	$errors = $data["errors"];
?>
<div>
	<h1>Register</h1>
	<form id="registerForm" method="POST" action="/register/?commit=true">
		<table align="center" width="950px" border="1" cellspacing="0" cellpadding="5">
			<tr>
			<td>First Name</td>
			<td><?=$htmlUtil->textbox(array("name"=>"firstName","value"=>$user["firstName"]))?></td>
			<td><?=$errors["@firstName_Error"]?></td>
			</tr>
			<tr>
			<td>Last Name</td>
			<td><?=$htmlUtil->textbox(array("name"=>"lastName","value"=>$user["lastName"]))?></td>
			<td><?=$errors["@lastName_Error"]?></td>
			</tr>
			<tr>
			<td>Email</td>
			<td><?=$htmlUtil->textbox(array("name"=>"email","value"=>$user["email"]))?></td>
			<td><?=$errors["@email_Error"]?></td>
			</tr>
			<tr>
			<td>Password</td>
			<td><?=$htmlUtil->password(array("name"=>"password"))?></td>
			<td><?=$errors["@password_Error"]?></td>
			</tr>
			<tr>
			<td>Password Confirm</td>
			<td><?=$htmlUtil->password(array("name"=>"passwordConfirm"))?></td>
			<td><?=$errors["@passwordConfirm_Error"]?></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input type="submit" value="Register" /></td>
				<td>&nbsp;</td>
			</tr>
		</table>
	</form>
</div>