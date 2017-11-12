<?php
require_once('config.php');
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
$db = new Connection;
$user = $db -> prepare("UPDATE users SET session = :new_session WHERE session = :current_sess");
$user -> execute(array(
	'new_session' => NULL,
	'current_sess' => filter($_COOKIE['session'])
));
setcookie('session', '', time() - 60*60*24*30, '/');
header('Location: /index.php');
die();
?>