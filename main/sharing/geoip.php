<span class="pageTitle">CentOS安裝GeoIP</span>
<span class="pageDate">最後修改時間：<?=$modifiedtime?></span>
<br /><br />
<p>
GeoIP利來IP來了解該IP實際所在位置、公司名稱與傳送速度等有用資訊。當然也可以了解入侵者都是從哪裡來，這也是我對GeoIP啟發興趣的原因。如果想更深入了解，可以到<a href="https://www.maxmind.com/en/geolocation_landing" target="_blank">MaxMind GeoIP</a>的網站了解。
<br /><br />
<span class="pageSubtitle">1. 安裝GeoIP</span><br />
<div class="terminal">
[aaron@phoenix ~]$ <span class="command">sudo yum install GeoIP</span>
</div>
<br />
<span class="pageSubtitle">2. 執行geoiplookup</span><br />
<div class="terminal">
<pre>
[aaron@phoenix ~]$ <span class="command">geoiplookup 61.174.51.235</span>
GeoIP Country Edition: CN, China<span class="sideNote">#China就是中國，就可以知道這IP是在中國</span>
</pre>
</div>
<br />
<span class="pageSubtitle">3. 查城市位置</span><br />
<div class="terminal">
<pre>
[aaron@phoenix ~]$ <span class="command">geoiplookup -f /usr/share/GeoIP/GeoLiteCity.dat 61.174.51.235</span>
<span class="note">#GeoLiteCity.dat必須另外下載</span>

GeoIP City Edition, Rev 1: CN, 02, Huzhou, N/A, 30.870300, 120.093300, 0, 0
<span class="note">#CN, Huzhou 中國浙江省湖州市，30.870300, 120.093300就是該城市的經緯度</span>
</pre>
</div>
<br />
<span class="pageSubtitle">4. 連結</span><br />
<ul>
<li><a href="http://dev.maxmind.com/geoip/legacy/geolite/" target="_blank">下載GeoLiteCity.dat</a></li>
</ul>
</p>