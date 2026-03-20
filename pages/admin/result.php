<?php 
$attempt_id = $_GET['attempt_id'] ?? 0;
?>

<?php include("includes/header.php"); ?>
<?php include("includes/sidebar.php"); ?>


<div class="page-wrapper">

<div class="page-breadcrumb">
<div class="row">
<div class="col-12 d-flex align-items-center justify-content-between">

<h4 class="page-title">Quiz Result</h4>

<ol class="breadcrumb">
<li class="breadcrumb-item">Quiz</li>
<li class="breadcrumb-item active">Result</li>
</ol>

</div>
</div>
</div>

<div class="container-fluid">



<div class="card mb-3">
<div class="card-body result-summary">

<div class="summary-row">

<div class="summary-box">
<div class="summary-title">Score</div>
<div class="summary-value" id="scoreMarks">0/0</div>
</div>

<div class="summary-box">
<div class="summary-title">Correct</div>
<div class="summary-value text-success" id="correctQ">0</div>
</div>

<div class="summary-box">
<div class="summary-title">Incorrect</div>
<div class="summary-value text-danger" id="wrongQ">0</div>
</div>

<div class="summary-box">
<div class="summary-title">Not Attempted</div>
<div class="summary-value text-secondary" id="skippedQ">0</div>
</div>

<div class="summary-box">
<div class="summary-title">Accuracy</div>
<div class="summary-value" id="accuracy">0%</div>
</div>

</div>

<div class="progress mt-3">
<div id="progressBar"
class="progress-bar progress-bar-striped progress-bar-animated bg-success"
style="width:0%">
</div>
</div>

</div>
</div>



<div class="card">

<div class="card-header bg-dark text-white">
<h6 class="mb-0">Question Palette</h6>
</div>

<div class="card-body">

<div id="questionPalette"></div>

<div class="mt-2 small">
<span class="badge bg-success">Correct</span>
<span class="badge bg-danger">Incorrect</span>
<span class="badge bg-light text-dark border">Not Answered</span>
</div>

</div>
</div>



<div class="card mt-3">

<div class="card-header bg-dark text-white">
<h6 class="mb-0">Question Review</h6>
</div>

<div class="card-body">

<div class="accordion" id="questionReview"></div>

</div>

</div>

</div>
</div>

<?php include("includes/footer.php"); ?>

<script>

var api_url = "<?php echo $api_url; ?>";
var attempt_id = "<?php echo $attempt_id; ?>";

$(document).ready(function(){
    loadResult();
});

function loadResult(){

$.get(api_url+"quiz/getresult.php",{attempt_id:attempt_id},function(res){

if(res.status!="success"){
    alert("Failed to load result");
    return;
}

let s = res.summary;

let total = parseInt(s.total_questions);
let correct = parseInt(s.correct_answers);
let wrong = parseInt(s.wrong_answers);
let skipped = total - correct - wrong;

let percent = total > 0 ? ((correct/total)*100).toFixed(2) : 0;

/* ================= SUMMARY ================= */

$("#scoreMarks").text(correct+" / "+total);
$("#correctQ").text(correct);
$("#wrongQ").text(wrong);
$("#skippedQ").text(skipped);
$("#accuracy").text(percent+"%");
$("#progressBar").css("width",percent+"%");

/* ================= PALETTE ================= */

let palette="";

res.questions.forEach(function(q,index){

let color="palette-notanswered"; // ✅ FIXED

if(q.status === "correct")
    color="palette-correct";

else if(q.status === "wrong")
    color="palette-wrong";

palette+=`
<button class="palette-btn ${color}" 
onclick="scrollToQuestion(${index}, this)">
${q.question_order}
</button>
`;

});

$("#questionPalette").html(palette);


/* ================= QUESTIONS ================= */

let html="";

res.questions.forEach(function(q,index){

let statusBadge="";

if(q.status === "correct")
    statusBadge='<span class="badge bg-success ms-2">Correct</span>';

else if(q.status === "wrong")
    statusBadge='<span class="badge bg-danger ms-2">Incorrect</span>';

else
    statusBadge='<span class="badge bg-secondary ms-2">Skipped</span>';

html+=`

<div class="accordion-item mb-2 shadow-sm border rounded" id="q${index}">

<h2 class="accordion-header">

<button class="accordion-button collapsed fw-semibold"
type="button"
data-bs-toggle="collapse"
data-bs-target="#collapse${index}">

Q${q.question_order} ${statusBadge}

</button>

</h2>

<div id="collapse${index}" class="accordion-collapse collapse">

<div class="accordion-body">

<p class="mb-3 fw-bold">${q.question_text}</p>

`;

q.options.forEach(function(op){

let className="option-default";

/* ✅ FIX TYPE ISSUE */
let isSelected = q.user_selected.includes(parseInt(op.id));

if(parseInt(op.is_correct) === 1){
    className="option-default option-correct";
}

if(isSelected && parseInt(op.is_correct) === 0){
    className="option-default option-wrong";
}

html+=`

<div class="${className}">

${op.option_text}

${isSelected 
    ? '<span class="badge bg-primary ms-2">Your Answer</span>' 
    : ''}

${parseInt(op.is_correct) === 1 
    ? '<span class="badge bg-success ms-2">Correct</span>' 
    : ''}

</div>
`;

});

html+=`</div></div></div>`;

});

$("#questionReview").html(html);

},"json");

}


/* ================= SCROLL ================= */

function scrollToQuestion(index, el){

$(".palette-btn").removeClass("active");
$(el).addClass("active");

$(".accordion-collapse").removeClass("show");
$("#collapse"+index).addClass("show");

$('html,body').animate({
    scrollTop: $("#q"+index).offset().top - 80
},400);

}

</script>