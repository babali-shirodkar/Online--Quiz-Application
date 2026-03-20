<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Online Quiz Application</title>

    <link rel="icon" type="image/png" sizes="16x16" href="https://demos.wrappixel.com/free-admin-templates/bootstrap/matrix-bootstrap-free/assets/images/favicon.png"/>
    <link href="https://demos.wrappixel.com/free-admin-templates/bootstrap/matrix-bootstrap-free/dist/css/style.min.css" rel="stylesheet"/>
    
   
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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

    <!-- ================= HEADER ================= -->
    <header class="py-3 bg-white shadow-sm fixed-top">
        <div class="container">
            <nav class="navbar navbar-expand-md navbar-light px-0">
                <a class="navbar-brand" href="#">
                    <img src="https://demos.wrappixel.com/free-admin-templates/bootstrap/matrix-bootstrap-free/assets/images/logo-icon.png" alt="logo"/>
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
                        <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                        <li class="nav-item ms-3">
                            <a href="login.php" class="btn btn-info text-white">Login</a>
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
                        <span class="badge bg-white text-muted border mr-2"><i class="fas fa-check-circle mr-1"></i> Secure</span>
                        <span class="badge bg-white text-muted border mr-2"><i class="fas fa-bolt mr-1"></i> Fast</span>
                        <span class="badge bg-white text-muted border"><i class="fas fa-clock mr-1"></i> Real-time</span>
                    </div>

                    <h1 class="text-dark font-weight-bold mb-3 display-4">
                        Online Quiz & <br>Assessment System
                    </h1>

                    <p class="font-16 text-muted mb-4">
                        Attempt quizzes online and get your results instantly. 
                        Our platform allows participants to test their knowledge 
                        through timed quizzes with automatic evaluation.
                    </p>

                    <!-- Stats Row -->
                    <div class="row">
                        <div class="col-3">
                            <h4 class="font-weight-bold text-dark">10k+</h4>
                            <small class="text-muted">Users</small>
                        </div>
                        <div class="col-3">
                            <h4 class="font-weight-bold text-dark">50k+</h4>
                            <small class="text-muted">Quizzes</small>
                        </div>
                        <div class="col-3">
                            <h4 class="font-weight-bold text-dark">99%</h4>
                            <small class="text-muted">Uptime</small>
                        </div>
                    </div>
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
                <!-- Feature 1 -->
                <div class="col-md-3 text-center mb-4">
                    <div class="p-3 border rounded h-100 shadow-sm bg-white">
                        <div class="feature-icon text-info">
                            <i class="fas fa-stopwatch"></i>
                        </div>
                        <h4 class="font-weight-bold">Timed Quizzes</h4>
                        <p class="text-muted">Set specific time limits for exams to ensure fairness and test speed along with accuracy.</p>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="col-md-3 text-center mb-4">
                    <div class="p-3 border rounded h-100 shadow-sm bg-white">
                        <div class="feature-icon text-info">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <h4 class="font-weight-bold">Instant Results</h4>
                        <p class="text-muted">No waiting required. Get detailed scorecards and performance analysis immediately.</p>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="col-md-3 text-center mb-4">
                    <div class="p-3 border rounded h-100 shadow-sm bg-white">
                        <div class="feature-icon text-info">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h4 class="font-weight-bold">Secure Platform</h4>
                        <p class="text-muted">Your data is safe with us. We use advanced encryption to protect user information.</p>
                    </div>
                </div>

                <!-- Feature 4 -->
                <div class="col-md-3 text-center mb-4">
                    <div class="p-3 border rounded h-100 shadow-sm bg-white">
                        <div class="feature-icon text-info">
                            <i class="fas fa-laptop-code"></i>
                        </div>
                        <h4 class="font-weight-bold">Multi-Subject</h4>
                        <p class="text-muted">Supports various categories including Technical, General Knowledge, Aptitude, and more.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="about" class="spacer bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="text-info font-weight-bold text-uppercase">About Us</h5>
                    <h2 class="text-dark font-weight-bold mb-3">Simplifying Assessments</h2>
                    <p class="text-dark font-16 mb-3">
                        The Online Quiz & Assessment Application is designed to make testing efficient and accessible.
                    </p>
                    <p class="text-muted font-16 mb-4">
                        Whether you are a student looking to practice, or an organization looking to hire, our platform offers the flexibility you need. Participants can navigate between questions, review answers, and receive instant feedback.
                    </p>
                    <a href="#contact" class="btn btn-info text-white">Contact Us</a>
                </div>
                <div class="col-md-6 mt-4 mt-md-0">
                     <div class="p-5 bg-white rounded shadow-sm border text-center">
                        <i class="fas fa-users fa-5x text-muted" style="opacity: 0.3"></i>
                        <h4 class="mt-3 text-muted">Assessment Platform</h4>
                    </div>
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
                    <form class="bg-light p-4 rounded border">
                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label class="font-weight-bold text-dark">Your Name</label>
                                <input type="text" class="form-control" placeholder="JManilk  Malohadra">
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label class="font-weight-bold text-dark">Email Address</label>
                                <input type="email" class="form-control" placeholder="youremail@example.com">
                            </div>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label class="font-weight-bold text-dark">Message</label>
                            <textarea class="form-control" rows="5" placeholder="How can we help you?"></textarea>
                        </div>

                        <div class="text-center mt-3">
                            <button class="btn btn-info text-white btn-lg px-5">Send Message</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-white pt-5 pb-4">
        <div class="container">
            <div class="row">
                <!-- Col 1 -->
                <div class="col-md-4 mb-4">
                    <h4 class="text-white font-weight-bold mb-3">
                    QuizApp
                    </h4>
                    <p class="text-muted">
                        A modern solution for online assessments, technical interviews, and general knowledge testing.
                    </p>
                </div>

                <!-- Col 2 -->
                <div class="col-md-2 mb-4">
                    <h5 class="text-white mb-3">Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="#home">Home</a></li>
                        <li><a href="#features">Features</a></li>
                        <li><a href="#about">About Us</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>

                <!-- Col 3 -->
                <div class="col-md-2 mb-4">
                    <h5 class="text-white mb-3">Account</h5>
                    <ul class="list-unstyled">
                        <li><a href="pages/login.php">Login</a></li>
                        <li><a href="pages/register.php">Register</a></li>
                        <li><a href="#">Help Center</a></li>
                    </ul>
                </div>

                <!-- Col 4 -->
                <div class="col-md-4 mb-4">
                    <h5 class="text-white mb-3">Follow Us</h5>
                    <div class="d-flex">
                        <a href="#" class="btn btn-outline-light btn-sm mr-2"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm mr-2"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm mr-2"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm"><i class="fab fa-github"></i></a>
                    </div>
                </div>
            </div>

            <hr class="bg-secondary opacity-25">

            <div class="text-center pt-3 text-muted">
                © 2026 Online Quiz System. All Rights Reserved.
            </div>
        </div>
    </footer>

</div>


<script src="https://demos.wrappixel.com/free-admin-templates/bootstrap/matrix-bootstrap-free/assets/libs/jquery/dist/jquery.min.js"></script>
<script src="https://demos.wrappixel.com/free-admin-templates/bootstrap/matrix-bootstrap-free/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function(){
        $('a[href^="#"]').on('click', function(event) {
            var target = $(this.getAttribute('href'));
            if( target.length ) {
                event.preventDefault();
                $('html, body').stop().animate({
                    scrollTop: target.offset().top - 70 
                }, 1000);
            }
        });
    });
</script>

</body>
</html>