<?php include "backend/confi/database.php" ?>

<!DOCTYPE html>
<html dir="ltr">

<head>

    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Quiz Application - Login</title>

    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon.png" />

    <link href="dist/css/style.min.css" rel="stylesheet" />
    <link href="dist/css/customstyle.css" rel="stylesheet" />

</head>

<body>

    <div class="login-container">

        <div class="login-card">

            <div class="login-logo">

                <img src="assets/images/logo-icon.png">

                <h3 class="login-title">QuizApp Login</h3>

                <p class="text-muted">Welcome Back</p>

            </div>

            <form class="form-horizontal">

                <!-- Email -->
                <div class="form-group mb-3">

                    <label class="font-weight-bold text-dark">Email</label>

                    <div class="input-group">

                        <div class="input-group-prepend">
                            <span class="input-group-text h-100">
                                <i class="mdi mdi-email"></i>
                            </span>
                        </div>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control"
                            placeholder="Enter email"
                            required>

                    </div>

                </div>

                <!-- Password -->
                <div class="form-group mb-4">

                    <label class="font-weight-bold text-dark">Password</label>

                    <div class="input-group">

                        <div class="input-group-prepend">
                            <span class="input-group-text h-100">
                                <i class="mdi mdi-email"></i>
                            </span>
                        </div>


                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control pl-5 pr-5"
                            placeholder="Enter password"
                            required>

                        <div class="input-group-append">
                            <span class="input-group-text h-100" onclick="togglePassword()" style="cursor:pointer;">
                                <i id="toggleIcon" class="mdi mdi-eye"></i>
                            </span>
                        </div>


                    </div>
                </div>


                <!-- Login Button -->
                <div class="d-grid mb-3">

                    <a class="btn btn-info btn-login text-white w-100" id="loginBtn" onclick="loginPage();">
                        Login
                    </a>

                </div>

                <div class="d-flex justify-content-between login-links">

                    <a href="forgot_password.php" class="text-info">
                        Forgot Password?
                    </a>

                    <span class="text-muted">
                        New here?
                        <a href="register.php" class="text-info font-weight-bold">
                            Register
                        </a>
                    </span>

                </div>

            </form>

        </div>

    </div>


    <?php
    include 'pages/admin/includes/footer.php';
    ?>

    <script>
        var api_url = '<?php echo $api_url; ?>';

        function loginPage() {

            let email = $("#email").val().trim();
            let password = $("#password").val();

            if (email === "" || password === "") {
                showMessage("danger", "Please enter email and password");
                return;
            }

            $("#loginBtn").text("Logging in...").prop("disabled", true);

            let loginData = {
                email: email,
                password: password
            };

            $.ajax({

                url: api_url + 'auth/login.php',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(loginData),
                dataType: 'json',

                success: function(response) {

                        console.log(response);

                    if (response.status === "success") {

                        showMessage("success", "Login successful! Redirecting...");

                        setTimeout(function() {

                            window.location.href = "pages/admin/index.php";

                        }, 800);

                    } else {

                        showMessage("danger", response.message);

                    }

                    $("#loginBtn").text("Login").prop("disabled", false);

                },

                error: function() {

                    showMessage("danger", "Server error. Please try again.");
                    $("#loginBtn").text("Login").prop("disabled", false);

                }

            });


        }

        function togglePassword() {

            let input = document.getElementById("password");
            let icon = document.getElementById("toggleIcon");

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("mdi-eye");
                icon.classList.add("mdi-eye-off");
            } else {
                input.type = "password";
                icon.classList.remove("mdi-eye-off");
                icon.classList.add("mdi-eye");
            }

        }

        function showMessage(type, message) {

        $(".alert").remove(); // remove old messages

        let html = `
            <div class="alert alert-${type} mt-2">
                ${message}
            </div>
        `;

        $(".login-card").prepend(html);
    }


        /* ENTER KEY SUPPORT */
        $(document).keypress(function(e) {
            if (e.which == 13) {
                loginPage();
            }
        });
    </script>
</body>

</html>