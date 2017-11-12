<?php
require_once('../config.php');
class User{
	// generate a session
	function Session($length=6){
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
		$code = ""; 
		$clen = strlen($chars) - 1; 
		while (strlen($code) < $length){ 
			$code .= $chars[mt_rand(0,$clen)];
		}
		return $code;
	}
	// filter the information from Login form.
	function filter($str){
		//convert case to lower
		$str = strtolower($str);
		//remove special characters
		$str = preg_replace('/[^a-zA-Z0-9]/i',' ', $str);
		//remove white space characters from both side
		$str = trim($str);
		return $str;
	}
	function LogIn(){
		// check if the user entered any information
		if (isset($_POST['nickname'])) { $nickname = $_POST['nickname']; if ($nickname == '') { unset($nickname);} }
		if (isset($_POST['password'])) { $password = $_POST['password']; if ($password == '') { unset($password);} }
		if(empty($nickname) || empty($password)){
			$con = 'All fields are required!';
		}else{
			// connect to the database
			$db = new Connection;
			// let's get the user session
			$session = $this -> Session(10);
			// filter the information
			///$session = $this -> filter($session);
			$nickname = $this -> filter($nickname);
			$password = $this -> filter($password);
			// select the user with nickname and password entered
			$user = $db -> prepare("SELECT id FROM users WHERE nickname = :name AND password = :pass");
			$user -> execute(array(
				'name' => $nickname,
				'pass' => $password
			));
			// if the user were found
			if($user -> rowCount() > 0){
				$con = 'Welcome, '.$nickname;
				// update user session
				$update_user_sess = $db -> prepare("UPDATE users SET session = :user_session WHERE nickname = :user_nickname");
				$update_user_sess -> execute(array(
					'user_session' => $session,
					'user_nickname' => $nickname
				));
				// set up the cookie
				setcookie("session", $session, time()+60*60*24*30, "/", NULL);
				// redirect to the main page using JavaScript
				echo("<script>setTimeout(\"location.href = '/index.php';\",1500);</script>");
			}else{
				// if the user weren't found
				$con = 'The nickname or Password is incorrect!';
			}
		}
		return $con;
	}
}
// assign the User object to $User variable
$User = new User;
// Output the LogIn() function from User Object
echo $User -> LogIn();
?>