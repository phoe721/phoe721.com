<span class="pageTitle">CentOS安裝vsftpd</span>
<span class="pageDate">最後修改時間：<?=$modifiedtime?></span>
<br /><br />
<p>
什麼是vsftpd，就是Very Secure FTP Daemon，簡單來說就是FTP Server的套件。
FTP Server方便用戶可從遠端來上傳檔案到自己的FTP Server上與下載檔案到本機。
<br />
<br />
<span class="pageSubtitle">1. 安裝vsftpd</span>
<div class="terminal">
<pre>
[aaron@phoenix ~]$ <span class="command">sudo yum install vsftpd</span><span class="sideNote">#這是ftp server (伺服器端)</span>

[aaron@phoenix ~]$ <span class="command">sudo yum install ftp</span><span class="sideNote">#這是ftp client (用戶端)</span>
</pre>
</div>
<div class="comment">#ftp套件預設已經安裝上Linux</div>
<br />
<span class="pageSubtitle">2. 設定vsftpd.conf</span>
<div class="terminal">
<pre>
[aaron@phoenix ~]$ <span class="command">sudo vim /etc/vsftpd/vsftpd.conf</span>

anonymous_enable=NO<span class="sideNote">#是否允許匿名登入</span>

local_umask=022<span class="sideNote">#umask用來設定檔案權限，所以新目錄(755)，檔案(644)</span>

xferlog_enable=YES<span class="sideNote">#是否要log記錄?</span>

xferlog_file=/var/log/xferlog<span class="sideNote">#log檔的位置</span>

ftpd_banner=歡迎來到我的FTP伺服器<span class="sideNote">#登入的訊息，可自訂</span>

chroot_local_user=YES<span class="sideNote">#是否開啟chroot？chroot就是限定用戶只能在自己的根目錄</span>

chroot_list_enable=YES<span class="sideNote">#是否使用chroot_list？chroot_list就是不被chroot的用戶清單</span>

chroot_list_file=/etc/vsftpd/chroot_list<span class="sideNote">#chroot_list的位置</span>
</pre>
</div>
<br />
<span class="pageSubtitle">3. 啟動vsftpd</span>
<div class="terminal">
<pre>
[aaron@phoenix ~]$ <span class="command">sudo chkconfig --level 3 vsftpd on</span><span class="sideNote">#設定Runlevel 3開啟vsftpd</span>

[aaron@phoenix ~]$ <span class="command">sudo service vsftpd start</span><span class="sideNote">#啟動vsftpd</span>
</pre>
</div>
<br />
<span class="pageSubtitle">4. 測試vsftpd</span>
<div class="terminal">
<pre>
[aaron@phoenix ~]$ <span class="command">ftp localhost</span>
Trying 127.0.0.1...
Connected to localhost (127.0.0.1).
220 歡迎來到我的FTP伺服器<span class="sideNote">#這就是登入訊息</span>
Name (localhost:user): <span class="command">user</span>
331 Please specify the password.
Password: <span class="command">[輸入密碼]</span>
230 Login successful.
Remote system type is UNIX.
Using binary mode to transfer files.
ftp><span class="sideNote">#登入成功啦！</span>
</pre>
</div>
</p>