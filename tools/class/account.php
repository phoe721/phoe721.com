<?php
require_once("database.php");
class account {
	private $db;
	private $errorNo;
	public function __construct() {
		$this->db = new database;
		$con = $this->db->connect("localhost", "root", "revive", "server");
		mysqli_set_charset($con, "utf8");
	}
	public function exist($aid) {
		$result = $this->db->query("SELECT aid FROM account WHERE aid='$aid'");
		if (mysqli_num_rows($result) == 0) {
			return false;
		} else {
			return true;
		}
	}
	public function store($lid, $acct, $uid, $pwd, $link, $comment) {
		$result = $this->db->query("INSERT INTO account VALUES ('', '$lid', '$acct', '$uid', '$pwd', '$link', '$comment')");
		if (!$result) {
			$this->errorNo = mysqli_errno($this->db->getConnection());
			return false;
		} else {
			return true;
		}
	}
	public function delete($aid) {
		if ($this->exist($aid)) {
			$this->db->query("DELETE FROM account WHERE aid='$aid'");
			return true;
		} else {
			return false;
		}
	}
	public function modify($aid, $acct, $uid, $pwd, $link, $comment) {
		if ($this->exist($aid)) {
			$result = $this->db->query("UPDATE account SET account = '$acct', uid = '$uid', pwd = '$pwd', link = '$link', comment = '$comment' WHERE aid = '$aid'");
			if (!$result) {
				$this->errorNo = mysqli_errno($this->db->getConnection());
				return false;
			} else {
				return true;
			}
		}
	}
	public function display($lid) {		
		$result = $this->db->query("SELECT * FROM account WHERE lid = '$lid'");
		if (!$result) {
			$this->errorNo = mysqli_errno($this->db->getConnection());
			return false;
		} else {
			return $result;
		}
	}
	public function getCount($lid) {
		$result = $this->db->query("SELECT * FROM account WHERE lid = '$lid'");
		if (!$result) {
			$this->errorNo = mysqli_errno($this->db->getConnection());
			return false;
		} else {
			$count = mysqli_num_rows($result);
			return $count;
		}
	}
	public function getErrorNo() {
		return $this->errorNo;
	}
}
?>