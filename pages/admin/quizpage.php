
<?php include("includes/header.php"); ?>
<?php include("includes/sidebar.php"); ?>


<div class="page-wrapper">
<div class="container-fluid">

<!-- HEADER -->

<div class="quiz-header">

<div class="quiz-title" id="quizTitle">
    
</div>

<div class="timer-box">
<span>Time Left</span>
<div id="timer">00:00</div>
</div>

</div>


<div class="row">

<!-- QUESTION PANEL -->

<div class="col-md-9 quiz-left">

<div class="card">
<div class="card-body quiz-card-body">

<div class="d-flex justify-content-between align-items-center">

<h5 id="qtitle"></h5>

<div class="marks-box">
Marks : <span id="questionMarks" class="badge bg-success"></span>
</div>

</div>

<hr>

<div id="questionBox"></div>

<hr>


<div class="d-flex justify-content-between">

<div>

<button class="btn btn-warning btn-sm" id="markReviewBtn">
Mark for Review & Next
</button>

<button class="btn btn-secondary btn-sm" id="clearResponseBtn">
Clear Response
</button>

</div>

<div>

<button class="btn btn-success btn-sm text-white" id="saveNextBtn">
Save & Next
</button>

</div>

</div>

</div>
</div>

</div>


<!-- QUESTION PALETTE -->

<div class="col-md-3 quiz-right">

<div class="card">
<div class="card-body quiz-card-body">

<h6 class="fw-bold mb-2">Questions</h6>

<div class="legend">

<div><span class="box answered"></span> Answered</div>
<div><span class="box review"></span> Review</div>
<div><span class="box notanswered"></span> Not Attempted</div>

</div>

<hr>

<div id="questionPalette" class="palette-grid"></div>

<hr>

<div class="quiz-stats">

<p>Total : <span id="totalQ"></span></p>
<p>Attempted : <span id="attemptedQ">0</span></p>
<p>Remaining : <span id="remainingQ"></span></p>
<p>Review : <span id="reviewQ">0</span></p>

</div>

<button class="btn btn-primary btn-sm w-100 mt-2" id="submitQuizBtn">
Submit Test
</button>

<div class="modal fade" id="submitModal" tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">

<div class="modal-header">
<h5 class="modal-title">Submit Test</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<table class="table table-bordered text-center">

<thead>
<tr>
<th>Total Questions</th>
<th>Attempted</th>
<th>Remaining</th>
<th>Marked Review</th>
</tr>
</thead>

<tbody>
<tr>
<td id="modalTotal"></td>
<td id="modalAttempted"></td>
<td id="modalRemaining"></td>
<td id="modalReview"></td>
</tr>
</tbody>

</table>

<p class="text-danger text-center mt-2">
Are you sure you want to submit the test?
</p>

</div>

<div class="modal-footer">

<button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
Cancel
</button>

<button class="btn btn-primary btn-sm" id="confirmSubmit">
Submit Test
</button>

</div>

</div>
</div>
</div>

</div>
</div>

</div>

</div>

</div>
</div>

<?php include("includes/footer.php"); ?>

<style>
</style>


<script>

let api_url = "<?php echo $api_url; ?>";
let attempt_id = "<?php echo $_GET['attempt_id']; ?>";

let questions=[];
let currentIndex=0;
let answers={};
let reviewQuestions=[];
let timerInterval=null;

$(document).ready(function(){
loadQuestions();
});


/* ================= LOAD QUESTIONS ================= */

function loadQuestions(){

$.get(api_url+"question/getquizquestions.php?attempt_id="+attempt_id,function(res){

questions=res.questions;

/* QUIZ TITLE */
$("#quizTitle").text(res.quiz_name);

/* LOAD SAVED INDEX (RESUME) */
let savedIndex = localStorage.getItem("quiz_current_index_"+attempt_id);
if(savedIndex){
    currentIndex = parseInt(savedIndex);
}

/* LOAD SAVED ANSWERS FROM API */
questions.forEach(q=>{
    if(q.saved_answers && q.saved_answers.length>0){
        answers[q.id] = q.saved_answers;
    }
});

/* COUNTS */
$("#totalQ").text(questions.length);
updateCount();

/* CREATE UI */
createPalette();
loadQuestion();

/* TIMER RESUME */
startTimer(res.duration);

},"json");

}


/* ================= CREATE PALETTE ================= */

function createPalette(){

let html="";

questions.forEach((q,i)=>{

let cls = "notanswered";

if(answers[q.id]) cls = "answered";
if(reviewQuestions.includes(q.id)) cls = "review";

html+=`<div class="qbox ${cls}" onclick="jumpQuestion(${i})">${i+1}</div>`;

});

$("#questionPalette").html(html);

}


/* ================= LOAD QUESTION ================= */

function loadQuestion(){

localStorage.setItem("quiz_current_index_"+attempt_id,currentIndex);

let q = questions[currentIndex];

$("#qtitle").text("Question " + (currentIndex+1));
$("#questionMarks").text("+" + (q.marks ?? 0));

let html = `<p>${q.question_text}</p>`;

/* TRUE FALSE */
if(q.question_type == "truefalse"){

q.options.forEach(op=>{

let checked="";

if(answers[q.id] && answers[q.id].includes(op.id.toString())){
checked="checked";
}

html += `
<div class="option-container">
<input type="radio" name="option_${q.id}" value="${op.id}" ${checked}>
<div class="option-row">${op.option_text}</div>
</div>
`;
});
}

/* MCQ / MULTISELECT */
else{

let inputType = (q.question_type == "multiselect") ? "checkbox" : "radio";

q.options.forEach(op=>{

let checked="";

if(answers[q.id] && answers[q.id].includes(op.id.toString())){
checked="checked";
}

html += `
<div class="option-container">
<input type="${inputType}" name="option_${q.id}" value="${op.id}" ${checked}>
<div class="option-row">${op.option_text}</div>
</div>
`;
});
}

$("#questionBox").html(html);

updatePalette();

}


$(document).on("click",".option-row",function(){
$(this).prev("input").prop("checked",true).trigger("change");
});


/* ================= UPDATE PALETTE ================= */

function updatePalette(){

$(".qbox").removeClass("current");
$(".qbox").eq(currentIndex).addClass("current");

}


/* ================= SAVE ANSWER ================= */

function saveAnswer(){

let selected=[];

$("#questionBox input:checked").each(function(){
selected.push($(this).val());
});

if(selected.length>0){

answers[questions[currentIndex].id]=selected;

/* SAVE TO DB */
$.post(api_url+"question/savesingleanswer.php",{
    attempt_id:attempt_id,
    question_id:questions[currentIndex].id,
    options:selected
});

/* UPDATE UI */
$(".qbox").eq(currentIndex)
.removeClass("notanswered")
.addClass("answered");

}

updateCount();
}


/* ================= CLEAR RESPONSE ================= */

$("#clearResponseBtn").click(function(){

delete answers[questions[currentIndex].id];

$("#questionBox input").prop("checked",false);

$.post(api_url+"question/savesingleanswer.php",{
    attempt_id:attempt_id,
    question_id:questions[currentIndex].id,
    options:[]
});

$(".qbox").eq(currentIndex)
.removeClass("answered review")
.addClass("notanswered");

updateCount();

});


/* ================= MARK REVIEW ================= */

$("#markReviewBtn").click(function(){

let qid=questions[currentIndex].id;

if(!reviewQuestions.includes(qid)){
reviewQuestions.push(qid);
}

$(".qbox").eq(currentIndex)
.removeClass("answered notanswered")
.addClass("review");

if(currentIndex < questions.length-1){
currentIndex++;
loadQuestion();
}

updateCount();

});


/* ================= SAVE NEXT ================= */

$("#saveNextBtn").click(function(){

saveAnswer();

if(currentIndex < questions.length-1){
currentIndex++;
loadQuestion();
}

});


function jumpQuestion(i){
currentIndex=i;
loadQuestion();
}


/* ================= COUNT ================= */

function updateCount(){

let attempted=Object.keys(answers).length;

$("#attemptedQ").text(attempted);
$("#remainingQ").text(questions.length-attempted);
$("#reviewQ").text(reviewQuestions.length);

}


/* ================= TIMER (RESUME) ================= */

function startTimer(minutes){

let totalTime = minutes*60;

/* GET SAVED START TIME */
let savedStart = localStorage.getItem("quiz_start_time_"+attempt_id);

if(!savedStart){
    savedStart = Math.floor(Date.now()/1000);
    localStorage.setItem("quiz_start_time_"+attempt_id,savedStart);
}

clearInterval(timerInterval);

timerInterval = setInterval(function(){

let now = Math.floor(Date.now()/1000);
let elapsed = now - savedStart;

let remaining = totalTime - elapsed;

if(remaining <= 0){
    clearInterval(timerInterval);
    submitQuiz();
    return;
}

let m = Math.floor(remaining/60);
let s = remaining%60;

$("#timer").text(
String(m).padStart(2,'0') + ":" +
String(s).padStart(2,'0')
);

},1000);

}


/* ================= SUBMIT POPUP ================= */

$("#submitQuizBtn").click(function(){

let total = questions.length;
let attempted = Object.keys(answers).length;
let review = reviewQuestions.length;
let remaining = total - attempted;

$("#modalTotal").text(total);
$("#modalAttempted").text(attempted);
$("#modalRemaining").text(remaining);
$("#modalReview").text(review);

let modal = new bootstrap.Modal(document.getElementById('submitModal'));
modal.show();

});


/* ================= FINAL SUBMIT ================= */

$("#confirmSubmit").click(function(){

saveAnswer();

$.post(api_url+"question/saveanswer.php",{
    attempt_id:attempt_id
},function(res){

if(res.status=="success"){

/* CLEAR STORAGE AFTER SUBMIT */
localStorage.removeItem("quiz_start_time_"+attempt_id);
localStorage.removeItem("quiz_current_index_"+attempt_id);

alert("Quiz Submitted Successfully");

window.location.href="result.php?attempt_id="+res.attempt_id;

}

},"json");

});


/* AUTO SUBMIT FUNCTION */
function submitQuiz(){
$("#confirmSubmit").click();
}

</script>
