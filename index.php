<?php 
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="keywords" content="wrappixel, admin dashboard, html css dashboard, web dashboard, bootstrap 5 admin, bootstrap 5, css3 dashboard, bootstrap 5 dashboard, Matrix lite admin bootstrap 5 dashboard, frontend, responsive bootstrap 5 admin template, Matrix admin lite design, Matrix admin lite dashboard bootstrap 5 dashboard template" />
    <meta name="description" content="Matrix Admin Lite Free Version is powerful and clean admin dashboard template, inpired from Bootstrap Framework" />
    <meta name="robots" content="noindex,nofollow" />
    <title>Quiz & Assessment Application</title>
    <!-- Favicon icon -->
   <link rel="icon" type="image/png" sizes="16x16" href="https://demos.wrappixel.com/free-admin-templates/bootstrap/matrix-bootstrap-free/assets/images/favicon.png"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="assets/libs/fullcalendar/dist/fullcalendar.min.css" rel="stylesheet" />
    <link href="assets/extra-libs/calendar/calendar.css" rel="stylesheet" />
    <link href="dist/css/style.min.css" rel="stylesheet" />
     <link href="dist/css/customstyle.css" rel="stylesheet" />

    <style>
        
        .feature-icon {
            font-size: 2rem;
            margin-bottom: 15px;
        }
        .hero-stats h4 { margin-bottom: 0; }
       
        footer a { color: rgba(255,255,255,0.7); text-decoration: none; }
        footer a:hover { color: #fff; }
    </style>
</head>

<body class="lp-custom-row">

<div id="main-wrapper">

    <!--  HEADER  -->
    <header class="py-3 bg-white shadow-sm fixed-top">
        <div class="container">
            <nav class="navbar navbar-expand-md navbar-light px-0">
                <a class="navbar-brand" href="#">
                    <img src="assets/images/logo-icon.png" alt="logo"/>
                    <span class="ml-2 font-weight-bold">QuizApp</span>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                        <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                        <li class="nav-item"><a class="nav-link" href="#explore">Explore Quizzes</a></li>
                        <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                        <li class="nav-item ms-3">
                            <a href="login.php" class="btn btn-theme">Login</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>

   

    <section id="home" class="spacer bg-light" style="padding-top: 120px;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="mb-3">
                       <span class=" badge bg-white text-muted border mr-2 icon-green"><i class="fas fa-check-circle mr-1"></i> Secure</span>
                        <span class="badge bg-white text-muted border mr-2 icon-orange"><i class="fas fa-bolt mr-1"></i> Fast</span>
                        <span class="badge bg-white text-muted border mr-2 icon-blue"><i class="fas fa-clock mr-1"></i> Real-time</span>
                    </div>

                    <h1 class="text-dark font-weight-bold mb-3 display-4">
                            Smart Online Quiz <br> & Assessment Platform
                    </h1>

                        <p class="font-16 text-muted mb-4">
                            Practice, test, and improve your skills with real-time assessments. 
                            Get instant results, track performance, and grow faster with our 
                            powerful and easy-to-use quiz system.
                        </p>

                        <a href="login.php" class="btn btn-theme btn-lg px-4">
                            Get Started
                        </a>

                </div>
            </div>
        </div>
    </section>

    <section id="features" class="spacer">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-md-8 text-center">
                    <h5 class="text-info font-weight-bold text-uppercase">Why Choose QuizApp</h5>
                    <h2 class="text-dark font-weight-bold">Powerful Features</h2>
                    <p class="text-muted">Everything you need to conduct professional assessments</p>
                </div>
            </div>

            <div class="row">

                <div class="col-md-3 text-center mb-4">
                    <div class="p-4 border rounded h-100 shadow-sm bg-white feature-card">
                        <div class="feature-icon icon-blue">
                            <i class="fas fa-stopwatch"></i>
                        </div>
                        <h5 class="font-weight-bold">Timed Quizzes</h5>
                        <p class="text-muted">Real-time countdown ensures fair and time-bound assessments.</p>
                    </div>
                </div>

                <div class="col-md-3 text-center mb-4">
                    <div class="p-4 border rounded h-100 shadow-sm bg-white feature-card">
                        <div class="feature-icon icon-green">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h5 class="font-weight-bold">Instant Results</h5>
                        <p class="text-muted">Get scores immediately with detailed performance insights.</p>
                    </div>
                </div>

                <div class="col-md-3 text-center mb-4">
                    <div class="p-4 border rounded h-100 shadow-sm bg-white feature-card">
                        <div class="feature-icon icon-green">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h5 class="font-weight-bold">Secure System</h5>
                        <p class="text-muted">Advanced protection ensures safe and reliable exams.</p>
                    </div>
                </div>

                <div class="col-md-3 text-center mb-4">
                    <div class="p-4 border rounded h-100 shadow-sm bg-white feature-card">
                        <div class="feature-icon icon-orange">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <h5 class="font-weight-bold">Multi Categories</h5>
                        <p class="text-muted">Supports technical, aptitude, and general knowledge quizzes.</p>
                    </div>
                </div> 
               
            </div>
        </div>
    </section>

    <section id="about" class="spacer bg-light">
        <div class="container">
            <div class="row align-items-center">

                <!-- LEFT CONTENT -->
                <div class="col-md-6">

                    <h5 class="text-uppercase font-weight-bold" style="color:var(--primary);">
                        About Us
                    </h5>

                    <h2 class="text-dark font-weight-bold mb-3">
                        Smart & Scalable Assessment Platform
                    </h2>

                    <p class="text-muted mb-3">
                    Our platform is built to simplify the way quizzes and assessments are conducted. 
                    Whether you are an instructor creating tests or a participant improving skills, 
                    we provide a seamless and efficient experience.
                    </p>

                    <p class="text-muted mb-4">
                    With features like real-time evaluation, smart navigation, and instant feedback, 
                    we ensure a smooth testing journey for users at every level.
                    </p>

                    <ul class="list-unstyled text-muted">
                    <li><i class="fas fa-check text-success mr-2"></i> Easy quiz creation</li>
                    <li><i class="fas fa-check text-success mr-2"></i> Instant performance tracking</li>
                    <li><i class="fas fa-check text-success mr-2"></i> Secure and scalable system</li>
                    </ul>

                    <a href="#contact" class="btn btn-theme mt-3">
                        Contact Us
                    </a>

                </div>

                <!-- RIGHT IMAGE -->
                <div class="col-md-6 mt-4 mt-md-0">

                    <div class="about-img-box">
                        <img src="assets/images/quiz.jpg" 
                            class="img-fluid rounded shadow-sm">
                    </div>

                </div>

            </div>
        </div>
    </section>

<!-- Explore Quizzez -->

    <section id="explore" class="spacer">
    <div class="container">

        <div class="row mb-4">
            <div class="col-md-12 text-center">
                <h2 class="font-weight-bold">Explore Quizzes</h2>
                <p class="text-muted">Try available quizzes</p>
            </div>
        </div>

        <div class="row">

            <!-- LEFT: CATEGORY -->
            <div class="col-md-3">

                <h5 class="mb-3">Categories</h5>

                <div id="homeCategoryContainer" class="list-group">
                    <button class="list-group-item active" data-category="all">
                        All
                    </button>
                </div>

            </div>

            <!-- RIGHT: QUIZZES -->
            <div class="col-md-9">

                <div class="row" id="homeQuizContainer"></div>

            </div>

        </div>

    </div>
</section>
 

    <section id="contact" class="spacer">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center mb-4">
                    <h5 class="text-info font-weight-bold text-uppercase">Get In Touch</h5>
                    <h2 class="font-weight-bold">Contact Us</h2>
                    <p class="text-muted">Have questions? We are here to help.</p>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-8">
                    <form class="bg-light p-4 rounded border" id="ContactForm">
                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label class="font-weight-bold text-dark">Your Name</label>
                                <input type="text"  id= "name" class="form-control" placeholder="Manilk malhodra">
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label class="font-weight-bold text-dark">Email Address</label>
                                <input type="email" id= "email" class="form-control" placeholder="youremail@example.com">
                            </div>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label class="font-weight-bold text-dark">Message</label>
                            <textarea class="form-control" id= "message" rows="5" placeholder="How can we help you?"></textarea>
                        </div>

                        <div class="text-center mt-3">
                           <button  type="submit" class="btn btn-theme btn-lg px-5">Send Message</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

   <footer class="bg-dark text-white pt-5 pb-3 footer-custom">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h4 class="font-weight-bold mb-3">QuizApp</h4>
                    <p class="text-muted">
                        A modern platform for online quizzes, assessments, and skill evaluation.
                        Fast, secure, and easy to use for everyone.
                    </p>
                </div>

           
                <div class="col-md-2 mb-4">
                    <h5 class="mb-3">Quick Links</h5>
                    <ul class="list-unstyled footer-links">
                        <li><a href="#home">Home</a></li>
                        <li><a href="#features">Features</a></li>
                        <li><a href="#about">About</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>

                <!-- ACCOUNT -->
                <div class="col-md-2 mb-4">
                    <h5 class="mb-3">Account</h5>
                    <ul class="list-unstyled footer-links">
                        <li><a href="pages/login.php">Login</a></li>
                        <li><a href="pages/register.php">Register</a></li>
                        <li><a href="#">Help Center</a></li>
                    </ul>
                </div>

                <!-- CONTACT DETAILS -->
                <div class="col-md-4 mb-4">
                    <h5 class="mb-3">Contact Us</h5>

                    <p class="text-muted mb-2">
                        <i class="fas fa-phone-alt me-2 text-info"></i>
                        +91 98765 43210
                    </p>

                    <p class="text-muted mb-2">
                        <i class="fas fa-envelope me-2 text-info"></i>
                        quizapp@quiz.com
                    </p>

                    <p class="text-muted">
                        <i class="fas fa-map-marker-alt me-2 text-info"></i>
                        dodamarg, Maharashtra, India
                    </p>

                </div>

            </div>

                <!-- DIVIDER -->
                <hr class="bg-secondary opacity-25">

                <!-- COPYRIGHT -->
                <div class="text-center text-muted small">
                    © 2026 QuizApp. All Rights Reserved. | Designed by Babali Shirodkar
                </div>
        </div>
    </footer>

</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://demos.wrappixel.com/free-admin-templates/bootstrap/matrix-bootstrap-free/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function(){
            $('a[href^="#"]').on('click', function(event) {
                var target = $(this.getAttribute('href'));
                if( target.length ) {
                    event.preventDefault();
                    $('html, body').stop().animate({
                        scrollTop: target.offset().top -50
                    }, 1000);
                }
            });
    

            $("#ContactForm").submit(function(e){
            e.preventDefault();

            $.post("backend/sendmail.php", {
                name: $("#name").val(),
                email: $("#email").val(),
                message: $("#message").val()
            }, function(res){

                if(res.status === "success"){
                    alert("Message sent successfully!");
                    $("#ContactForm")[0].reset();
                }else{
                    alert(res.message);
                }

            }, "json");
        });



        let api_url = "<?php echo $api_url ?? 'backend/api/'; ?>";

            let homeQuizzes = [];

            /* LOAD ON PAGE */
            $(document).ready(function(){
                loadHomeCategories();
                loadHomeQuizzes();
            });


            /* LOAD CATEGORIES */
            function loadHomeCategories(){

                $.ajax({
                    url: api_url + "category/getcategories.php",
                    type: "GET",
                    dataType: "json",

                    success: function(res){

                        if(res.status === "success"){

                            let html = `
                                <button class="list-group-item active" data-category="all">
                                    All
                                </button>
                            `;

                            res.data.forEach(function(cat){
                                html += `
                                    <button class="list-group-item" data-category="${cat.id}">
                                        ${cat.category_name}
                                    </button>
                                `;
                            });

                            $("#homeCategoryContainer").html(html);
                        }
                    }
                });
            }


            /* LOAD QUIZZES */
            function loadHomeQuizzes(){

                $.ajax({
                    url: api_url + "quiz/getpublishedquizzes.php",
                    type: "GET",
                    dataType: "json",

                    success: function(res){

                        if(res.status === "success"){
                            homeQuizzes = res.data;
                            renderHomeQuizzes(homeQuizzes);
                        }
                    }
                });
            }


            /* RENDER QUIZZES */
            function renderHomeQuizzes(data){

                let html = "";

                if(data.length === 0){
                    html = `<p class="text-center">No quizzes available</p>`;
                }
                else{

                    data.forEach(function(q){

                        html += `
                            <div class="col-md-6 col-lg-4 mb-4">

                                <div class="card shadow-sm h-100">

                                    <div class="card-body">

                                        <h5 class="mb-2">${q.title}</h5>

                                        <div class="mb-2 text-muted small">
                                            <i class="mdi mdi-help-circle-outline text-primary"></i> ${q.total_questions} Questions
                                        </div>

                                        <div class="mb-2 text-muted small">
                                            <i class="mdi mdi-clock-outline text-warning"></i> ${q.duration} Min
                                        </div>

                                        <div class="mb-2 text-muted small">
                                            <i class="mdi mdi-star-outline text-success"></i> ${q.total_marks} Marks
                                        </div>

                                       
                                        <span class="badge bg-light text-dark small">
                                            <i class="mdi mdi-tag-outline"></i> ${q.category_name ?? ''}
                                        </span>

                                        <div class="text-end">
                                            <a href="login.php?redirect=startquiz&quiz_id=${q.quiz_id}" class="btn btn-theme btn-sm">
                                                Start
                                            </a>
                                        </div>

                                    </div>

                                </div>

                            </div>
                        `;
                    });
                }

                $("#homeQuizContainer").html(html);
            }


            /* CATEGORY FILTER */
            $(document).on("click", "#homeCategoryContainer button", function(){

                $("#homeCategoryContainer button").removeClass("active");
                $(this).addClass("active");

                let cat = $(this).data("category");

                if(cat === "all"){
                    renderHomeQuizzes(homeQuizzes);
                }
                else{
                    let filtered = homeQuizzes.filter(q => q.category_id == cat);
                    renderHomeQuizzes(filtered);
                }

            });
    });

</script>

</body>
</html>