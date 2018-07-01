<?php
require_once("class/login.php");

$login = new login;

if (isset($_POST['registerCheck']) && isset($_POST['name'])) {
	$check = $login->exist($_POST['name']);
	if ($check) {
		echo "This account ID has been taken";
	} else {
		echo "OK";
	}
}

if (isset($_POST['nameCheck']) && isset($_POST['name'])) {
	$check = $login->exist($_POST['name']);
	if (!$check) {
		echo "User does not exist";
	} else {
		$q = $login->getUserQuestion($_POST['name']);
		echo "<option value=" . $q[0] . ">" . $q[1] . "</option>";
	}
}

if (isset($_POST['name']) && isset($_POST['answer']) && !isset($_POST['password'])) {
	$check = $login->getAnswer($_POST['name'], $_POST['answer']);
	if (!$check) {
		echo "Wrong answer";
	} else {
		echo "Correct";
	}
}

if (isset($_POST['login'])) {
	$check = $login->verify($_POST['name'], $_POST['password'], $_POST['answer']);
	if (!$check) {
		echo "Authentication failed";
	} else {
		session_start();
		$_SESSION['lid'] = $login->getLoginID($_POST['name']);
		echo "Access Granted";
	}
}

if (isset($_POST['register'])) {
	$check = $login->store($_POST['name'], $_POST['password'], $_POST['question'], $_POST['answer']);
	if (!$check) {
		echo "Registration failed";
	} else {
		session_start();
		$_SESSION['lid'] = $login->getLoginID($_POST['name']);
		echo "Registered";
	}
}

if (!isset($_POST['register']) && isset($_POST['question'])) {
	$questions = $login->getQuestions();
	for ($i = 1; $i <= $login->getNumOfQuestions(); $i++) {
		$q = array_shift($questions);
		echo "<option value=" . $i . ">" . $q . "</option>";
	}
}
?>
