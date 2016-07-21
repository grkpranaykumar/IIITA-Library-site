$(".req").click(function(){
		$.ajax({
			type:"POST",
			url:"user_operation.php",
			data:{
				//isbn:$(this).closest('tr').children('td.isbn').text(),
				isbn:
				request:"book"
			},
			success: function(result){
				console.log(this.data.isbn);
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
				 }
			},
			error: function(result){
				alert(result);
			}
				
		});
	});