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

<div class="card instruction-card">

<div class="card-body">

<div class="quiz-header mb-4">

<h3 class="quiz-title">Online Assessment</h3>

<div class="quiz-meta">
<span><i class="mdi mdi-clock-outline"></i> 30 Minutes</span>
<span><i class="mdi mdi-help-circle"></i> 20 Questions</span>
<span><i class="mdi mdi-star"></i> 40 Marks</span>
</div>

</div>

<hr>

<h4 class="section-title">
<i class="mdi mdi-information-outline"></i>
General Instructions
</h4>

<ul class="instruction-list">
<li>Timer will start once you click Start Quiz.</li>
<li>Each question carries equal marks.</li>
<li>Do not refresh or close the browser.</li>
<li>Click Submit before time expires.</li>
<li>Use navigation buttons to move between questions.</li>
</ul>

<h4 class="section-title">
<i class="mdi mdi-file-document-outline"></i>
Declaration
</h4>

<div class="declaration-box">
<input type="checkbox" id="declaration">
<label>
I confirm that I have read all instructions and will follow the rules.
</label>
</div>

<div class="text-end mt-4">

<button class="btn btn-success" id="startBtn">
Start Test
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

/* ================= START QUIZ ================= */

$("#startBtn").click(function(){

    if(!quiz_id){
        alert("Invalid Quiz ID");
        return;
    }

    if(!$("#declaration").is(":checked")){
        alert("Please accept the declaration before starting the quiz.");
        return;
    }

    $(this).prop("disabled", true).text("Starting...");

    $.post(api_url+"quiz/startquiz.php",{
        quiz_id: quiz_id
    },function(res){

        if(res.status=="success"){

            // redirect to quiz page
            window.location.href="quizpage.php?attempt_id="+res.attempt_id;

        }else{

            alert(res.message || "Something went wrong");

            $("#startBtn").prop("disabled", false).text("Start Test");
        }

    },"json").fail(function(){

        alert("Server error. Please try again.");

        $("#startBtn").prop("disabled", false).text("Start Test");
    });

});

</script>