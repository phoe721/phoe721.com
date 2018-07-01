<?php
require_once("debugger.php");

class sshConsole {
    private $session;
	private $status;
    private $stream;
    private $errorStream;
	private $debugger;
	private $output;
	private $data;
	private $error;

	public function __construct() {
		$this->output = new debugger;
	}

	public function connect($user, $password, $remoteHost, $port) {
		$this->session= ssh2_connect($remoteHost, $port);
        if (!$this->session) { 
            $this->output->error("Failed to create session");
            die("Connection failed"); 
        }
		$this->output->info("Session created successfully");
		
		$this->status = ssh2_auth_password($this->session, $user, $password);
        if (!$this->status) { 
            $this->output->error("Failed to authenticate the user");
            die("Connection failed"); 
        }
		$this->output->info("User authentication passed");
	}

    public function run($command) {
		if ($this->status) {
			$this->stream = ssh2_exec($this->session, $command);
			$this->errorStream = ssh2_fetch_stream($this->stream, SSH2_STREAM_STDERR);

			stream_set_blocking($this->errorStream, 1);
			stream_set_blocking($this->stream, 1);

			$this->data = trim(stream_get_contents($this->stream));
			$this->error = trim(stream_get_contents($this->errorStream));
			if ($this->data) {
				fclose($this->stream);
				return true;
			} else {
				fclose($this->errorStream);
				return false;
			}
		} else {
			$this->output->info("Session is not connected");
			return false;
		}
    }

    public function getOutput() {
		$this->output->info("Getting stream output");
        return $this->data;
    }

    public function getErrorOutput() {
		$this->output->info("Getting error output");
        return $this->error;
    }
}
?>
