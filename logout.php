<?php
	require_once("functions.php");
	$user = session_check();
	logout($user);
	header("Location: index.php");
	die();
?>