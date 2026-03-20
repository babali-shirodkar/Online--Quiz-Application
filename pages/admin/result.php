<?php 
$attempt_id = $_GET['attempt_id'] ?? 0;
?>

<?php include("includes/header.php"); ?>
<?php include("includes/sidebar.php"); ?>

<style>



.result-summary{
border:1px solid #e6e9ef;
border-radius:8px;
padding:15px;
background:#fff;
}

.summary-row{
display:flex;
justify-content:space-between;
text-align:center;
flex-wrap:wrap;
}

.summary-box{
flex:1;
min-width:120px;
}

.summary-title{
font-size:16px;
color: #343a40;
}

.summary-value{
font-size:20px;
font-weight:600;
color:#7460ee;
}


.progress{
height:10px;
border-radius:10px;
}



#questionPalette{
display:flex;
flex-wrap:wrap;
gap:6px;
}

.palette-btn{
width:32px;
height:32px;
border-radius:5px;
font-size:13px;
cursor:pointer;
}


.palette-correct{ 
background:#28b779; 
color:#fff; 

}
.palette-wrong{ 
background:#da542e; 
color:#fff; 
}
.palette-notanswered{ 
background:#fff; 
border:1px solid #ccc; 
color:#000; 
}

.palette-btn.active{
border:2px solid #0d6efd;
}

.accordion-button{
padding:8px;
font-size:14px;
}

.accordion-body{
padding:10px;
font-size:13px;
}

.option-default{
border:1px solid #ddd;
padding:8px;
border-radius:5px;
margin-bottom:6px;
}

.option-correct{
background:#e9f8f1;
border:1px solid #28b779;
}

.option-wrong{
background:#fdecea;
border:1px solid #da542e;
}

</style>

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

$("#scoreMarks").text(correct+" / "+total);
$("#correctQ").text(correct);
$("#wrongQ").text(wrong);
$("#skippedQ").text(skipped);
$("#accuracy").text(percent+"%");
$("#progressBar").css("width",percent+"%");



let palette="";

res.questions.forEach(function(q,index){

let color="palette-skipped";

if(q.status === "correct")
    color="palette-correct";

else if(q.status === "wrong")
    color="palette-wrong";

palette+=`
<button class="palette-btn ${color}" 
onclick="scrollToQuestion(${index}, this)">
${index+1}
</button>
`;

});

$("#questionPalette").html(palette);



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

Q${index+1} ${statusBadge}

</button>

</h2>

<div id="collapse${index}" class="accordion-collapse collapse">

<div class="accordion-body">

<p class="mb-3 fw-bold">${q.question_text}</p>

`;

q.options.forEach(function(op){

let className="option-default";

if(parseInt(op.is_correct) === 1){
    className="option-default option-correct";
}


if(q.user_selected.includes(op.id) && parseInt(op.is_correct) === 0){
    className="option-default option-wrong";
}

html+=`

<div class="${className}">

${op.option_text}

${q.user_selected.includes(op.id) 
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