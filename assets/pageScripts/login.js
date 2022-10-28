$(document).on('submit','form#loginForm', function(){
    var formData = $("#loginForm").serialize();
    $("#login_user_button").html('Please Wait...');
    $("#login_user_button").attr('disabled','disabled');          
    $.ajax({
        type:'POST',
        url: "./login/authenticate",
        data: formData,
        success: function(data)
        {
            response = JSON.parse(data);
            $("#login_user_button").html('Login');
            $("#login_user_button").removeAttr('disabled');
            if(response.status == 1){
                Swal.fire(
                    'Success',
                    "Successfully Logged In",
                    'success'
                )
                setTimeout(function(){ window.location.replace("./companies"); }, 1000);
            } else {
                Swal.fire(
                    'Error',
                    response.errors.login,
                    'error'
                )
            }                
        }
    });
});