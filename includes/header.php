<!-- Meta Tags //-->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$title?></title>
<script type="text/javascript" src="includes/jquery-2.1.0.min.js"></script>
<script type="text/javascript" src="includes/menu.js"></script>
<? 
if ($page == "gallery.php") {
	echo "<script type=\"text/javascript\" src=\"includes/gallery.js\" defer=\"defer\"></script>";
}
?>
<link href="includes/style.css" type="text/css" rel="stylesheet" />
<!-- Google Analytics //-->
<script defer="defer">
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	ga('create', 'UA-39026005-3', 'auto');
	ga('send', 'pageview');
</script>