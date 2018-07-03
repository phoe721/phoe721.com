<span class="pageTitle">CentOS安裝fail2ban</span>
<span class="pageDate">最後修改時間：<?=$modifiedtime?></span>
<br /><br />
<p>
fail2ban會掃描Linux上的log檔，用來擋惡意的IP，這些IP會多次嘗試帳戶登入，企圖找出伺服器的漏洞。大部分使用者會用ssh來登入Linux伺服器作管理與維運的工作，所以這些惡意IP會不斷嘗試猜測root的密碼，以來取得終極管理權。fail2ban要搭配防火牆iptables一起使用，可用來監控ssh、ftp和apache等伺服器端的服務，一旦發現惡意IP，便將這些IP阻擋，徹底阻擋這些IP繼續來搗亂。<br />
<br />
<span class="pageSubtitle">1. 安裝fail2ban</span><br />
<div class="terminal">
[aaron@phoenix ~]$ <span class="command">sudo yum install fail2ban</span><br />
</div>
<br />
<span class="pageSubtitle">2. 設定fail2ban.conf</span><br />
<div class="terminal">
<pre>
[aaron@phoenix ~]$ <span class="command">sudo vim /etc/fail2ban/fail2ban.conf</span>

logtarget = /var/log/fail2ban.log<span class="sideNote">#找到logtarget，修改為fail2ban.log</span>
</pre>
</div>
<div class="comment">
註：fail2ban的log就會紀錄在/var/log/fail2ban.log的檔案中<br />
</div>
<br />
<span class="pageSubtitle">3. 設定jail.conf</span><br />
<div class="terminal">
<pre>
<span class="note">#修改backend的設定</span>
[aaron@phoenix ~]$ <span class="command">sudo vim /etc/fail2ban/jail.conf</span>

backend = gamin<span class="sideNote">#修改為gamin，Gamin為Linux套件，用來監測系統檔案的變動</span>

<span class="note">#修改ssh-iptables的設定</span>
[ssh-iptables]
enabled  = true<span class="sideNote">#是否啟動這個監測</span> 
filter   = sshd
action   = iptables[name=SSH, port=ssh, protocol=tcp]
	   sendmail-whois[name=SSH, 
	   dest=xxx@xxx.com,<span class="sideNote">#收信人信箱</span> 
	   sender=xxx@xxx.com,<span class="sideNote">#發信人信箱</span> 
	   sendername="Fail2Ban"]<span class="sideNote">#發信人</span> 
logpath  = /var/log/secure
maxretry = 2<span class="sideNote">#maxretry為最高嘗試錯誤次數，表示可以比maxretry還多一次</span>
bantime = -1<span class="sideNote">#bantime單位是秒，600則是10分鐘，-1則是永久阻擋</span>
</pre>
</div>
<br />
<span class="pageSubtitle">4. 設定chkconfig</span><br />
<div class="terminal">
<pre>
[aaron@phoenix ~]$ <span class="command">sudo chkconfig --level 3 fail2ban on</span><span class="sideNote">#Runlevel 3時啟動fail2ban</span>

[aaron@phoenix ~]$ <span class="command">sudo chkconfig --list fail2ban</span><span class="sideNote">#檢查是否開啟</span>

fail2ban 0:off 1:off 2:off <span class="highlight">3:on</span> 4:off 5:off 6:off
</pre>
</div>
<br />
<span class="pageSubtitle">5. 啟動fail2ban</span><br />
<div class="terminal">
[aaron@phoenix ~]$ <span class="command">sudo service fail2ban start</span>
<span class="sideNote">#設定完，便可啟動fail2ban</span>
<br />
</div>
<br />
<span class="pageSubtitle">5. 範例fail2ban.log</span><br />
<div class="terminal">
<span class="note">#實際log資料，可以看到時間點與執行狀態</span>				
<pre>
2014-06-24 07:56:36,471 fail2ban.jail   : INFO   Creating new jail 'ssh-iptables'
2014-06-24 07:56:36,589 fail2ban.jail   : INFO   Jail 'ssh-iptables' uses Gamin
2014-06-24 07:56:36,725 fail2ban.jail   : INFO   Initiated 'gamin' backend
2014-06-24 07:56:36,735 fail2ban.filter : INFO   Added logfile = /var/log/secure
2014-06-24 07:56:36,741 fail2ban.filter : INFO   Set maxRetry = 2
2014-06-24 07:56:36,749 fail2ban.filter : INFO   Set findtime = 600
2014-06-24 07:56:36,754 fail2ban.actions: INFO   Set banTime = -1
2014-06-24 07:56:37,299 fail2ban.jail   : INFO   Jail 'ssh-iptables' started
2014-06-24 09:56:27,706 fail2ban.actions: WARNING [ssh-iptables] Ban 116.10.191.236
2014-06-24 14:37:24,889 fail2ban.actions: WARNING [ssh-iptables] Ban 118.242.3.90
2014-06-24 15:14:38,573 fail2ban.actions: WARNING [ssh-iptables] Ban 50.30.34.7
</pre>
</div>
<br />
<span class="pageSubtitle">6. 範例fail2ban-client</span><br />
<div class="terminal">
<span class="note">#執行fail2ban-client來查看已封鎖的IP</span>	
<pre>
[aaron@phoenix ~]$ <span class="command">sudo fail2ban-client status ssh-iptables</span>			
Status for the jail: ssh-iptables
|- filter
|  |- File list:        /var/log/secure
|  |- Currently failed: 0
|  `- Total failed:     63
`- action
   |- Currently banned: 19
   |  `- IP list:       116.10.191.233 116.10.191.189 116.10.191.168 61.174.51.2 13...
   `- Total banned:     19
</pre>
</div>
</p>