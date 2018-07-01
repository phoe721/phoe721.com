<span class="pageTitle">CentOS安裝Mail Server</span>
<span class="pageDate">最後修改時間：<?=$modifiedtime?></span>
<br /><br />
<p>
電子郵件是個劃時代的技術，讓人與人溝通不再需要紙本與紙本之間的傳遞，也省去了手寫的麻煩，更重要的是往來的時間利用網路技術，幾乎用不到幾分鐘時間就可以傳遞全世界。
<br /><br />
<span class="pageSubtitle">1. 安裝postfix</span><br />
<div class="terminal">
<span class="note">
#postfix是免費與開源的mail transfer agent(MTA)，簡單來說就是發送電子郵件的工具。
</span><br />
[aaron@phoenix ~]$ <span class="command">sudo yum install postfix</span>
</div>
<br />
<span class="pageSubtitle">2. 設定main.cf</span><br />
<div class="terminal">
<pre>
[aaron@phoenix ~]$ <span class="command">sudo vim /etc/postfix/main.cf</span>

myhostname = phoenix.phoe721.com<span class="sideNote">#伺服器名稱</span>

mydomain = phoe721.com<span class="sideNote">#網域名</span>

mydestination = $myhostname, localhost.$mydomain, localhost, $mydomain

mynetworks = 192.168.8.0/24, 127.0.0.0/8<span class="sideNote">#內網IP</span>

home_mailbox = mail/<span class="sideNote">#信箱資料夾</span>

smtpd_sasl_auth_enable = yes

smtpd_sasl_security_options = noanonymous

smtpd_sasl_local_domain = $myhostname

smtpd_recipient_restrictions = permit_sasl_authenticated,permit_mynetworks, check_relay_domains

broken_sasl_auth_clients = yes

smtpd_sasl_type = dovecot

smtpd_sasl_path = private/auth
</pre>
</div>
<br />
<span class="pageSubtitle">3. 啟動postfix</span><br />
<div class="terminal">
[aaron@phoenix ~]$ <span class="command">sudo chkconfig --level 3 postfix on</span><br />
[aaron@phoenix ~]$ <span class="command">sudo service postfix start</span>
</div>
<br />
<span class="pageSubtitle">4. 測試posftix</span>
<div class="terminal">
<pre>
[aaron@phoenix ~]$ <span class="command">telnet localhost smtp</span><span class="sideNote">#連線到本機經由smtp</span>
Trying ::1...
Connected to localhost.<span class="sideNote">#連線上了</span>
Escape character is '^]'.
220 phoenix.phoe721.com ESMTP Postfix
<span class="command">ehlo localhost</span>
250-phoenix.phoe721.com
250-PIPELINING
250-SIZE 10240000
250-VRFY
250-ETRN
250-AUTH PLAIN LOGIN
250-AUTH=PLAIN LOGIN
250-ENHANCEDSTATUSCODES
250-8BITMIME
250 DSN
<span class="command">mail from: phoe721@yahoo.com</span><span class="sideNote">#設定發信人</span>
250 2.1.0 Ok
<span class="command">rcpt to: aaron@phoe721.com</span><span class="sideNote">#設定收信人</span>
250 2.1.5 Ok
<span class="command">data</span><span class="sideNote">#發送內容格式</span>
354 End data with &#60;CR&#62;&#60;LF&#62;.&#60;CR&#62;&#60;LF&#62;
<span class="command">test</span><span class="sideNote">#信件內容為test</span>
<span class="command">.</span><span class="sideNote">#用.結束信件內容</span>
250 2.0.0 Ok: queued as 4E205205DB
<span class="command">quit</span><span class="sideNote">#登出</span>
221 2.0.0 Bye
Connection closed by foreign host.
</pre>
</div>
<br />
<span class="pageSubtitle">5. 安裝dovecot</span>
<div class="terminal">
[aaron@phoenix ~]$ <span class="command">sudo yum install dovecot</span>
</div>
<br />
<span class="pageSubtitle">6. 設定dovecot</span>
<div class="terminal">
<pre>
<span class="note">#修改dovecot.conf</span>
[aaron@phoenix ~]$ <span class="command">sudo vim /etc/dovecot/dovecot.conf</span>
protocols = imap pop3 lmtp<span class="sideNote">#設定要開啟的通訊協定</span>

<span class="note">#修改10-mail.conf</span>
[aaron@phoenix ~]$ <span class="command">sudo vim /etc/dovecot/conf.d/10-mail.conf</span>
mail_location = maildir:~/mail<span class="sideNote">#設定信箱資料夾位置</span>

<span class="note">#修改10-auth.conf</span>
[aaron@phoenix ~]$ <span class="command">sudo vim /etc/dovecot/conf.d/10-auth.conf</span>
auth_mechanisms = plain login<span class="sideNote">#登入格式</span>

<span class="note">#修改10-master.conf</span>
[aaron@phoenix ~]$ <span class="command">sudo vim /etc/dovecot/conf.d/10-master.conf</span>
unix_listener auth-userdb { 
    #mode = 0600 
    user = postfix 
    group = postfix 
}
</pre>
</div>
<br />
<span class="pageSubtitle">7. 啟動dovecot</span>
<div class="terminal">
[aaron@phoenix ~]$ <span class="command">sudo chkconfig --level 3 dovecot on</span>
<br />
[aaron@phoenix ~]$ <span class="command">sudo service dovecot start</span>
</div>
<br />
<span class="pageSubtitle">8. 安裝roundcube</span>
<div class="terminal">
<pre>
[aaron@phoenix ~]$ <span class="command">sudo yum install roundcubemail</span>

[aaron@phoenix ~]$ <span class="command">mysql -u root -p</span><span class="sideNote">#登入本機的MySql Server</span>

<span class="command">create database roundcube;</span><span class="sideNote">#建立資料庫roundcube</span>

<span class="command">create user roundcube;</span><span class="sideNote">#建立使用者roundcube</span>

<span class="command">GRANT ALL PRIVILEGES ON roundcube.* TO roundcube@localhost IDENTIFIED BY &#039;password&#039;;</span>
<span class="sideNote">#設定使用者roundcube的權限為全開</span> 

<span class="command">flush privileges;</span> 

<span class="command">use roundcube;</span><span class="sideNote">#開啟資料庫roundcube</span>
 
<span class="command">source /usr/share/doc/roundcubemail-0.8.6/SQL/mysql.initial.sql</span><span class="sideNote">#執行roundcube的SQL指令</span>

<span class="command">quit</span><span class="sideNote">#登出本機的MySQL Server</span> 
</pre>
</div>
<br />
<span class="pageSubtitle">8. 設定roundcube</span>
<div class="terminal">
<pre>
<span class="note">#修改db.inc.php</span>
[aaron@phoenix ~]$ <span class="command">sudo vim /etc/roundcubemail/db.inc.php</span>

$rcmail_config['db_dsnw'] = 'mysql://roundcube:password@localhost/roundcubemail';

<span class="note">#修改main.inc.php</span>
[aaron@phoenix ~]$ <span class="command">sudo vim /etc/roundcubemail/main.inc.php</span>

$rcmail_config['default_host'] = 'phoenix.phoe721.com';

$rcmail_config['smtp_server'] = 'phoenix.phoe721.com';<span class="sideNote">#設定smpt伺服器</span> 

$rcmail_config['mail_domain'] = 'phoe721.com';<span class="sideNote">#設定網域名</span> 

<span class="note">#修改roundcubemail.conf</span>
[aaron@phoenix ~]$ <span class="command">sudo vim /etc/httpd/conf.d/roundcubemail.conf</span>

Alias /roundcube /usr/share/roundcubemail<span class="sideNote">#設定信箱登入別名</span> 

&#60;Directory /usr/share/roundcubemail/&#62;
    &#60;IfModule mod_authz_core.c&#62;
        # Apache 2.4
        Require local
    &#60;/IfModule&#62;
    &#60;IfModule !mod_authz_core.c&#62;
        # Apache 2.2
        Allow from all
    &#60;/IfModule&#62;
&#60;/Directory&#62;

<span class="note">#修改php.ini</span>
[aaron@phoenix ~]$ <span class="command">sudo vim /etc/php.ini</span>

date.timezone = "Asia/Taipei"<span class="sideNote">#修改時區</span> 
</pre>
</div>
</p>