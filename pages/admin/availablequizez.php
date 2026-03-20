
<?php include("includes/header.php"); ?>
<?php include("includes/sidebar.php"); ?>

<div class="page-wrapper">

<div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex align-items-center justify-content-between">
                <h4 class="page-title">Quiz</h4>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">quiz</a></li>
                        <li class="breadcrumb-item active"> Available Quizzez</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
<div class="container-fluid">

<div class="card">

<div class="card-body">


<div class="row mb-4">

<div class="col-md-6">

<input type="text"
id="quizSearch"
class="form-control form-control-sm"
placeholder="Search Quiz">

</div>

</div>


<div class="category-section mb-4">

<h5 class="mb-3">Categories</h5>

<div class="category-scroll" id="categoryContainer">

<button class="category-chip active" data-category="all">
All
</button>

</div>

</div>


<div class="row quiz-container" id="quizContainer">


</div>


<nav class="mt-4">

<ul class="pagination justify-content-center" id="quizPagination"></ul>

</nav>

</div>

</div>

</div>

</div>

<?php include("includes/footer.php"); ?>

<script>

let api_url = "<?php echo $api_url; ?>";

let allQuizzes = [];

$(document).ready(function(){

loadCategories();
loadQuizzes();

});


/*  LOAD CATEGORIES  */

function loadCategories(){

$.get(api_url+"category/getcategories.php",function(res){

if(res.status=="success"){

let html='<button class="category-chip active" data-category="all">All</button>';

res.data.forEach(function(cat){

html+=`<button class="category-chip" data-category="${cat.id}">
${cat.category_name}
</button>`;

});

$("#categoryContainer").html(html);

}

},"json");

}


/*  LOAD QUIZZES  */

function loadQuizzes(){

$.get(api_url+"quiz/getpublishedquizzes.php",function(res){

if(res.status=="success"){

allQuizzes = res.data;

renderQuizzes(allQuizzes);

}

},"json");

}


/*  RENDER QUIZZES  */

function renderQuizzes(data){

let html="";

if(data.length==0){

html='<div class="col-md-12 text-center"><p>No quizzes available</p></div>';

}else{

data.forEach(function(q){

html+=`

<div class="col-md-4 quiz-item" data-category="${q.category_id}">

<div class="card quiz-card mb-4">

<div class="card-body">

<h5 class="quiz-title">${q.title}</h5>

<div class="quiz-info">

<span>${q.total_questions} Questions</span>
<span>${q.duration} Min</span>
<span>${q.total_marks} Marks</span>

</div>

<a href="startquiz.php?quiz_id=${q.quiz_id}"
class="btn btn-purple btn-sm">

Start Now

</a>

</div>

</div>

</div>

`;

});

}

$("#quizContainer").html(html);

}


/*  SEARCH  */

$("#quizSearch").on("keyup",function(){

let value=$(this).val().toLowerCase();

let filtered = allQuizzes.filter(q =>
q.title.toLowerCase().includes(value)
);

renderQuizzes(filtered);

});


/*  CATEGORY FILTER  */

$(document).on("click",".category-chip",function(){

$(".category-chip").removeClass("active");

$(this).addClass("active");

let cat = $(this).data("category");

if(cat=="all"){

renderQuizzes(allQuizzes);

}else{

let filtered = allQuizzes.filter(q => q.category_id == cat);

renderQuizzes(filtered);

}

});

</script>