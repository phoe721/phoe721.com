<?
switch($page) {
	case "index.php":
		break;
	case "project.php":
		echo "<a href=\"index.php\">首頁</a> > ";
		if (isset($breadcrumb)) {
			echo "<a href=\"project.php\">作品集</a> > $breadcrumb";
		} else {
			echo "作品集";	
		}
		break;
	case "sharing.php":
		echo "<a href=\"index.php\">首頁</a> > ";
		if (isset($breadcrumb)) {
			echo "<a href=\"sharing.php\">分享</a> > $breadcrumb";
		} else {
			echo "分享";
		}
		break;
	case "gallery.php":
		echo "<a href=\"index.php\">首頁</a> > ";
		echo "相簿";
		break;
}
?>