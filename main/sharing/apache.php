<span class="pageTitle">CentOS安裝apache</span>
<span class="pageDate">最後修改時間：<?=$modifiedtime?></span>
<br /><br />
<p>
Apache HTTP Server（簡稱Apache）是一個開放原始碼的網頁伺服器，可以在大多數電腦作業系統中運行，由於其跨平台和安全性，被廣泛使用，是最流行的Web伺服器端軟體之一。所謂的LAMP就是指<span class="special">L</span>inux、<span class="special">A</span>pache、<span class="special">M</span>ySQL和<span class="special">P</span>HP的聯合體，這是架網站必備四個核心。
<br />
<br />
<span class="pageSubtitle">1. 安裝httpd</span><br />
<div class="terminal">
[aaron@phoenix ~]$ <span class="command">sudo yum install httpd</span>
</div>
<br />
<span class="pageSubtitle">2. 設定chkconfig (管理系統服務預設啟動與否)</span><br />
<div class="terminal">
<pre>
[aaron@phoenix ~]$ <span class="command">sudo chkconfig --level 3 httpd on</span><span class="sideNote">#Runlevel 3時啟動httpd</span>

[aaron@phoenix ~]$ <span class="command">sudo chkconfig --list httpd</span><span class="sideNote">#檢查是否開啟</span>

httpd 0:off 1:off 2:off <span class="highlight">3:on</span> 4:off 5:off 6:off
</pre>
</div>
<br />
<span class="pageSubtitle">3. 修改httpd.conf (網站伺服器設定)</span><br />
<div class="terminal">
<pre>
[aaron@phoenix ~]$ <span class="command">sudo vim /etc/httpd/conf/httpd.conf</span>

ServerRoot "/etc/httpd"<span class="sideNote">#伺服器最頂端的目錄</span>

PidFile run/httpd.pid<span class="sideNote">#伺服器的PID檔，裡面存有網站伺服器的Process ID</span>

Timeout 60<span class="sideNote">#當連線等待超過60秒便會自動斷線</span>

KeepAlive On<span class="sideNote">#是否允許持續性的連線，改預設值為On，如果OFF的話則需要多個TCP連線才能將整個網頁下載完成</span>

MaxKeepAliveRequests 500<span class="sideNote">#持續性連線可要求的最大傳輸量</span>

KeepAliveTimeout 15<span class="sideNote">#持續性連線等待多久會斷線</span>

&#60;IfModule prefork.c&#62;
StartServers       8<span class="sideNote">#啟動多少個Process來處理網站服務</span>
MinSpareServers    5<span class="sideNote">#最少預備使用的Process數量</span>
MaxSpareServers   20<span class="sideNote">#最多預備使用的Process數量</span>
ServerLimit      256<span class="sideNote">#伺服器限制MaxClients最多數量</span>
MaxClients       256<span class="sideNote">#伺服器最多可以啟用多少個Process</span>
MaxRequestsPerChild  4000<span class="sideNote">#一個Process最多可以處理多少需求</span>
&#60;/IfModule&#62;

Listen 80<span class="sideNote">#監聽通訊埠80</span>

ServerAdmin phoe721@yahoo.com<span class="sideNote">#系統管理員信箱</span>

ServerName *:80<span class="sideNote">#網域名，這裡是只要從通訊埠80來</span>

DocumentRoot "/var/www/html"<span class="sideNote">#首頁的目錄</span>

ErrorLog logs/error_log<span class="sideNote">#錯誤訊息的log</span>

CustomLog logs/access_log combined<span class="sideNote">#連結訊息的log</span>

NameVirtualHost *:80<span class="sideNote">#虛擬網域名稱，一樣是通訊埠80</span>
&#60;VirtualHost *:80&#62;
    ServerAdmin root@localhost
    DocumentRoot /var/www/html/
    ServerName phoe721.com<span class="sideNote">#虛擬網域名</span>
    ServerAlias www.phoe721.com<span class="sideNote">#虛擬網域別名</span>
&#60;/VirtualHost&#62;
</pre>
</div>
<br />
<span class="pageSubtitle">4. 啟動httpd</span><br />
<div class="terminal">
[aaron@phoenix ~]$ <span class="command">sudo service httpd start</span>
</div>
<br />
<span class="pageSubtitle">5. 範例 (http://phoe721.com)</span><br />
<img src="images/apache.png" class="example" />
</p>