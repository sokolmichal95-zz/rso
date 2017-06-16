<?php
        require_once('functions.php');
        $user=session_check();
?>
<html>
<head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.26.3/css/uikit.min.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.26.3/js/uikit.min.js"> </script>
</head>
<body>
<?php
        show_menu($user);
if ($user==NULL or $user['id']==NULL)
{
	echo '<form method="post" action="login.php" class="uk-form">
		<fieldset data-uk-margin>
			<legend>Log in</legend>
			<input name="username" type="text" placeholder="username">
			<input name="password" type="password" placeholder="password">
			<button class="uk-button">Go!</button>
		</fieldset>
	</form>';
}
else
    echo "Witaj ".$user['username']."!";
?>
</body>
</html>