<?
$page = basename($_SERVER['PHP_SELF']);
switch($page) {
	case "index.php" : 
		$title = "鳳翼資訊工作室"; 
		break;
	case "project.php" :
		$title = "作品集"; 
		if (isset($_GET["project"])) {
			$project = "main/project/" . strtolower(str_replace(" ", "", $_GET["project"])) . ".php";
			if (file_exists($project)) {
				$breadcrumb = $_GET["project"];
				$title .= " - " . $breadcrumb;
			} else {
				$project = "main/project/main.php";
			}
		} else {
			$project = "main/project/main.php";
		}
		break;
	case "sharing.php" :
		$title = "分享"; 
		if (isset($_GET["sharing"])) {
			$sharing = "main/sharing/" . strtolower(str_replace(" ", "", $_GET["sharing"])) . ".php";
			if (file_exists($sharing)) {
				$modifiedtime = date("Y/n/d H:i:s", filemtime($sharing));
				$breadcrumb = $_GET["sharing"];
				if ($breadcrumb == "sudoer" || $breadcrumb == "htpasswd") {
					$breadcrumb = "設定 $breadcrumb";
				} else {
					$breadcrumb = "安裝 $breadcrumb";
				}
				$title .= " - " . $breadcrumb;
			} else {
				$sharing = "main/sharing/main.php";
			}
		} else {
			$sharing = "main/sharing/main.php";
		}
		break;
	case "gallery.php" :
		$title = "相簿"; 
		break;
}
?>
