<?php
	$quiz_id = $_GET['quiz_id'] ?? '';
?>

<?php include("includes/header.php"); ?>
<?php include("includes/sidebar.php"); ?>

<div class="page-wrapper">

	<!-- Breadcrumb -->
	<div class="page-breadcrumb">
		<div class="row">
			<div class="col-12 d-flex align-items-center justify-content-between">
				<h4 class="page-title">Quiz</h4>

				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="#">Quiz</a></li>
						<li class="breadcrumb-item active">Start Quiz</li>
					</ol>
				</nav>
			</div>
		</div>
	</div>

	<div class="container-fluid">

		<div class="row justify-content-center">

			<div class="col-md-8">

				<div class="card instruction-card shadow-sm">

                    <div class="card-body p-4">

                        <!-- QUIZ HEADER -->
                        <div class="quiz-header text-center mb-4">

                          <h3 class="quiz-title" id="quizTitle">Loading...</h3>

                            <div class="quiz-meta">
                                <span><i class="mdi mdi-clock-outline"></i> 
                                    <span id="quizDuration">0</span> Minutes
                                </span>

                                <span><i class="mdi mdi-help-circle"></i> 
                                    <span id="quizQuestions">0</span> Questions
                                </span>

                                <span><i class="mdi mdi-star"></i> 
                                    <span id="quizMarks">0</span> Marks
                                </span>
                            </div>

                        </div>

                        <!-- INSTRUCTIONS -->
                        <div class="instruction-section">

                            <h5 class="section-title">
                                <i class="mdi mdi-information-outline"></i> Instructions
                            </h5>

                            <ul class="instruction-list">
                                <li>Timer starts immediately after clicking start</li>
                                <li>Each question has equal marks</li>
                                <li>Do not refresh or close the browser</li>
                                <li>Submit before time expires</li>
                                <li>Use navigation to move between questions</li>
                            </ul>

                        </div>

                        <!-- DECLARATION -->
                        <div class="declaration-card mt-4">

                            <label class="d-flex align-items-start gap-2">

                                <input type="checkbox" id="declaration">

                                <span>
                                    I have read all instructions and agree to follow the rules.
                                </span>

                            </label>

                        </div>

                        <!-- BUTTON -->
                        <div class="text-center mt-4">

                            <button class="btn btn-primary px-4" id="startBtn">
                                <i class="mdi mdi-play-circle-outline"></i> Start Quiz
                            </button>

                        </div>

                    </div>
                    </div>

			</div>

		</div>

	</div>

</div>

<?php include("includes/footer.php"); ?>

<script>

	let quiz_id = "<?php echo $quiz_id; ?>";
	let api_url = "<?php echo $api_url; ?>";

    $(document).ready(function(){
        loadQuizDetails();
    });


    function loadQuizDetails(){

        $.get(api_url + "quiz/getquizdetails.php", {
            quiz_id: quiz_id
        }, function(res){

            if(res.status !== "success"){
                alert(res.message);
                return;
            }

            let q = res.data;

            $("#quizTitle").text(q.title);
            $("#quizDuration").text(q.duration);
            $("#quizQuestions").text(q.total_questions);
            $("#quizMarks").text(q.total_marks);

        }, "json");

    }

	/* START QUIZ */

    $("#startBtn").prop("disabled", true);

    $("#declaration").on("change", function(){
        $("#startBtn").prop("disabled", !this.checked);
    });

	$("#startBtn").click(function(){

		if(!quiz_id){
			alert("Invalid Quiz ID");
			return;
		}

		if(!$("#declaration").is(":checked")){
			alert("Please accept the declaration before starting the quiz.");
			return;
		}


		$.post(api_url + "quiz/startquiz.php", {
			quiz_id: quiz_id
		}, function(res){

			if(res.status == "success"){

				window.location.href = "quizpage.php?attempt_id=" + res.attempt_id;

			}else{

				alert(res.message || "Something went wrong");

				$("#startBtn").prop("disabled", false).text("Start Test");
			}

		}, "json").fail(function(){

			alert("Server error. Please try again.");

			$("#startBtn").prop("disabled", false).text("Start Test");
		});

	});





</script>
