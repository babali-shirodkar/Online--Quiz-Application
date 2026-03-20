<?php

$quiz_id = $_GET['quiz_id'];
?>

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
let quiz_id = "<?php echo $quiz_id ?>";

let questions=[];
let currentIndex=0;
let answers={};
let reviewQuestions=[];

$(document).ready(function(){
loadQuestions();
});


function loadQuestions(){

$.get(api_url+"question/getquizquestions.php?quiz_id="+quiz_id,function(res){

questions=res.questions;



$("#quizTitle").text(res.quiz_name);

$("#totalQ").text(questions.length);
$("#remainingQ").text(questions.length);

createPalette();
loadQuestion();
startTimer(res.duration);

},"json");

}




function createPalette(){

let html="";

questions.forEach((q,i)=>{

html+=`<div class="qbox notanswered" onclick="jumpQuestion(${i})">${i+1}</div>`;

});

$("#questionPalette").html(html);

}




function loadQuestion(){

let q = questions[currentIndex];

$("#qtitle").text("Question " + (currentIndex+1));
$("#questionMarks").text("+" + (q.marks ?? 0));

let html = `<p>${q.question_text}</p>`;




if(q.question_type == "truefalse"){

    q.options.forEach(op=>{

        let checked = "";

        if(answers[q.id] && answers[q.id].includes(op.id.toString())){
            checked = "checked";
        }

        html += `
        <div class="option-container">

            <input type="radio"
            name="option_${q.id}"
            value="${op.id}" ${checked}>

            <div class="option-row">${op.option_text}</div>

        </div>
        `;
    });
}




else{

let inputType = (q.question_type == "multiselect") ? "checkbox" : "radio";

q.options.forEach(op=>{

let checked="";

if(answers[q.id] && answers[q.id].includes(op.id.toString())){
checked="checked";
}

html += `

<div class="option-container">

<input type="${inputType}"
name="option_${q.id}"
value="${op.id}" ${checked}>

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

function updatePalette(){

$(".qbox").removeClass("current");

$(".qbox").eq(currentIndex).addClass("current");

}



function saveAnswer(){

let selected=[];

$("#questionBox input:checked").each(function(){
selected.push($(this).val());
});

if(selected.length>0){

answers[questions[currentIndex].id]=selected;

$(".qbox").eq(currentIndex)
.removeClass("notanswered")
.addClass("answered");

}

updateCount();

}




$("#clearResponseBtn").click(function(){

delete answers[questions[currentIndex].id];

$("#questionBox input").prop("checked",false);

$(".qbox").eq(currentIndex)
.removeClass("answered review")
.addClass("notanswered");

updateCount();

});



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


function updateCount(){

let attempted=Object.keys(answers).length;

$("#attemptedQ").text(attempted);
$("#remainingQ").text(questions.length-attempted);
$("#reviewQ").text(reviewQuestions.length);

}



function startTimer(minutes){

let time=minutes*60;

let timer=setInterval(function(){

let m=Math.floor(time/60);
let s=time%60;

$("#timer").text(

String(m).padStart(2,'0')+":"+
String(s).padStart(2,'0')

);

time--;

if(time<0){

clearInterval(timer);
submitQuiz();

}

},1000);

}


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



$("#confirmSubmit").click(function(){

saveAnswer();

$.post(api_url+"question/saveanswer.php",{

quiz_id:quiz_id,
answers:JSON.stringify(answers)

},function(res){

if(res.status=="success"){

alert("Quiz Submitted Successfully");

window.location.href="result.php?attempt_id="+res.attempt_id;

}

},"json");

});
</script>
