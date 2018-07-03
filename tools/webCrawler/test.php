<?php
include_once('simple_html_dom.php');
$target_url = "http://en.wikipedia.org/wiki/Web_crawler";
$html = new simple_html_dom();
$html->load_file($target_url);

foreach($html->find('p') as $link){
	echo $link . "<br />";
}
?>