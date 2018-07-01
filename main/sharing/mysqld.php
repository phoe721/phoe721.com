<span class="pageTitle">CentOS安裝mysqld</span>
<span class="pageDate">最後修改時間：<?=$modifiedtime?></span>
<br /><br />
<p>
MySQL是一個開放原始碼的關聯式資料庫管理系統，
MySQL由於效能高、成本低、可靠性好，已經成為最流行的開源資料庫，因此被廣泛地應用在Internet上的中小型網站中。
<br />
<br />
<span class="pageSubtitle">1. 安裝mysql-server</span><br />
<div class="terminal">
[aaron@phoenix ~]$ <span class="command">sudo yum install mysql-server mysql</span>
</div>
<br />
<span class="pageSubtitle">2. 設定chkconfig</span><br />
<div class="terminal">
[aaron@phoenix ~]$ <span class="command">sudo chkconfig --level 3 mysqld on</span>
<span class="sideNote">#Runlevel 3時啟動mysqld</span>
</div>
<br />
<span class="pageSubtitle">3. 設定mysqld</span><br />
<div class="terminal">
<pre>
[aaron@phoenix ~]$ <span class="command">mysql -u root -p</span><span class="sideNote">#輸入密碼，如果不輸入密碼為空白</span>
mysql> <span class="command">SET PASSWORD FOR 'root'@'localhost' = PASSWORD('new-password');</span>
mysql> <span class="command">exit</span>
</pre>
</div>
<div class="comment">註：new-password就是自設密碼，不要打new-password當密碼...</div>
<br />
<span class="pageSubtitle">4. 啟動mysqld</span><br />
<div class="terminal">
[aaron@phoenix ~]$ <span class="command">sudo service mysqld start</span>
</div>
<br />
<span class="pageSubtitle">5. 範例mysqld</span><br />
<div class="terminal">
<span class="note">#測試mysqld是否正常</span>
<pre>
[aaron@phoenix ~]$ <span class="command">mysql -u root -p</span>
Enter password:
Welcome to the MySQL monitor.  Commands end with ; or \g.
Your MySQL connection id is 39
Server version: 5.1.66 Source distribution

Copyright (c) 2000, 2012, Oracle and/or its affiliates. All rights reserved.

Oracle is a registered trademark of Oracle Corporation and/or its
affiliates. Other names may be trademarks of their respective
owners.

Type 'help;' or '\h' for help. Type '\c' to clear the current input statement.

mysql> <span class="command">SHOW DATABASES;</span>
+--------------------+
| Database           |
+--------------------+
| information_schema |
| mysql              |
| test               |
+--------------------+
3 rows in set (0.00 sec)

mysql> <span class="command">exit</span>
Bye
</pre>
</div>
</p>