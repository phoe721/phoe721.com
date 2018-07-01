<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript">
var form; 
function load() {
	form = window.opener.document.forms[0];
	document.writeln("<table>");
	document.writeln("<tr>");
	document.writeln("<td colspan=\"2\">");
	document.writeln("<b>報價單號: </b>" + form["order_no"].value + "<br />");
	document.writeln("</td>");
	document.writeln("</tr>");
	document.writeln("<tr>");
	document.writeln("<td>");
	document.writeln("<b>公司名稱: </b>" + form["company"].value + "<b>統一編號: </b>" + form["tax_id"].value + "<br />");
	document.writeln("<b>公司地址: </b>" + form["address"].value + "<br />");
	document.writeln("<b>其他備註: </b>" + form["comment"].value + "<br />");
	document.writeln("</td>");
	document.writeln("<td>");
	document.writeln("<b>聯絡人: </b>" + form["contact"].value + "<br />&nbsp;&nbsp;&nbsp;");
	document.writeln("<b>電話: </b>" + form["phone"].value + "<br />&nbsp;&nbsp;&nbsp;");
	document.writeln("<b>信箱: </b>" + form["email"].value + "<br />");
	document.writeln("</td>");
	document.writeln("</tr>");
	document.writeln("</table>");
	document.writeln("<br />");
	document.writeln("<table border=\"1\" style=\"border-collapse:collapse;\">");
	document.writeln("<tr>");
	document.writeln("<th width=\"150\" align=\"center\">日期</th>");
	document.writeln("<th width=\"250\" align=\"center\">品項</th>");
	document.writeln("<th width=\"150\" align=\"center\">價格</th>");
	document.writeln("</tr>");
	if (form["order[]"].value != undefined) {
		var date = form["year[]"].value + "-" + form["month[]"].value + "-" + form["day[]"].value;
		document.writeln("<tr>");
		document.writeln("<td align=\"center\">" + date + "</td>");
		document.writeln("<td>" + form["order[]"].value + "</td>");document.writeln("<td align=\"right\">" + form["price[]"].value + "元</td>");
		document.writeln("</tr>");
	} else {
		for (var i = 0; i < form["order[]"].length; i++) {			
			if (form["order[]"][i].value != "") {
				var date = form["year[]"][i].value + "-" + form["month[]"][i].value + "-" + form["day[]"][i].value;
				document.writeln("<tr>");
				document.writeln("<td align=\"center\">" + date + "</td>");
				document.writeln("<td>" + form["order[]"][i].value + "</td>");document.writeln("<td align=\"right\">" + form["price[]"][i].value + "元</td>");
				document.writeln("</tr>");
			}
		}	
	}
	document.writeln("<tr>");
	document.writeln("<td align=\"right\" colspan=\"2\">含稅: ");
	for (var i = 0; i < form["tax"].length; i++) {
		if (form["tax"][i].checked) {
			if (form["tax"][i].value == "yes") {
				document.writeln("是");
			} else {
				document.writeln("否");
			}
		}
	}
	document.writeln("&nbsp;&nbsp;</td>");
	document.writeln("<td align=\"right\">合計:&nbsp;&nbsp;" + form["subtotal"].value + "元</td>");
	document.writeln("</tr>");
	document.writeln("</table>");
	document.writeln("<div style=\"margin-top:5px;padding-left:515px\">");
	document.writeln("<button id=\"print\" type=\"button\" onclick=\"window.print()\">列印</button>");
	document.writeln("</div>");
}
</script>
</head>
<body onload="load()">
</body>
</html>