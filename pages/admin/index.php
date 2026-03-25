<?php include("includes/header.php"); ?>
<?php include("includes/sidebar.php"); ?>

<div class="page-wrapper">

    <!-- Breadcrumb -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title">Dashboard</h4>
                <div class="ms-auto text-end">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Container -->
    <div class="container-fluid">

        <!-- Dynamic Cards -->
        <div class="row" id="dashboardCards"></div>

    </div>

</div>

<?php include("includes/footer.php"); ?>

<script>

let api_url = "<?php echo $api_url; ?>";

$(document).ready(function(){
    loadDashboard();
});

/* LOAD DASHBOARD */

function loadDashboard(){

    $.ajax({
        url: api_url + "quiz/getdashboard.php",
        type: "GET",
        dataType: "json",

        success: function(res){

            console.log(res);

            if(res.status !== "success"){
                alert(res.message);
                return;
            }

            let role = res.role;
            let d = res.data;
            let html = "";

            /*  PARTICIPANT  */

            if(role === "participant"){

                html += `
                <div class="col-md-6 col-lg-3">
                    <div class="card card-hover">
                        <div class="box bg-success text-center">
                            <h2 class="text-white">${d.total_quizzes}</h2>
                            <h6 class="text-white">Total Quizzes</h6>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card card-hover">
                        <div class="box bg-info text-center">
                            <h2 class="text-white">${d.attempted_quizzes}</h2>
                            <h6 class="text-white">Attempted Quizzes</h6>
                        </div>
                    </div>
                </div>
                `;
            }

            /*  INSTRUCTOR  */

            else if(role === "instructor"){

                html += `
                <div class="col-md-6 col-lg-3">
                    <div class="card card-hover">
                        <div class="box bg-primary text-center">
                            <h2 class="text-white">${d.total}</h2>
                            <h6 class="text-white">Total Quizzes</h6>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card card-hover">
                        <div class="box bg-warning text-center">
                            <h2 class="text-white">${d.draft}</h2>
                            <h6 class="text-white">Draft Quizzes</h6>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card card-hover">
                        <div class="box bg-success text-center">
                            <h2 class="text-white">${d.published}</h2>
                            <h6 class="text-white">Published Quizzes</h6>
                        </div>
                    </div>
                </div>
                `;
            }

            /*  ADMIN  */

            else if(role === "admin"){

                html += `
                <div class="col-md-6 col-lg-3">
                    <div class="card card-hover">
                        <div class="box bg-info text-center">
                            <h2 class="text-white">${d.total_instructors}</h2>
                            <h6 class="text-white">Instructors</h6>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card card-hover">
                        <div class="box bg-success text-center">
                            <h2 class="text-white">${d.total_participants}</h2>
                            <h6 class="text-white">Participants</h6>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card card-hover">
                        <div class="box bg-primary text-center">
                            <h2 class="text-white">${d.total_quizzes}</h2>
                            <h6 class="text-white">Total Quizzes</h6>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card card-hover">
                        <div class="box bg-warning text-center">
                            <h2 class="text-white">${d.draft}</h2>
                            <h6 class="text-white">Draft Quizzes</h6>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3 mt-3">
                    <div class="card card-hover">
                        <div class="box bg-success text-center">
                            <h2 class="text-white">${d.published}</h2>
                            <h6 class="text-white">Published Quizzes</h6>
                        </div>
                    </div>
                </div>
                `;
            }

            $("#dashboardCards").html(html);

        },

        error: function(){
            alert("Failed to load dashboard");
        }

    });
}

</script>