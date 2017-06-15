<?php
	require_once('functions.php');
	/* $user=session_check(); */
?>
<html>
<head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.26.3/css/uikit.min.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.26.3/js/uikit.min.js"> </script>
</head>
<body>
<?PHP
    show_menu($user);
	if ($user==NULL or $user['id']==NULL)
	{ 
		echo '<form method="post" action="register.php" class="uk-form" enctype="multipart/form-data">
		<fieldset data-uk-margin>
			<legend>Sign Up!</legend>
			<input name="username" type="text" placeholder="username">
			<input name="password" type="password" placeholder="password"><br/>
			<input name="name" type="text" placeholder="name">
			<input name="surname" type="text" placeholder="surname"><br/>
			<input name="address" type="text" placeholder="adress"><br/>
			<input name="nip" type="text" placeholder="nip"><br/>
			<input name="pesel" type="text" placeholder="pesel"><br/>
			<input type="file" name="avatar" accept="image/*"><br/>
			<input type="submit" name="submit" value="Register">
		</fieldset>
		</form><br/>';
	}
	else
	{
        echo "Witaj ".$user['username']."!";
	}
?>
</body>
</html>