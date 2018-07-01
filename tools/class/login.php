<?php
require_once("database.php");
require_once("debugger.php");

class login {
	private $db;
	private $errorNo;
	private $debug = 0;
	private $debugger;
	public function __construct() {
		$this->db = new database;
		$con = $this->db->connect("localhost", "root", "revive", "server");
		mysqli_set_charset($con, "utf8");
		$this->debugger = new debugger;
	}
	public function store($uid, $pwd, $securityQ1, $securityA1) {
		$hashedPwd = md5($pwd);
		$result = $this->db->query("INSERT INTO login VALUES ('', '$uid', '$hashedPwd', '$securityQ1', '$securityA1')");
		$this->debugger->output("Store function: executed successfully", 1, $this->debug);
		return true;
	}
	public function getQuestions() {
		$qList = array();
		$result = $this->db->query("SELECT q FROM security_questions");
		if (mysqli_num_rows($result) == 0) {
			$this->debugger->output("GetQuestions function: emtpy list", 2, $this->debug);
			return false;
		} else {
			while ($row = mysqli_fetch_array($result)) { array_push($qList, $row['q']); }
			$this->debugger->output("GetQuestions function: executed successfully", 1, $this->debug);
			return $qList;
		}
	}
	public function getUserQuestion($uid) {
		$result = $this->db->query("SELECT S.sid, S.q FROM login L, security_questions S WHERE L.uid = '$uid' AND L.s1 = S.sid LIMIT 1");
		if (mysqli_num_rows($result) == 0) {
			$this->debugger->output("GetUserQuestion function: user not found", 2, $this->debug);
			return false;
		} else {				
			$row = mysqli_fetch_array($result);
			$this->debugger->output("GetUserQuestion function: sid: " . $row['sid'] . ", question: " . $row['q'], 1, $this->debug);
			return array($row['sid'], $row['q']);
		}
	}
	public function getAnswer($uid, $answer) {
		$result = $this->db->query("SELECT uid, a1 FROM login WHERE uid='$uid' AND a1='$answer' LIMIT 1");
		if (mysqli_num_rows($result) == 0) {
			$this->debugger->output("GetAnswer function: wrong answer", 2, $this->debug);
			return false;
		} else {
			$row = mysqli_fetch_array($result);
			$this->debugger->output("GetAnswer function: correct answer", 1, $this->debug);
			return true;
		}
	}
	public function verify($uid, $pwd, $a1) {
		$hashedPwd = md5($pwd);
		$result = $this->db->query("SELECT uid, pwd, a1 FROM login WHERE uid='$uid' AND pwd='$hashedPwd' AND a1='$a1' LIMIT 1");
		if (mysqli_num_rows($result) == 0) {
			$this->debugger->output("Verify function: verify failed", 2, $this->debug);
			return false;
		} else {
			$row = mysqli_fetch_array($result);
			$this->debugger->output("Verify function: $uid verified", 1, $this->debug);
			return true;
		}
	}
	public function delete($uid) {
		if ($this->exist($uid)) {
			$this->db->query("DELETE FROM login WHERE uid='$uid'");
			$this->debugger->output("Delete function: $uid deleted", 1, $this->debug);
			return true;
		} else {
			$this->debugger->output("Delete function: no such user", 2, $this->debug);
			return false;
		}
	}
	public function exist($uid) {
		$result = $this->db->query("SELECT uid FROM login WHERE uid='$uid'");
		if (mysqli_num_rows($result) == 0) {
			$this->debugger->output("Exist function: no such user", 2, $this->debug);
			return false;
		} else {
			$this->debugger->output("Exist function: user found", 1, $this->debug);
			return true;
		}
	}
	public function getLoginID($uid) {
		$result = $this->db->query("SELECT lid FROM login WHERE uid='$uid' LIMIT 1");
		if (mysqli_num_rows($result) == 0) {
			$this->debugger->output("GetLoginID function: no such user", 2, $this->debug);
			return false;
		} else {
			$row = mysqli_fetch_array($result);
			$this->debugger->output("GetLoginID function: lid = " . $row['lid'], 1, $this->debug);
			return $row['lid'];
		}
	}
	public function getNumOfQuestions() {
		$result = $this->db->query("SELECT q FROM security_questions");
		$count = mysqli_num_rows($result);
		if ($count == 0) {
			$this->debugger->output("GetNumOfQuestions function: found no questions", 2, $this->debug);
			return $count;
		} else {
			$this->debugger->output("GetNumOfQuestions function: count = " . $count, 1, $this->debug);
			return $count;
		}
	}
} 

// Debug
/* $login = new login;
$login->store("aaron","revive",1,"Sweet Potato");
print_r($login->getQuestions());
$login->getUserQuestion("aaron");
$login->getAnswer("aaron","Sweet Potato");
$login->verify("aaron","revive","Sweet Potato");
$login->getLoginID("aaron");
$login->getNumOfQuestions();
$login->delete("aaron"); */
?>
