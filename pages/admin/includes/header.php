<?php
include "../../backend/confi/database.php";
include "../../userAccess.php";
?>
<!DOCTYPE html>
<html dir="ltr" lang="en">
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
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $site_url; ?>assets/images/favicon.png" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo $site_url; ?>assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?php echo $site_url; ?>assets/libs/fullcalendar/dist/fullcalendar.min.css" rel="stylesheet" />
    <link href="<?php echo $site_url; ?>assets/extra-libs/calendar/calendar.css" rel="stylesheet" />
    <link href="<?php echo $site_url; ?>dist/css/style.min.css" rel="stylesheet" />
     <link href="<?php echo $site_url; ?>dist/css/customstyle.css" rel="stylesheet" />
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
    
    <!-- Preloader - style you can find in spinners.css -->
    
    <div class="preloader">
      <div class="lds-ripple">
        <div class="lds-pos"></div>
        <div class="lds-pos"></div>
      </div>
    </div>

    <!-- Main wrapper - style you can find in pages.scss -->

    
    <div
      id="main-wrapper"
      data-layout="vertical"
      data-navbarbg="skin5"
      data-sidebartype="full"
      data-sidebar-position="absolute"
      data-header-position="absolute"
      data-boxed-layout="full"
    >
      
  
      <!-- Topbar header - style you can find in pages.scss -->
    
      <header class="topbar" data-navbarbg="skin5">
        <nav class="navbar top-navbar navbar-expand-md navbar-dark">
          <div class="navbar-header" data-logobg="skin5">
            
            <!-- Logo -->
            
            <a class="navbar-brand" href="index.php">
              <!-- Logo icon -->
              <b class="logo-icon ps-2">
                <!-- Dark Logo icon -->
                <img src="<?php echo $site_url; ?>assets/images/logo-icon.png" alt="homepage" class="light-logo" width="25" />
              </b>
              <!--End Logo icon -->
              <!-- Logo text -->
               <span class="ml-2 font-weight-bold">QuizApp</span>
            </a>
    
            <!-- End Logo -->
          
            <!-- Toggle which is visible on mobile only -->
           
            <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)">
              <i class="ti-menu ti-close"></i>
            </a>
          </div>
 
          <!-- End Logo -->
     
          <div class="navbar-collapse collapse" id="navbarSupportedContent" data-navbarbg="skin5">
            
            
            <!-- toggle and nav items -->
            
            <ul class="navbar-nav float-start me-auto">
              <li class="nav-item d-none d-lg-block">
                <a class="nav-link sidebartoggler waves-effect waves-light" href="javascript:void(0)" data-sidebartype="mini-sidebar">
                  <i class="mdi mdi-menu font-24"></i>
                </a>
              </li>

              <!-- Search -->

              
            </ul>

     
            <!-- Right side toggle and nav items -->
           
            <ul class="navbar-nav float-end">

              <li class="nav-item dropdown">

                <a class="nav-link dropdown-toggle text-white d-flex align-items-center"
                  href="#"
                  id="navbarDropdown"
                  role="button"
                  data-bs-toggle="dropdown"
                  aria-expanded="false">

                    <!-- USER IMAGE -->
                    <img src="<?php echo $site_url; ?>assets/images/users/1.jpg"
                        class="rounded-circle me-2"
                        width="35"
                        height="35">

                    <!-- USER NAME -->
                    <span class="d-none d-md-block fw-bold">
                        <?php echo $name; ?>
                    </span>

                </a>

                <!-- DROPDOWN -->
                <ul class="dropdown-menu dropdown-menu-end shadow user-dd">

                    <!-- USER INFO -->
                    <li class="px-3 py-2 border-bottom">
                        <strong><?php echo $name; ?></strong><br>
                        <small class="text-muted"><?php echo $email; ?></small>
                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <li>
                        <a class="dropdown-item" href="<?php echo $site_url; ?>logout.php">
                            <i class="fa fa-power-off me-2"></i> Logout
                        </a>
                    </li>

                </ul>

              </li>

            </ul>
          </div>
        </nav>
      </header>