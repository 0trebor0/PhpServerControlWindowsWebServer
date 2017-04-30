<?php
session_start();
date_default_timezone_set('Europe/London');
$config=['Account'=>[
'Username'=>'YourUsername',
'Password'=>'YourPassword']];
?>
<head>
	<title>Server Admin Page</title>
</head>
<body style='text-align:center;'>
	<?php
		if(isset($_SESSION['Account']) && !empty($_SESSION['Account'])){
			$accountJsonDec= json_decode($_SESSION['Account'],true);
			if($accountJsonDec['Username'] == $config['Account']['Username']){
				if($accountJsonDec['Password'] == $config['Account']['Password']){
					//Log In
					echo"<h1>Log in as ".$accountJsonDec['Username']."</h1>";
					echo"| <a href='http://".$_SERVER['SERVER_ADDR']."/server.php'>Home</a> | ";
					echo"<a href='http://".$_SERVER['SERVER_ADDR']."/server.php?shutdown'>Shutdown</a> | ";
					echo"<a href='http://".$_SERVER['SERVER_ADDR']."/server.php?CancelShutdown'>Cancel Shutdown</a> | ";
					echo"<a href='http://".$_SERVER['SERVER_ADDR']."/server.php?RestartComputer'>Restart Computer</a> | ";
					echo"<a href='http://".$_SERVER['SERVER_ADDR']."/server.php?Logout'>Log Out</a> | <br>";
					echo"Session Details Username:".$accountJsonDec['Username']." Log In Expire ".date("d/m/Y H:i:s a",strtotime($accountJsonDec['datetime']));
					if(strtotime($accountJsonDec['datetime']) < strtotime(date("Y-m-d H:i:s"))){
						session_unset($_SESSION['Account']);     // unset $_SESSION variable for the run-time 
						session_destroy();
						header("Refresh: 0; url=server.php");
					}
					if(isset($_GET['shutdown']) && empty($_GET['shutdown'])){
						exec("shutdown -s -f -t 30");
						echo"<h1 style='color:red;'>Computer Shutting Down</h1>";
					}else if(isset($_GET['CancelShutdown']) && empty($_GET['CancelShutdown'])){
						exec("shutdown /a");
						echo"<h1 style='color:red;'>Computer Shutting Down Cancel</h1>";
						header("Refresh: 5; url=server.php");
					}else if(isset($_GET['RestartComputer']) && empty($_GET['RestartComputer'])){
						exec("shutdown -r");
						echo"<h1 style='color:red;'>Restarting Computer</h1>";
					}else if(isset($_GET['Logout']) && empty($_GET['Logout'])){
						session_unset($_SESSION['Account']);     // unset $_SESSION variable for the run-time 
						session_destroy();
						header("Refresh: 0; url=server.php");
					}
				}else{
					//Error
					session_unset($_SESSION['Account']);     // unset $_SESSION variable for the run-time 
					session_destroy();
					header("Refresh: 0; url=server.php");
				}
			}else{
				//Error
				session_unset($_SESSION['Account']);     // unset $_SESSION variable for the run-time 
				session_destroy();
				header("Refresh: 0; url=server.php");
			}
		}else{
			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				if(!empty($_POST['AccUsername'])){
					if(!empty($_POST['AccPassword'])){
						$_SESSION['Account'] = json_encode(['Username'=>$_POST['AccUsername'],'Password'=>$_POST['AccPassword'],'datetime'=>date("Y-m-d H:i:s",time() + 50)]);
						header("Refresh: 0; url=server.php");
					}
				}
			}
			echo"
			<h1>Admin Login</h1>
			<form method='post'>
				<!--<input type='hidden' name='country' value='Norway'>-->
				<p>Username</p>
				<input type='text' name='AccUsername'><br>
				<p>Password</p>
				<input type='password' name='AccPassword'><br>
				<input type='submit'>
			</form>
			";
		}
	?>
<script>

</script>
</body>