<!-- Banner Title //-->
<div id="title">
	<a href="index.php">phoe721</a>
</div>
<!-- Search Bar //-->
<div id="search">
	<!-- Google Search Script //-->
	<script defer="defer">
		(function() {
		var cx = '008036803544194049939:s0cd6nt3ckm';
		var gcse = document.createElement('script');
		gcse.type = 'text/javascript';
		gcse.async = true;
		gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
			'//www.google.com/cse/cse.js?cx=' + cx;
		var s = document.getElementsByTagName('script')[0];
		s.parentNode.insertBefore(gcse, s);
		})();
	</script>
	<?	
	// Google search box declarations on search page and other pages
	if ($page == "search.php") {
		echo "<gcse:searchbox></gcse:searchbox>";
	} else {
		echo "<gcse:searchbox-only resultsUrl=\"search.php\"></gcse:searchbox-only>";
	} 
	?>
</div>
<!-- Menu Bar //-->
<div id="menu">
	<? include("menu.php"); ?>
</div>
