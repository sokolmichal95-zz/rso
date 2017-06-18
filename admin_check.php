<?php
	require_once 'functions.php';
	require_once __DIR__ . '/vendor/autoload.php';
	use PhpAmqpLib\Connection\AMQPStreamConnection;
	
	$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
	$channel = $connection->channel();
	
	$channel->queue_declare('postsQueue', false, false, false, false);

	echo '[*] Waiting for messages. To exit press C-c', "\n";

	$callback = function($msg) 
	{
		echo " [x] Received ", $msg->body, "\n";
	
		if (PHP_OS == 'WINNT') {
  			echo " Do you accept that post? ";
 		 	$choice = stream_get_line(STDIN, 1024, PHP_EOL);
		} else {
  			$choice = readline(' Do you accept that post? ');
		}
		if($choice=="yes")
		{
			$msgArr = explode("\n", $msg->body);
			$host = "localhost";
			$username = "user";
			$password = "qwerty";
			$dbname = "rso";
			$conn = new mysqli($host, $username, $password, $dbname);
			$sql = "INSERT INTO posts (author, text) VALUES ('".$msgArr[0]."','".$msgArr[1]."')";
			if($conn->query($sql) === TRUE)
			{
				echo "Post added";
			}
			else
			{
				echo "Problem adding the post";
			}
			$conn->close();
		}
	};

	$channel->basic_consume('postsQueue', '', false, true, false, false, $callback);
	
	while(count($channel->callbacks))
	{
		$channel->wait();
	}

	$channel->close();
	$connection->close();
?>
