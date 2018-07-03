$(document).ready(function(){
	$("#menu ul li a").hover(function(){
		$(this).parent("li").find("ul").slideDown(200);
	});
	$("#menu ul li").mouseleave(function(){
		$(this).find("ul").fadeOut(400);
	});
	$("#menu ul li ul").mouseleave(function(){
		$(this).fadeOut(400);
	});
});