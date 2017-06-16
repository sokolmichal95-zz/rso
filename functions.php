<?PHP
//pozmieniać później wszystkie wystąpienia predis\func na oryginalne
require "predis/autoload.php";
Predis\Autoloader::register();
function session_check()
{
	if(!isset($_COOKIE['MYSID'])) 
	{
		$token=md5(rand(0,1000000000));
		setcookie('MYSID', $token);
		$user=array('id'=>NULL,'username'=>"Visitor");
		redis_set_json($token, $user,0);
	}
	else
	{
		$token=$_COOKIE['MYSID'];
	}
	if (isset($_POST['username']) and isset($_POST['password']))
	{
		return authorize($_POST['username'],$_POST['password'],$token);
	}
	else
	{
		return authorize(NULL,NULL,$token);
	}
}
function register()
{
	$host = "localhost";
	$username = "user";
	$password = "qwerty";
	$dbname = "rso";
	$conn = new mysqli($host, $username, $password, $dbname);
	if(mysqli_connect_error())
	{
		die("Connection failed: " . mysqli_connect_error());
	}
	echo "Connected Succesfully<br/>";
	$sql = "SELECT * FROM users WHERE username = '".$_POST['username']."'";
	$rows_count = $conn->query($sql);
	if(mysqli_num_rows($rows_count) == 0){
		$target_dir = "uploads/";
		$target_file = $target_dir . basename($_FILES["avatar"]["name"]);
		$uploadOk = 1;
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		$ready_to_upload_file = $target_dir.$_POST['username'].".".$imageFileType;
		// Check if image file is a actual image or fake image
		if(isset($_POST["submit"])) {
			$check = getimagesize($_FILES["avatar"]["tmp_name"]);
			if($check !== false) {
				echo "File is an image - " . $check["mime"] . ".";
				$uploadOk = 1;
			} else {
				echo "File is not an image.";
				$uploadOk = 0;
			}
		}
		// Check if file already exists
		if (file_exists($ready_to_upload_file)) {
			echo "Sorry, file already exists.";
			$uploadOk = 0;
		}
		// Check file size
		if ($_FILES["avatar"]["size"] > 500000) {
			echo "Sorry, your file is too large.";
			$uploadOk = 0;
		}
		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
			echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
			$uploadOk = 0;
		}
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			echo "Sorry, your file was not uploaded.";
		// if everything is ok, try to upload file
		} else {
			if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $ready_to_upload_file)) {
				echo "The file ". basename( $_FILES["avatar"]["name"]). " has been uploaded.";
			} else {
				echo "Sorry, there was an error uploading your file.";
			}
		}
		$sql = "INSERT INTO users (username, password, firstname, lastname, address, nip, pesel, avatar) VALUES ('".$_POST['username']."','".$_POST['password']."','".$_POST['name']."','".$_POST['surname']."','".$_POST['address']."','".$_POST['nip']."','".$_POST['pesel']."','".$ready_to_upload_file."')";
		if($conn->query($sql) === TRUE)
		{
			echo "User created<br/>";
		}
		else
		{
			echo "Error: " . $sql . "<br>" . $conn->error . "<br>";
		}
		
		$conn->close();
		header("Location: index.php");
		die();
	} 
	else 
	{
		echo 'User: '.$_POST['username'].' already registered';
	}
}
function authorize($username,$password, $token)
{
        if ($username!=NULL and $password!=NULL)
        {
			// echo $username."<br>";
			// echo $password."<br>";
			$host = "localhost";
			$usern = "user";
			$pass = "qwerty";
			$dbname = "rso";
			$conn = new mysqli($host, $usern, $pass, $dbname);
			$sql = "SELECT * FROM users WHERE username = '".$username."' AND password = '".$password."'";
			// echo $sql."<br>";
			$rows_count = $conn->query($sql);
			$id_res = $conn->query("SELECT id FROM users WHERE username = '".$username."' AND password = '".$password."'");
			// var_dump($id_res);
			// echo mysqli_num_rows($rows_count);
            if (mysqli_num_rows($rows_count) == 1)
                $user=array('id'=>$id_res,'username'=>$username);
			else if(mysqli_num_rows($rows_count) > 1)
				echo 'Database error: TOO MUCH DATA';
            else
			{
				$user=array('id'=>NULL,'username'=>"Visitor");
			}
			redis_set_json($token,$user,"0");
            return $user;
        }
        else
            return redis_get_json($token);
}
function logout($user)
{
	$token=$_COOKIE['MYSID'];
	$user=array('id'=>NULL,'username'=>"Visitor");
	redis_set_json($token,$user,"0");
	return $user;
}
function redis_set_json($key, $val, $expire)
{
	// $redisClient = new Redis();
	$redisClient = new Predis\Client();
	// $redisClient->connect( '127.0.0.1', 6379 );
	$value=json_encode($val);
	if ($expire > 0)
			$redisClient->setex($key, $expire, $value );
	else
			$redisClient->set($key, $value);
	// $redisClient->close();
}
function redis_get_json($key)
{
	// $redisClient = new Redis();
	$redisClient = new Predis\Client();
	// $redisClient->connect( '127.0.0.1', 6379 );
	$ret=json_decode($redisClient->get($key),true);
	// $redisClient->close();
	return $ret;
}
function show_menu($user)
{
echo '
<nav class="uk-navbar">
    <ul class="uk-navbar-nav">';
	if ($user==NULL or $user['id']==NULL)
		echo '<li class="uk-active"><a href="login.php">Login</a></li>';
	else
		echo '<li class="uk-active"><a href="logout.php">Logout</a></li>';
	echo '<li class="uk-parent"><a href="index.php">Home</a></li>
    </ul>
</nav>';
}
function wall()
{
	echo '<form method="post" action="add_post.php">
			<fieldset data-uk-margin>
				<input type="text" maxlength="256"><br>
				<input type="submit" name="submit" value="Post">
			</fieldset>
		</form>';
		
	echo '<div name="postview">
			
		</div>'
}
function get_posts()
{
	
}
?>