<span class="pageTitle">Apache資料夾設定帳密控管</span>
<span class="pageDate">最後修改時間：<?=$modifiedtime?></span>
<br /><br />
<p>
架設完網站，但有些重要資料的資料夾不想分享出去，怎麼辦？就是設定帳密。如何設定一個簡單又有效的帳密管理，就需要用到htpasswd這個工具的功能搭配.htaccess檔，就能簡單的保護自己重要的資料夾，也不會被搜尋引擎找到。
<br /><br />
<span class="pageSubtitle">1. 設定.htaccess</span><br />
<div class="terminal">
<pre>
<span class="note">#為test資料夾設定.htaccess，就是要帳密保護的資料夾</span>
[aaron@phoenix ~]$ <span class="command">vi /home/user/test/.htaccess</span>

AuthName "會員區"<span class="sideNote">#登入畫面訊息</span>

AuthType Basic<span class="sideNote">#登入模式</span>

AuthUserFile /home/user/.htpasswd<span class="sideNote">#密碼檔的位置</span>

require valid-user
</pre>
</div>
<br />
<span class="pageSubtitle">2. 執行htpasswd</span><br />
<div class="terminal">
<pre>
[aaron@phoenix ~]$ <span class="command">htpasswd -c .htpasswd user</span><span class="sideNote">#建立密碼擋，設定密碼給帳戶user</span>
New password:<span class="sideNote">#設定密碼</span>
Re-type new password:<span class="sideNote">#重新輸入密碼</span>
</pre>
</div>
<div class="comment">#htpasswd -c是「建立」密碼檔，如只要設定其他帳戶就要拿掉-c</div>
<br />
<span class="pageSubtitle">3. 修改httpd.conf</span><br />
<div class="terminal">
<pre>
[aaron@phoenix ~]$ <span class="command">sudo vim /etc/httpd/conf/httpd.conf</span>

Alias /test/ "/home/user/test/"<span class="sideNote">#test為要分享的資料夾名稱，後面為實際路徑</span>

&#60;Directory "/home/user/test/"&#62;
    AllowOverride all<span class="sideNote">#底層資料夾可以使用.htaccess來控管</span>
    Options Indexes FollowSymLinks
    Order allow,deny
    Allow from all
&#60;/Directory&#62;
</pre>
</div>
<div class="comment">#輸入網域名加上/test/在後面就可以進行測試，例：http://phoe721.com/test/</div>
<br />
<span class="pageSubtitle">4. 範例</span><br />
<img src="images/htpasswd.png" class="example" />
</p>