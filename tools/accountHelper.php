<?php
require_once("class/account.php");
session_start();
$acct = new account;

if (isset($_POST['insert'])) {
	$status = $acct->store($_SESSION['lid'],$_POST['account'],$_POST['uid'],$_POST['pwd'],$_POST['link'],$_POST['comment']);
	if ($status) {
		echo "New account added";
	} else {
		echo "No";
	}
}

if (isset($_POST['display'])) {
	$result = $acct->display($_SESSION['lid']);
	$index = 1;
	while ($row = mysqli_fetch_array($result)) {
		echo "<tr>";
		echo "<form class='accountForm' method='POST' action='accountHelper.php'>";
		echo "<td><input type='text' name='account' value='" . $row['account'] . "'></td>";
		echo "<td><input type='text' name='uid' value='" . $row['uid'] . "'></td>";
		echo "<td><input type='text' name='pwd' value='" . $row['pwd'] . "'></td>";
		echo "<td class='link'><input type='text' name='link' value='" . $row['link'] . "'></td>"; 
		echo "<td><input type='text' name='comment' value='" . $row['comment'] . "'></td>";
		echo "<td>";
		echo "<input type='submit' name='update' value='Update' class='update' />";
		echo "<a href='accountHelper.php?delete=" . $row['aid'] . "'><button>Delete</button></a>";
		echo "</td>";
		echo "</form>";
		echo "</tr>";
		$index++;
	}
}

if (isset($_GET['delete'])) {
	$result = $acct->delete($_GET['delete']);
	if (!$result) {
		echo "Failed to delete account.";
	} else {
		header("location: account.php");
	}
}

if (isset($_GET['update'])) {
	$result = $acct->modify($_GET['update'],$_POST['account'],$_POST['uid'],$_POST['pwd'],$_POST['link'],$_POST['comment']);
	if (!$result) {
		echo "Failed to update account.";
	} else {
		header("location: account.php");
	}
}

if (isset($_POST['auth'])) {
	if (isset($_SESSION['lid'])) {
		echo "Authenticated";
	} else {
		echo "Not authenticated";
	}
}

if (isset($_GET['count'])) {
	$count = $acct->getCount($_SESSION['lid']);
	echo $count;
}
?>