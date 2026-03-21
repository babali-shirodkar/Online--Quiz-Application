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
                        <li class="breadcrumb-item active">Update Quiz</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="container-fluid">

        <form class="form-horizontal" onsubmit="return false">

            <div class="row">
                <div class="col-md-12">

                    <div class="card mb-4 shadow-sm">

                        <div class="card-body">

                            <h4 class="card-title mb-4">1. Quiz Details</h4>

                           
                            <div class="row mb-3">

                                <div class="col-md-6">
                                    <label class="form-label">Quiz Title</label>
                                    <input type="text"
                                           class="form-control form-control-sm"
                                           id="qtitle"
                                           name='quiz_title'
                                           placeholder="Quiz Title">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Total Marks</label>
                                    <input type="number"
                                           class="form-control form-control-sm"
                                           id="tmarks"
                                           name='tmarks'
                                           placeholder="Total Marks">
                                </div>

                            </div>

                    
                            <div class="row mb-3">

                                <div class="col-md-6">
                                    <label class="form-label">Category</label>

                                    <select class="form-select form-select-sm" id="category" name="category">
                                        <option value="">Select Category</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Duration (Minutes)</label>
                                    <input type="number"
                                           class="form-control form-control-sm"
                                           id="duration"
                                           name='duration'
                                           placeholder="Duration">
                                </div>

                            </div>

                            <div class="row mb-3">

                                <div class="col-md-6">
                                    <label class="form-label">Total Questions</label>
                                    <input type="number"
                                           class="form-control form-control-sm"
                                           id="tques"
                                           name='total_question'
                                           placeholder="Total Questions">
                                </div>

                            </div>

                            <div class="text-end mt-4">

                                <button type="button"
                                        class="btn btn-primary btn-sm px-4"
                                        onclick="updateQuiz()">
                                    Update Quiz
                                </button>

                                <button type="button"
                                        class="btn btn-success btn-sm px-4"
                                        onclick="publishQuiz()">
                                    Publish Quiz
                                </button>

                            </div>

                        </div>
                    </div>


                    <div class="card">

                        <div class="card-body">

                            <div class="d-flex justify-content-between mb-4 border-bottom pb-2">

                                <h4>2. Manage Questions</h4>

                                <button type="button"
                                        class="btn btn-sm btn-primary"
                                        id="addQuestion">
                                    Add Question
                                </button>

                            </div>

                            <div id="questionContainer"></div>


                            <div class="text-end mt-3">
                                <button type="button"
                                        class="btn btn-success"
                                        onclick="saveAllQuestions()">
                                    Save All Questions
                                </button>
                            </div>

                        </div>

                    </div>

                </div>
            </div>

        </form>

    </div>

</div>

<?php include("includes/footer.php"); ?>

<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<script>



let api_url = "<?php echo $api_url; ?>";

let params = new URLSearchParams(window.location.search);

let quiz_id = params.get("quiz_id") || "<?php echo $_GET['quiz_id'] ?? ''; ?>";


$(document).ready(function(){

    if(!quiz_id){
        alert("Quiz ID missing");
        return;
    }

    loadCategories();
    loadQuiz();
    loadQuestions();
    enableDrag();

});


function loadCategories(){

    $.get(api_url+"category/getcategories.php",function(res){

        if(res.status=="success"){

            let html='<option value="">Select Category</option>';

            res.data.forEach(function(cat){

                html += `<option value="${cat.id}">
                            ${cat.category_name}
                         </option>`;

            });

            $("#category").html(html);

        }

    },"json");

}

/*= LOAD QUIZ  */

function loadQuiz(){

    $.get(api_url+"quiz/getquiz.php?quiz_id="+quiz_id,function(res){

        if(res.status==="success"){

            let q=res.quiz;

            $("input[name='quiz_title']").val(q.title);
            $("input[name='tmarks']").val(q.total_marks);
            $("input[name='duration']").val(q.duration);
            $("input[name='total_question']").val(q.total_questions);
            $("input[name='attempt']").val(q.attempt_limit);
            $("select[name='category']").val(q.category_id);

        }

    },"json");

}


/*  LOAD QUESTIONS*/

function loadQuestions(){

    $.get(api_url+"question/getquestions.php?quiz_id="+quiz_id,function(res){

        if(res.status!=="success") return;

        $("#questionContainer").html("");

        res.questions.forEach(function(q){

            let block = addQuestionUI(q);

            block.find(".optionContainer").html("");

            if(q.options){

                q.options.forEach(function(op){
                    addOptionUI(block,q.question_type,op.option_text,op.is_correct);
                });

            }

        });

        updateQuestionNumbers();

    },"json");

}


/*  DRAG */

function enableDrag(){

    $("#questionContainer").sortable({
        handle:".dragHandle",
        update:updateQuestionNumbers
    });

}


/*  QUESTION NUMBERING */

function updateQuestionNumbers(){

    $(".question-block").each(function(i){
        $(this).find(".qnumber").text("Question "+(i+1));
    });

}


/* ADD QUESTION  */

$("#addQuestion").click(function(){

    addQuestionUI();

    updateQuestionNumbers();

});


/*  QUESTION TEMPLATE  */

function addQuestionUI(data=null){

let html=`

<div class="question-block card mb-2">

<input type="hidden" class="question_id" value="${data?data.id:''}">

<div class="card-header d-flex justify-content-between">

<div>
<span class="dragHandle">☰</span>
<strong class="qnumber"></strong>
</div>

<div>
<button type="button" class="btn btn-sm btn-light toggleQ">▼</button>
<button type="button" class="btn btn-sm btn-danger removeQuestion">✖</button>
</div>

</div>

<div class="card-body">

<div class="row g-2 mb-2">

<div class="col-md-8">
<input type="text"
class="form-control form-control-sm qtext"
value="${data?data.question_text:''}"
placeholder="Question">
</div>

<div class="col-md-2">
<input type="number"
class="form-control form-control-sm qmarks"
value="${data?data.marks:1}">
</div>

<div class="col-md-2">

<select class="form-select form-select-sm qtype">

<option value="mcq">MCQ</option>
<option value="multiselect">Multi Select</option>
<option value="truefalse">True False</option>

</select>

</div>

</div>

<div class="optionContainer"></div>

<div class="mt-2">

<button type="button" class="btn btn-sm btn-outline-primary addOption">
+ Option
</button>

</div>

</div>

</div>

`;

$("#questionContainer").append(html);

let block=$("#questionContainer .question-block").last();

if(data){
    block.find(".qtype").val(data.question_type);
}else{
    block.find(".qtype").trigger("change");
}

return block;

}



/* COLLAPSE */

$(document).on("click",".toggleQ",function(){

$(this).closest(".question-block").find(".card-body").toggle();

});


/* REMOVE QUESTION */

$(document).on("click",".removeQuestion",function(){

$(this).closest(".question-block").remove();

updateQuestionNumbers();

});


/* TYPE CHANGE */

$(document).on("change",".qtype",function(){

let block=$(this).closest(".question-block");

let type=$(this).val();

let container=block.find(".optionContainer");

container.html("");

block.find(".addOption").show();

if(type=="truefalse"){

container.append(optionRow(block,"radio","True",true));
container.append(optionRow(block,"radio","False",true));

block.find(".addOption").hide();

}

if(type=="mcq"){

container.append(optionRow(block,"radio",""));
container.append(optionRow(block,"radio",""));

}

if(type=="multiselect"){

container.append(optionRow(block,"checkbox",""));
container.append(optionRow(block,"checkbox",""));

}

});


/* OPTION ROW */

function optionRow(block,type,text="",readonly=false){

let name="correct_"+block.index();

let ro=readonly?"readonly":"";

return `

<div class="d-flex align-items-center mb-1 option-row" style="gap:6px">

<input type="${type}" name="${name}" class="correctOption">

<input type="text"
class="form-control form-control-sm optionText"
style="width:400px"
value="${text}" ${ro}
placeholder="Option">

<button type="button"
class="btn btn-sm btn-outline-danger removeOption">
✖
</button>

</div>

`;

}


/* ADD OPTION */

$(document).on("click",".addOption",function(){

let block=$(this).closest(".question-block");

let type=block.find(".qtype").val()=="multiselect"?"checkbox":"radio";

block.find(".optionContainer").append(optionRow(block,type));

});


/* REMOVE OPTION */

$(document).on("click",".removeOption",function(){

let container=$(this).closest(".optionContainer");

if(container.find(".option-row").length<=2){
alert("Minimum 2 options required");
return;
}

$(this).closest(".option-row").remove();

});


/* LOAD EXISTING OPTION */

function addOptionUI(block,type,text,correct){

let inputType=type=="multiselect"?"checkbox":"radio";

let row=$(optionRow(block,inputType,text));

if(correct==1){
row.find(".correctOption").prop("checked",true);
}

block.find(".optionContainer").append(row);

}


/* SAVE QUESTION */

function saveAllQuestions(){

    let allQuestions = [];

    let isValid = true;

    $(".question-block").each(function(){

        let block = $(this);

        let question_id = block.find(".question_id").val();
        let question_text = block.find(".qtext").val().trim();
        let marks = block.find(".qmarks").val();
        let type = block.find(".qtype").val();

        if(question_text === ""){
            alert("Question text cannot be empty");
            isValid = false;
            return false;
        }

        let options = [];

        block.find(".option-row").each(function(){

            let text = $(this).find(".optionText").val().trim();
            let correct = $(this).find(".correctOption").prop("checked") ? 1 : 0;

            if(text !== ""){
                options.push({
                    option_text: text,
                    is_correct: correct
                });
            }

        });

        if(options.length < 2){
            alert("Each question must have at least 2 options");
            isValid = false;
            return false;
        }

        let correctCount = options.filter(op => op.is_correct === 1).length;

        if(correctCount === 0){
            alert("Each question must have at least one correct answer");
            isValid = false;
            return false;
        }

        allQuestions.push({
            quiz_id: quiz_id,
            question_id: question_id,
            question_text: question_text,
            marks: marks,
            question_type: type,
            options: options
        });

    });

    if(!isValid) return;

    $.ajax({

        url: api_url + "question/savequestion.php",
        type: "POST",
        contentType: "application/json",
        data: JSON.stringify({questions: allQuestions}),
        dataType: "json",

        success: function(res){

            if(res.status === "success"){
                alert("All questions saved successfully");

                loadQuestions();
            }else{
                alert(res.message);
            }

        },

        error: function(){
            alert("Server error while saving questions");
        }

    });

}


/* UPDATE QUIZ */

function updateQuiz(){

let data={

quiz_id:quiz_id,
title:$("input[name='quiz_title']").val(),
total_marks:$("input[name='tmarks']").val(),
duration:$("input[name='duration']").val(),
total_questions:$("input[name='total_question']").val(),
attempt_limit:$("input[name='attempt']").val(),
category_id:$("select[name='category']").val()

};

$.ajax({

url:api_url+"quiz/updatequiz.php",
type:"POST",
contentType:"application/json",
data:JSON.stringify(data),
dataType:"json",

success:function(res){

if(res.status=="success"){
alert("Quiz Updated");
}else{
alert(res.message);
}

}

});

}


/* PUBLISH QUIZ */

function publishQuiz(){

let requiredQuestions = parseInt($("input[name='total_question']").val());

$.get(api_url+"question/getquestions.php?quiz_id="+quiz_id,function(res){

if(res.status!="success"){
alert("Unable to verify questions");
return;
}

let currentQuestions = parseInt(res.questions.length);



if(currentQuestions < requiredQuestions){

alert("You must add "+requiredQuestions+" questions before publishing.\nCurrently added: "+currentQuestions);

return;

}



let invalidQuestion=false;

res.questions.forEach(function(q){

if(!q.options || q.options.length < 2){
invalidQuestion=true;
}

let correctCount=0;

q.options.forEach(function(op){
if(op.is_correct==1) correctCount++;
});

if(correctCount==0){
invalidQuestion=true;
}

});

if(invalidQuestion){

alert("Each question must have at least 2 options and one correct answer");

return;

}


/* CALL PUBLISH API */

$.ajax({

url:api_url+"quiz/publishquiz.php",
type:"POST",
contentType:"application/json",
data:JSON.stringify({quiz_id:quiz_id}),
dataType:"json",

success:function(res){

if(res.status=="success"){

alert("Quiz Published Successfully");

location.reload();

}else{

alert(res.message);

}

},

error:function(){
alert("Server error while publishing quiz");
}

});

},"json");

}

</script>