<span class="pageTitle">CentOS安裝php</span>
<span class="pageDate">最後修改時間：<?=$modifiedtime?></span>
<br /><br />
<p>
PHP是一種開源的通用電腦語言，尤其適用於網路開發並可嵌入HTML中使用。PHP的語法借鑒吸收了C語言、Java和Perl等流行電腦語言的特點，易於一般程式設計師學習。PHP的主要標的是允許網路開發人員快速編寫動態頁面。
<br />
<br />
<span class="pageSubtitle">1. 安裝php</span><br />
<div class="terminal">
[aaron@phoenix ~]$ <span class="command">sudo yum install php php-mysql</span>
</div>
<br />
<span class="pageSubtitle">2. 修改php.ini</span><br />
<div class="terminal">
<pre>
[aaron@phoenix ~]$ <span class="command">sudo vim /etc/php.ini</span>

short_open_tag = On<span class="sideNote">#是否使用簡略語法</span>

max_input_time = 60<span class="sideNote">#程式執行時間限制為60秒</span>

memory_limit = 128M<span class="sideNote">#記憶體限制為128MB</span>

error_reporting = E_ALL | E_STRICT<span class="sideNote">#錯誤回報設定</span>

error_log = /var/log/httpd/php_errors.log<span class="sideNote">#錯誤訊息log的位置</span>

post_max_size = 8M<span class="sideNote">#POST可用記憶體大小</span>
</pre>
</div>
<br />
<span class="pageSubtitle">3. 範例 (http://你的網址/test.php)</span><br />
<div class="terminal">
<span class="note">#撰寫一個測試頁，命名為test.php，內容如下</span><br />
<pre>
&#60;?php 
	phpinfo(); 
?&#62;
</pre>
</div>
<img src="images/php.png" class="example" />
<div class="imageComment">註：出現這個畫面就代表php安裝成功嚕～</div>
</p>