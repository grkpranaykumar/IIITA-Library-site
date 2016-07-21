$("#search1").click(function(e) {
    $("#searchform1").show();
    $("#searchform2").hide();
    $("#requestform").hide();
    $("#d1").show();
    $("#d2").hide();
    $("#d3").hide();
    e.preventDefault();
});
$("#search2").click(function(e) {
    $("#searchform2").show();
    $("#searchform1").hide();
    $("#requestform").hide();
    $("#d1").hide();
    $("#d2").show();
    $("#d3").hide();
    e.preventDefault();
});
$("#request").click(function(e) {
	$("#requestform").show();
    $("#searchform2").hide();
    $("#searchform1").hide();
    $("#d1").hide();
    $("#d2").hide();
    $("#d3").show();
    e.preventDefault();
});

$("#searchform1").submit(function(e){
	e.preventDefault();
	$.ajax({
		type:"POST",
		url:"search.php",
		data:{
			group:"user",
			query:$('#query').val(),
			action:"search",
			subgroup:$('#subgroup').val()
		},
		success: function(result){
		
			result = JSON.parse(result);
			 if(result.errorMsgs.length){
			 	
			 	var items=result.errorMsgs;
				var i;
				for(i = 0; i < items.length; i++){
					$("#d1").html(items[i]+"<br/>");
				}
			 }
			 else{
			 	var items=result.successMsgs;
				var i;
				for(i = 0; i < items.length; i++){
					$("#d1").html(items[i]+"<br/>");
				}
			 	var source = $("#usertemplate").html(); 
				
				var template = Handlebars.compile(source); 
			 	var dat = {};
			 	dat.users = result.data.rows;
			 	console.log(dat);
			 	$('#result').html(template(dat));
			 }
		},
		error: function(result){
			alert(result);
		}
			
	});
});


$("#searchform2").submit(function(e){
	e.preventDefault();
	$.ajax({
		type:"POST",
		url:"search.php",
		data:{
			group:"book",
			query:$('#query2').val(),
			action:"search",
			subgroup:$('#subgroup2').val()
		},
		success: function(result){
		
			result = JSON.parse(result);
			 if(result.errorMsgs.length){
			 	
			 	var items=result.errorMsgs;
				var i;
				for(i = 0; i < items.length; i++){
					$("#d2").html(items[i]+"<br/>");
				}

			 }
			 else{
			 	var items=result.successMsgs;
				var i;
				for(i = 0; i < items.length; i++){
					$("#d2").html(items[i]+"<br/>");
				}
			 	var source = $("#booktemplate").html(); 
				
				var template = Handlebars.compile(source); 
			 	var dat = {};
			 	dat.books = result.data.rows;
			 	console.log(dat);
			 	$('#result').html(template(dat));
			 	
			 }
		},
		error: function(result){
			alert(result);
		}
			
	});
});

$("#requestform").submit(function(e){
	e.preventDefault();
	$.ajax({
		type:"POST",
		url:"user_operation.php",
		data:{
			isbn:$('#isbn_req').val(),
			request:"book"
		},
		success: function(result){
		
			result = JSON.parse(result);
			 if(result.errorMsgs.length){
			 	var items=result.errorMsgs;
				var i;
				for(i = 0; i < items.length; i++){
					$("#d3").html(items[i]+"<br/>");
				}
			 }
			 else{
			 	var items=result.successMsgs;
				var i;
				for(i = 0; i < items.length; i++){
					$("#d3").html(items[i]+"<br/>");
				}
			 }
		},
		error: function(result){
			alert(result);
		}
			
	});
});


$("#logout").click(function(){
	$.ajax({
		type:"POST",
		url:"log_in_or_out.php",
		data:{
			action:"logout"
		},
		success: function(result){
		
			result = JSON.parse(result);
			 if(result.errorMsgs.length){
			 	
			 	/*var items=result.errorMsgs;
				var i;
				for(i = 0; i < items.length; i++){
					$("#d5").html(items[i]+"<br/>");
				}*/
			 }
			 else{
			 	
			 	window.location.assign("index.html");
			 	/*var items=result.successMsgs;
				var i;
				for(i = 0; i < items.length; i++){
					$("#d5").html(items[i]+"<br/>");
				}
				var book_id = result.data.book_id;
				$("#d5").append("book id is "+book_id+"<br>");*/
			 }
		},
		error: function(result){
			alert(result);
		}
	})
})