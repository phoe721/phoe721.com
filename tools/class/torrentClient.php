<?php
require_once("sshConsole.php");

class torrentClient {
    private $console;
    private $user = "aaron";
    private $password = "revive";
    private $remoteHost = "localhost";
    private $port = 22;
	private $torrentDir = "/home/aaron/bt/watch/start/";
	private $uploadDir = "/var/www/html/phoe721.com/tools/upload/";
	private $debugger;

    public function __construct() {
        $this->console = new sshConsole;
        $this->console->connect($this->user, $this->password, $this->remoteHost, $this->port);
		$this->output = new debugger;
    }

	public function setUploadDir($path) {
		$this->uploadDir = $path;
		$this->output->info("Upload directory is set to $path");
	}

	public function getUploadDir() {
		$this->output->info("Upload directory: " . $this->uploadDir);
		return $this->uploadDir;
	}

    public function check() {
        $status = exec("ps -ef | grep [r]torrent | wc -l");
        if ($status) { 
			$this->output->info("Rtorrent is launched");
			return true; 
		} else {
			$this->output->info("Rtorrent is not launched");
			return false;
		}
    }

    public function start() {
        if ($this->check()) {
			$this->output->info("It's already launched");
            return false;
        } else {
            $status = $this->console->run("touch ~/.scgi_local && screen -S bt_download -d -m rtorrent");
			$this->output->info("Rtorrent started");
            return true;
        }
    }

    public function stop() {
        if ($this->check()) {
            $status = $this->console->run("screen -S bt_download -X quit && rm -f ~/.scgi_local");
			$this->output->info("Rtorrent stopped");
            return true;
        } else {
			$this->output->info("Rtorrent is not started");
            return false;
        }
    }

    public function listScreen() {
        $status = $this->console->run("screen -ls");
		if (!$status) {
			$this->output->error($this->console->getErrorOutput());
			die($this->console->getErrorOutput());
		}
        return $this->console->getOutput();
    }

	public function countTorrentFile() {
		$count = exec("ls $this->uploadDir | wc -l");
		$this->output->info("Total count of torrent file(s): $count");
		return $count;
	}

	public function validateTorrentFile($path) {
		$status = $this->console->run("lstor -q $path | grep -w HASH");
		if (!$status) { 
			$this->output->info("$path is not a valid torrent");
			return false;
		} else {
			$this->output->info("$path is a valid torrent");
			return true;
		}
	}

	public function removeTorrentFile($path) {
		$this->console->run("rm -f $path");
		if (file_exists($path)) {
			$this->output->info("$path is not removed");
			return false;
		} else {
			$this->output->info("$path is removed");
			return true;
		}
	}

	public function startTorrent($torrentHash) {
		$status = $this->console->run("rtcontrol -q --start hash=$torrentHash");
		if (!$status) { 
			$this->output->error($this->console->getErrorOutput());
			die($this->console->getErrorOutput());
		}
		return $this->console->getOutput();
	}

	public function stopTorrent($torrentHash) {
		$status = $this->console->run("rtcontrol -q --stop hash=$torrentHash");
		if (!$status) { 
			$this->output->error($this->console->getErrorOutput());
			die($this->console->getErrorOutput());
		}
		return $this->console->getOutput();
	}

	public function deleteTorrent($torrentHash) {
		$status = $this->console->run("rtcontrol -q --delete --yes hash=$torrentHash");
		if (!$status) { 
			$this->output->error($this->console->getErrorOutput());
			die($this->console->getErrorOutput());
		}
		return $this->console->getOutput();
	}

	public function purgeTorrent($torrentHash) {
		$status = $this->console->run("rtcontrol -q --cull --yes hash=$torrentHash");
		if (!$status) {
			$this->output->error($this->console->getErrorOutput());
			die($this->console->getErrorOutput());
		}
		return $this->console->getOutput();
	}

	public function listActiveTorrent() {
		$status = $this->console->run("rtcontrol is_active=yes -qo hash,name,up.sz,down.sz,size.sz,done,ratio,is_active");
		if (!$status) { 
			$this->output->error($this->console->getErrorOutput());
			die($this->console->getErrorOutput());
		}
		return $this->console->getOutput();
	}

	public function listInactiveTorrent() {
		$status = $this->console->run("rtcontrol is_active=no -qo hash,name,up.sz,down.sz,size.sz,done,ratio,is_active");
		if (!$status) { 
			$this->output->error($this->console->getErrorOutput());
			die($this->console->getErrorOutput());
		}
		return $this->console->getOutput();
	}

	public function checkTorrentExt($fileName) {
		if (substr(strrchr($fileName,'.'),1) == "torrent") {
			$this->output->info("Valid torrent extension");
			return true;
		} else { 
			$this->output->info("Invalid torrent extension");
			return false;
		}
	}

	public function checkFileType($fileType) {
		if ($fileType == "application/x-bittorrent") {
			$this->output->info("Valid file type");
			return true;
		} else { 
			$this->output->info("Invalid file type");
			return false;
		}
	}
}
?>
