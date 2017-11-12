<?php
// get the connection to the database
require_once('config.php');
class LogIn{
	// function which will check if the user is online or offline
	function UserOnline(){
		// check if there is any cookie in the browser
		if(isset($_COOKIE['session'])){
			$db = new Connection;
			// filter the cookie
			$user_sess = preg_replace('/[^a-zA-Z0-9]/i',' ', $_COOKIE['session']);
			// select the user with the cookie from it's browser
			$get_user = $db->prepare('SELECT id FROM users WHERE session = :user_session');
			$get_user -> execute(array('user_session' => $user_sess));
			// check if any user was found
			if($get_user -> rowCount() > 0)
				return TRUE;
			else
				return FALSE;
		}else
			return FALSE;
	}
}
?>
<html>
	<head>
		<title>Login</title>
		<link rel="stylesheet" href="/css/style.css" type="text/css" >
		<script type="text/javascript" src="/js/jquery-3.2.1.min.js"></script>
		<script type="text/javascript" src="/js/javascript.js"></script>
	</head>
	<body>
		<div id="container">
			<?php
			$Login = new LogIn;
				// call the function which will check
				// if any user was found with the cookie from it's browser
				if($Login -> UserOnline() == TRUE){
					echo "You are online!<br /><br />
					<a href='exit.php'>Exit!</a>";
				// if there is no such user we show the login form
				}else{
			?>
			<div class="response"></div>
			<form id="login" action="" method="POST">
				<input type="text" name="nickname" placeholder="Nickname" /><br />
				<input type="password" name="password" placeholder="Password" /><br />
				<input type="submit" value="Enter" />
			</form>
			<?php } ?>
		</div>
	</body>
</html>