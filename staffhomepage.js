$("#search1").click(function(e) {
	$("#searchform1").show();
    $("#removebook").hide();
    $("#searchform2").hide();
    $("#addbook").hide();
    $("#returnbook").hide();
    $("#issuebook").hide();
    $("#d1").show();
	$("#d2").hide();
	$("#d3").hide();
	$("#d4").hide();
	$("#d5").hide();
	$("#d6").hide();
	$("#d7").hide();
    e.preventDefault();
});
$("#add2").click(function(e) {
    $("#addbook").show();
    $("#searchform2").hide();
    $("#removebook").hide();
    $("#searchform1").hide();
    $("#returnbook").hide();
    $("#issuebook").hide();
    $("#d1").hide();
	$("#d2").show();
	$("#d3").hide();
	$("#d4").hide();
	$("#d5").hide();
	$("#d6").hide();
	$("#d7").hide();
    e.preventDefault();
});
$("#remove2").click(function(e) {
    $("#removebook").show();
    $("#searchform2").hide();
    $("#addbook").hide();
    $("#searchform1").hide();
    $("#returnbook").hide();
    $("#issuebook").hide();
    $("#d1").hide();
	$("#d2").hide();
	$("#d3").show();
	$("#d4").hide();
	$("#d5").hide();
	$("#d6").hide();
	$("#d7").hide();
    e.preventDefault();
});

$("#search2").click(function(e) {
    $("#searchform2").show();
    $("#addbook").hide();
    $("#removebook").hide();
    $("#searchform1").hide();
    $("#returnbook").hide();
    $("#issuebook").hide();
    $("#d1").hide();
    $("#d2").hide();
    $("#d3").hide();
    $("#d4").show();
    $("#d5").hide();
    $("#d6").hide();
	$("#d7").hide();
    e.preventDefault();
});

$("#issue").click(function(e) {
	$("#issuebook").show();
	$("#returnbook").hide();
	$("#searchform1").hide();
    $("#removebook").hide();
    $("#searchform2").hide();
    $("#addbook").hide();
    $("#d1").hide();
	$("#d2").hide();
	$("#d3").hide();
	$("#d4").hide();
	$("#d5").show();
	$("#d6").hide();
	$("#d7").hide();
	e.preventDefault();
});
$("#return").click(function(e) {
	$("#returnbook").show();
	$("#searchform1").hide();
    $("#removebook").hide();
    $("#searchform2").hide();
    $("#addbook").hide();
    $("#issuebook").hide();
    $("#d1").hide();
	$("#d2").hide();
	$("#d3").hide();
	$("#d4").hide();
	$("#d5").hide();
	$("#d6").show();
	$("#d7").hide();
    e.preventDefault();
});

$("#view").click(function(e) {
	$("#returnbook").hide();
	$("#searchform1").hide();
    $("#removebook").hide();
    $("#searchform2").hide();
    $("#addbook").hide();
    $("#issuebook").hide();
    $("#d1").hide();
	$("#d2").hide();
	$("#d3").hide();
	$("#d4").hide();
	$("#d5").hide();
	$("#d6").hide();
	$("#d7").show();

    e.preventDefault();
});

$("#searchform1").submit(function(e){
	e.preventDefault();
	$.ajax({
		type:"POST",
		url:"search.php",
		data:{
			group:"staff",
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
			 	var source = $("#stafftemplate").html(); 
				
				var template = Handlebars.compile(source); 
			 	var dat = {};
			 	dat.staffs = result.data.rows;
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
					$("#d4").html(items[i]+"<br/>");
				}

			 }
			 else{
			 	var items=result.successMsgs;
				var i;
				for(i = 0; i < items.length; i++){
					$("#d4").html(items[i]+"<br/>");
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


$("#addbook").submit(function(e){
	e.preventDefault();
	$("#tables").hide();
	/*for(var j=0;j<.length;j++)
	{
		author[j]=$("#addauthor").val();
	}*/
	var authors = [];
	$("#aa > input").each(function(i, el){
		authors[i] = $(el).val();
	})
	var publishers = [];
	$("#ap > input").each(function(i, el){
		publishers[i] = $(el).val();
	})
	$.ajax({
		type:"POST",
		url:"staff_operation.php",
		data:{
			isbn:$('#addisbn').val(),
			id:$('#addid').val(),
			title:$('#addtitle').val(),
			authors:authors,
			publishers:publishers,
			price:$('#addprice').val(),
			shelf_no:$('#addshelf_no').val(),
			action:"add"
		},
		success: function(result){
			console.log("successs");
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
			 }
		},
		error: function(result){
			alert(result);
		}
	});
});

$("#removebook").submit(function(e){
	e.preventDefault();
	$.ajax({
		type:"POST",
		url:"staff_operation.php",
		data:{
			id:$('#removeid').val(),
			action:"remove"
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

$("#view").click(function(){
		$.ajax({
			type:"POST",
			url:"staff_operation.php",
			data:{
				action:"view"
			},
		success: function(result){
		
			result = JSON.parse(result);
			 if(result.errorMsgs.length){
			 	
			 	var items=result.errorMsgs;
				var i;
				for(i = 0; i < items.length; i++){
					$("#d7").html(items[i]+"<br/>");
				}
			 }
			 else{
			 	var items=result.successMsgs;
				var i;
				for(i = 0; i < items.length; i++){
					$("#d7").html(items[i]+"<br/>");
				}
			 	var source = $("#viewtemplate").html(); 
				
				var template = Handlebars.compile(source); 
			 	var dat = {};
			 	dat.requests = result.data.rows;
			 	console.log(dat);
			 	$('#result').html(template(dat));
			 }
		},
		error: function(result){
			alert(result);
		}
			
	});
});

$("#returnbook").submit(function(e){
	e.preventDefault();
		$.ajax({
			type:"POST",
			url:"staff_operation.php",
			data:{
				book_id:$('#book_id').val(),
				action:"return"
			},
		success: function(result){
		
			result = JSON.parse(result);
			 if(result.errorMsgs.length){
			 	
			 	var items=result.errorMsgs;
				var i;
				for(i = 0; i < items.length; i++){
					$("#d6").html(items[i]+"<br/>");
				}
			 }
			 else{
			 	var items=result.successMsgs;
				var i;
				for(i = 0; i < items.length; i++){
					$("#d6").html(items[i]+"<br/>");
				}
				var fine = result.data.fine;
				$("#d6").append("Fine is "+fine+"<br>");

			 }
		},
		error: function(result){
			alert(result);
		}
			
	});
});

$("#issuebook").submit(function(e){
	e.preventDefault();
		$.ajax({
			type:"POST",
			url:"staff_operation.php",
			data:{
				user_id:$('#user_id_iss').val(),
				isbn:$('#isbn_iss').val(),
				action:"issue"
			},
		success: function(result){
		
			result = JSON.parse(result);
			 if(result.errorMsgs.length){
			 	
			 	var items=result.errorMsgs;
				var i;
				for(i = 0; i < items.length; i++){
					$("#d5").html(items[i]+"<br/>");
				}
			 }
			 else{
			 	var items=result.successMsgs;
				var i;
				for(i = 0; i < items.length; i++){
					$("#d5").html(items[i]+"<br/>");
				}
				var book_id = result.data.book_id;
				$("#d5").append("book id is "+book_id+"<br>");
			 }
		},
		error: function(result){
			alert(result);
		}
			
	});
});


$("#addauthbut").click(function(e){
	e.preventDefault();
	$("#aa").append('<input type="text" name="author" placeholder="author" class="addauthor">')
})

$("#remauthbut").click(function(e){
	e.preventDefault();
	$("#aa > input").last().remove();
})

$("#addpubbut").click(function(e){
	e.preventDefault();
	$("#ap").append('<input type="text" name="publisher" placeholder="publisher" class="addpublisher">')
})

$("#rempubbut").click(function(e){
	e.preventDefault();
	$("#ap > input").last().remove();
})

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