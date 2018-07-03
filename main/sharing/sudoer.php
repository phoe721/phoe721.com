<span class="pageTitle">CentOS設定sudoer</span>
<span class="pageDate">最後修改時間：<?=$modifiedtime?></span>
<br /><br />
<p>
Linux安裝套件與系統設定都需要root的權限，就是super-user privileges (最高權限)，也就是管理員權限。如何擁有root的權限？我們可以直接用ssh登入root的帳號，或用su(substitute user)指令來切換使用者，再不然就是使用sudo指令。
<br /><br />
<span class="pageSubtitle">1. 使用su指令</span><br />
<div class="terminal">
<pre>
<span class="note">#用su切換使用者</span>
[aaron@phoenix ~]$ <span class="command">su</span><span class="sideNote">#使用su切換為root</span>
Password:<span class="command">[輸入root密碼]</span>

[root@phoenix aaron]# <span class="command">service httpd restart</span><span class="sideNote">#已切換為root</span>
Stopping httpd:                                            [  OK  ]
Starting httpd:                                            [  OK  ]

[root@phoenix aaron]# <span class="command">exit</span><span class="sideNote">#切換回aaron</span>

<span class="note">#執行單一指令，不需要切換為root</span>
[aaron@phoenix ~]$ <span class="command">su -c "service httpd restart"</span>
Password:<span class="command">[輸入root密碼]</span>
Stopping httpd:                                            [  OK  ]
Starting httpd:                                            [  OK  ]

<span class="note">#執行su與su -的差別(Shell環境是否跟著切換)</span>
[aaron@phoenix ~]$ <span class="command">su</span>
Password:<span class="command">[輸入root密碼]</span>
[root@phoenix aaron]# <span class="command">echo $PATH</span><span class="sideNote">#列印PATH變數，發現還使用aaron的PATH變數</span>
/usr/local/bin:/bin:/usr/bin:/usr/local/sbin:/usr/sbin:/sbin:/home/aaron/bin

[aaron@phoenix ~]$ <span class="command">su -</span>
Password:<span class="command">[輸入root密碼]</span>
[root@phoenix aaron]# <span class="command">echo $PATH</span><span class="sideNote">#列印PATH變數，使用root的PATH變數</span>
/usr/local/sbin:/usr/local/bin:/sbin:/bin:/usr/sbin:/usr/bin:/root/bin
</pre>
</div>
<br />
<span class="pageSubtitle">2. 設定sudoer</span><br />
<div class="terminal">
<pre>
[aaron@phoenix ~]$ <span class="command">su</span>
Password:<span class="command">[輸入root密碼]</span>

[root@phoenix aaron]# <span class="command">vim /etc/sudoers</span>

## Allow root to run any commands anywhere
root    ALL=(ALL)       ALL

## Allow users to run any commands anywhere
aaron   ALL=(ALL)       ALL<span class="sideNote">#新增這條允許aaron可以執行管理員權限</span>
</pre>
</div>
<br />
<span class="pageSubtitle">3. 範例</span><br />
<div class="terminal">
<pre>
<span class="note">#沒有最高權限，所以就被擋了</span>
[aaron@phoenix ~]$ <span class="command">service httpd restart</span>
Stopping httpd:
rm: cannot remove `/var/run/httpd/httpd.pid': Permission denied

Starting httpd:
touch: cannot touch `/var/lock/subsys/httpd': Permission denied

<span class="note">#用sudo就可以</span>
[aaron@phoenix ~]$ <span class="command">sudo service httpd restart</span>
[sudo] password for aaron:<span class="command">[輸入密碼]</span>
Stopping httpd:                                            [  OK  ]
Starting httpd:                                            [  OK  ]
</pre>
</div>
<div class="comment">#su與sudo的差別在於是否要讓一般使用者知道root的密碼，如果用su就必須將root密碼分享出去，很有可能造成系統被侵入的危險。而sudo則是讓一般使用者有管理員權限，只要輸入自己的密碼即可，但同樣有風險，因為擁有管理員權限。</div>
</p>