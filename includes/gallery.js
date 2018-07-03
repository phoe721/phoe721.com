$(document).ready(function(){
	var imgArray = new Array();
	var total = 24;
	for (var i = 1; i <= total; i++) {
		imgArray[i] = "gallery/tiffany/" + i + ".jpg";
	}

	var cid = 2;
	intervalPlay = false;
	$("#content p img").click(function(){
		if (intervalPlay == false) {
			$("#content p img").fadeOut(400, function(){
				$("#content p img").attr("src", imgArray[cid++]);
			}).fadeIn(400);
		}
	});
	$("#content p img").mouseenter(function(){
		if (intervalPlay != false) {
			clearInterval(intervalPlay);
			intervalPlay = false;
		}
	});
	$("#content p img").mouseleave(function(){
		if (intervalPlay == false) {
			intervalPlay = setInterval(function(){
				cid++;
				if (cid > total) cid = 1;
				$("#content p img").fadeOut(400, function(){
					$("#content p img").attr("src", imgArray[cid]);
				}).fadeIn(400);
			},5000);				
		}
	});
	intervalPlay = setInterval(function(){
		cid++;
		if (cid > total) cid = 1;
		$("#content p img").fadeOut(400, function(){
			$("#content p img").attr("src", imgArray[cid]);
		}).fadeIn(400);
	},5000);		
	
	$("#sidebar .thumbnail img").click(function(){
		var newSrc = $(this).attr("src").replace("_small", "");
		var num = $(this).attr("src").replace("gallery/tiffany/", "").replace("_small.jpg", "");
		cid = num;
		$("#content p img").fadeOut(400, function(){
			$("#content p img").attr("src", newSrc);
		}).fadeIn(400);
	});
});