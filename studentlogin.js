function valid()
{
	var eMsg=[];
	var i=0;
    var allLetters = /^[a-zA-Z0-9]+$/;
    var letter = /[a-zA-Z]/;
    var number = /[0-9]/;	
	var user=document.studentForm.textField.value;
	var user=user.trim();
	var pass=document.studentForm.passwordField.value;
	if (!allLetters.test(user)) {
        i++;
        $("#p1").html("Username must contain only letters and numbers");
    }
    if (pass.length < 6 || !allLetters.test(pass) || pass.length > 29) {
        i++;
        $("#p2").html("Password length must be of atleast 6 chars & should contain letters and numbers only");
    }
    return i;
}

$("#studentLogin").submit(function(e){
	e.preventDefault();
	var errors;
	var Invalid=[];
	var Valid=[];
	errors=valid();
	if(errors==0){
		
		$.ajax({
			type:"POST",
			url:"log_in_or_out.php",
			data:{
				action:"login",
				type:"user",
				username:$('#textField').val(),
				password:$('#passwordField').val()
			},
			success: function(result){
				result = JSON.parse(result);
				 if(result.errorMsgs.length){
				 	
				 	var items=result.errorMsgs;
					(function myFunction(){
   						var i;
   						for(i = 0; i < items.length; i++){
   							$("#d1").html(items[i]+"<br/>");
   						}
				 	})();
				 	
				 }
				 else{
				 	var items=result.successMsgs;
					(function myFunction(){
   						var i;
   						for(i = 0; i < items.length; i++){
   							$("#d1").html(items[i]+"<br/>");
   						}
				 	})();
				 	window.location.href = "userhomepage.html";

				 }

			},
			error: function(result){
				alert(result);
			}
		});
	}
})