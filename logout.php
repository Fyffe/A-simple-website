<?php
	session_start();
	$user_id = $_SESSION['user_id'];
	session_destroy();
	session_start();
	$_SESSION['global_msg'] = "Successfully logged out!";
	header('Location: ./');
?>