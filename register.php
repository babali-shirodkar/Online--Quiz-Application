<?php
    include "backend/confi/database.php"
?>
<!DOCTYPE html>
<html dir="ltr">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>

    <title>Quiz Application - Register</title>

    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon.png"/>
    <link href="dist/css/style.min.css" rel="stylesheet"/>
    <link href="dist/css/customstyle.css" rel="stylesheet"/>

</head>

<body>

    <div class="register-container">
        <div class="register-card">
            
            <div class="register-logo">
                <img src="assets/images/logo-icon.png" alt="Logo">
                <h3 class="register-title">Create Account</h3>
                <p class="text-muted small">Join QuizApp today</p>
            </div>
            
        
            <form class="form-horizontal" action="api/auth/register.php" method="POST">

                <!-- Full Name -->
                <!-- Changed mb-3 to mb-4 for more space between groups -->
                <div class="form-group mb-4">
                    <label>Full Name</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text h-100">
                                <i class="mdi mdi-account"></i>
                            </span>
                        </div>
                        <input type="text" 
                        name="fullname" 
                        id="fullname"
                        class="form-control"
                        placeholder="Enter full name" 
                        required>
                    </div>
                </div>

                <!-- Email -->
                <div class="form-group mb-4">
                    <label>Email Address</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text h-100">
                                <i class="mdi mdi-email"></i>
                            </span>
                        </div>
                        <input
                        type="email"
                        name="email" 
                        id= "email"
                        class="form-control"
                        placeholder="Enter email address" 
                        required>
                    </div>
                </div>

                <!-- Password -->
                <div class="form-group mb-4">
                    <label>Password</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text h-100">
                                <i class="mdi mdi-lock"></i>
                            </span>
                        </div>
                        <input
                        type="password" 
                        name="password" 
                        id="password" 
                        class="form-control" 
                        placeholder="Enter password" 
                        required>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="form-group mb-4">
                    <label>Confirm Password</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text h-100">
                                <i class="mdi mdi-lock"></i>
                            </span>
                        </div>
                        <input 
                        type="password" 
                        name="confirm_password" 
                        id="confirm_password"
                        class="form-control" 
                        placeholder="Confirm password" 
                        required>
                    </div>
                </div>

                <!-- Register Button -->
                <div class="d-grid mb-3">
                    <a  class="btn btn-info btn-register text-white w-100" id="signupBtn" onclick="RegisterPage();">
                        Sign Up
                    </a>
                </div>

                <div class="text-center register-links">
                    <span class="text-muted">
                        Already have an account?
                        <a href="login.php" class="text-info font-weight-bold ml-1">Login</a>
                    </span>
                </div>

            </form>
        </div>
    </div>

   <?php
   include 'pages/admin/includes/footer.php';
   ?>

    <script>
     
    var api_url = '<?php echo $api_url; ?>'

    function RegisterPage()
{
    let fullname = $("#fullname").val().trim();
    let email = $("#email").val().trim();
    let password = $("#password").val();
    let confirm_password = $("#confirm_password").val();

    if (fullname === "" || email === "" || password === "" || confirm_password === "")
    {
        showMessage("danger", "Please enter all fields");
        return;
    }

    if(password !== confirm_password){
        showMessage("danger", "Passwords do not match");
        return;
    }

    let registerData = {
        fullname: fullname,
        email: email,
        password: password,
        confirm_password: confirm_password 
    };

    $.ajax({

        url: api_url + 'auth/register.php',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(registerData),
        dataType: 'json',

        success: function(response){

            if(response.status === "success"){

                showMessage("success", response.message);

                setTimeout(function(){
                    window.location.href = "login.php";
                }, 1000);

            }else{
                showMessage("danger", response.message);
            }

           
        },

        error: function(){
            showMessage("danger", "Server error. Try again.");
            
        }

    });
}


 function showMessage(type, message) {

        $(".alert").remove(); 
        let html = `
            <div class="alert alert-${type} mt-2">
                ${message}
            </div>
        `;

        $(".login-card").prepend(html);
    }

</script>

</body>
</html>