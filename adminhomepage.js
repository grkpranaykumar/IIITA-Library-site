$("#add1").click(function(e) {
    $("#adduser").show();
    $("#addstaff").hide();
    $("#removeuser").hide();
    $("#removestaff").hide();
    $("#d1").show();
    $("#d2").hide();
    $("#d3").hide();
    $("#d4").hide();
    e.preventDefault();
});
$("#add2").click(function(e) {
    $("#addstaff").show();
    $("#adduser").hide();
    $("#removeuser").hide();
    $("#removestaff").hide();
    $("#d1").hide();
    $("#d2").show();
    $("#d3").hide();
    $("#d4").hide();
    e.preventDefault();
});
$("#remove1").click(function(e) {
	$("#removeuser").show();
    $("#adduser").hide();
    $("#addstaff").hide();
    $("#removestaff").hide();
    $("#d1").hide();
    $("#d2").hide();
    $("#d3").show();
    $("#d4").hide();
    e.preventDefault();
});
$("#remove2").click(function(e) {
    $("#removestaff").show();
    $("#adduser").hide();
    $("#addstaff").hide();
    $("#removeuser").hide();
    $("#d1").hide();
    $("#d2").hide();
    $("#d3").hide();
    $("#d4").show();
    e.preventDefault();
});


$("#adduser").submit(function(e){
	e.preventDefault();
	//alert("Hi");
	$.ajax({
		type:"POST",
		url:"admin_operation.php",
		data:{
			id:$('#addid1').val(),
			name:$('#addname1').val(),
			address:$('#addaddress1').val(),
			username:$('#addusername1').val(),
			password:$('#addpassword1').val(),
			group:"user",
			action:"add"
		},
		success: function(result){
			//alert("Hi");
			result = JSON.parse(result);
			 if(result.errorMsgs.length){
			 	
			 	var items=result.errorMsgs;
				var i;
				for(i = 0; i < items.length; i++){
					$("#d1").append(items[i]+"<br/>");
				}

			 }
			 else{
			 	var items=result.successMsgs;
				var i;
				for(i = 0; i < items.length; i++){
					$("#d1").append(items[i]+"<br/>");
				}
			 }
		},
		error: function(result){
			alert(result);
		}
	});
});


$("#addstaff").submit(function(e){
	e.preventDefault();
	$.ajax({
		type:"POST",
		url:"admin_operation.php",
		data:{
			id:$('#addid2').val(),
			name:$('#addname2').val(),
			address:$('#addaddress2').val(),
			username:$('#addusername2').val(),
			password:$('#addpassword2').val(),
			group:"staff",
			action:"add"
		},
		success: function(result){
		
			result = JSON.parse(result);
			 if(result.errorMsgs.length){
			 	
			 	var items=result.errorMsgs;
				var i;
				for(i = 0; i < items.length; i++){
					$("#d2").append(items[i]+"<br/>");
				}

			 }
			 else{
			 	var items=result.successMsgs;
				var i;
				for(i = 0; i < items.length; i++){
					$("#d2").append(items[i]+"<br/>");
				}
			 }
		},
		error: function(result){
			alert(result);
		}
	});
});

$("#removeuser").submit(function(e){
	e.preventDefault();
	$.ajax({
		type:"POST",
		url:"admin_operation.php",
		data:{
			id:$('#removeid1').val(),
			group:"user",
			action:"remove"
		},
		success: function(result){
		
			result = JSON.parse(result);
			console.log(result);
			console.log($('#removeid1').val());
			 if(result.errorMsgs.length){
			 	
			 	var items=result.errorMsgs;
				var i;
				for(i = 0; i < items.length; i++){
					$("#d3").append(items[i]+"<br/>");
				}

			 }
			 else{
			 	var items=result.successMsgs;
				var i;
				for(i = 0; i < items.length; i++){
					$("#d3").append(items[i]+"<br/>");
				}
			 }
		},
		error: function(result){
			alert(result);
		}
	});
});

$("#removestaff").submit(function(e){
	e.preventDefault();
	$.ajax({
		type:"POST",
		url:"admin_operation.php",
		data:{
			id:$('#removeid2').val(),
			group:"staff",
			action:"remove"
		},
		success: function(result){
		
			result = JSON.parse(result);
			 if(result.errorMsgs.length){
			 	
			 	var items=result.errorMsgs;
				var i;
				for(i = 0; i < items.length; i++){
					$("#d4").append(items[i]+"<br/>");
				}

			 }
			 else{
			 	var items=result.successMsgs;
				var i;
				for(i = 0; i < items.length; i++){
					$("#d4").append(items[i]+"<br/>");
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