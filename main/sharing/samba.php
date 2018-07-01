<span class="pageTitle">CentOS安裝samba</span>
<span class="pageDate">最後修改時間：<?=$modifiedtime?></span>
<br /><br />
<p>
Samba可以讓Linux的資源在Windows系統上共用，讓跨平台的分享更方便。
<br />
<br />
<span class="pageSubtitle">1. 安裝samba</span><br />
<div class="terminal">
[aaron@phoenix ~]$ <span class="command">sudo yum install samba</span>
</div>
<br />
<span class="pageSubtitle">2. 設定smb.conf</span><br />
<div class="terminal">
<pre>
[aaron@phoenix ~]$ <span class="command">sudo vim /etc/samba/smb.conf</span>

#======================= Global Settings =====================================
[global]

 workgroup = Home<span class="sideNote">#Windows的工作群組</span>
 
 netbios name = phoenix<span class="sideNote">#主機名稱</span>
 
 server string = phoenix<span class="sideNote">#主機的簡易說明</span>
 
 security = user<span class="sideNote">#分享模式，user代表要登入用戶</span>
 
 unix charset = utf8<span class="sideNote">#文字編碼</span>
 
 display charset = utf8
 
 log file = /var/log/samba/log.%m<span class="sideNote">#log檔的位置</span>
 
 max log size = 50
 
 map to guest = bad user
 
#============================ Share Definitions ==============================

[share]<span class="sideNote">#資源分享的名稱</span>

 path = /home/user/share<span class="sideNote">#實際分享資源的路徑</span>
 
 valid users = user<span class="sideNote">#可使用資源的用戶</span>
 
 guest ok = no<span class="sideNote">#是否開放Guest</span>
 
 browsable = yes<span class="sideNote">#是否開放瀏覽</span>
 
 writable = yes<span class="sideNote">#是否開放寫入</span>
 
 read only = no<span class="sideNote">#是否唯讀</span>
</pre>
</div>
<br />
<span class="pageSubtitle">3. 設定chkconfig</span><br />
<div class="terminal">
<pre>
[aaron@phoenix ~]$ <span class="command">sudo chkconfig --level 3 smb on</span>

[aaron@phoenix ~]$ <span class="command">sudo chkconfig --level 3 nmb on</span>
</pre>
</div>
<br />
<span class="pageSubtitle">4. 設定smbusers</span><br />
<div class="terminal">
<pre>
<span class="note">#smbusers是用來對應Linux用戶與Windows用戶</span>
[aaron@phoenix ~]$ <span class="command">sudo vim /etc/samba/smbusers</span>

root = administrator admin

nobody = guest pcguest smbguest
</pre>
</div>
<br />
<span class="pageSubtitle">5. 啟動samba</span><br />
<div class="terminal">
<pre>
[aaron@phoenix ~]$ <span class="command">sudo service nmb start</span><span class="sideNote">#NetBIOS名稱查詢的daemon</span>

[aaron@phoenix ~]$ <span class="command">sudo service smb start</span><span class="sideNote">#權限管理的daemon</span>
</pre>
</div>
<br />
<span class="pageSubtitle">6. 範例</span><br />
<img src="images/samba.png" class="example" alt="Samba範例" />
<div class="imageComment">#可以看到phoenix主機(Linux)藉由samba分享出來的資源</div>
</p>