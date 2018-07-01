<?php
require_once("torrentClient.php");
$client = new torrentClient;
$client->getUploadDir();
$client->start();
echo $client->listScreen() . "\n";
echo $client->countTorrentFile();
echo $client->checkTorrentExt("HOBBIT：THE DESOLATION OF SMAUG.torrent");
echo $client->validateTorrentFile("/var/www/html/phoe721.com/tools/upload/test.torrent");
echo $client->validateTorrentFile("/var/www/html/phoe721.com/tools/upload/a");
echo $client->removeTorrentFile("/var/www/html/phoe721.com/tools/upload/a");
echo $client->checkFileType("application/x-bittorrent");
echo $client->checkFileType("application/abc");
$client->stop();
?>
