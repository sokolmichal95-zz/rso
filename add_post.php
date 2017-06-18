<?php
	require_once 'functions.php';
	require_once __DIR__ . '/vendor/autoload.php';
	use PhpAmqpLib\Connection\AMQPStreamConnection;
	use PhpAmqpLib\Message\AMQPMessage;	

	// otwarcie polaczenia z serwerem RabbitMQ
	$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
	$channel = $connection->channel();

	$channel->queue_declare('postsQueue',false,false,false,false);
	$msg = new AMQPMessage($_POST['author']."\n".$_POST['text']);
	$channel->basic_publish($msg, '', 'postsQueue');

	$channel->close();
	$connection->close();

	echo " [x] Sent your post for admin approval\n";
	header("Location: index.php");
	die();
?>
