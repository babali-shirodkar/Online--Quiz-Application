

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
                        <li class="breadcrumb-item active">Manage Quiz</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

<div class="container-fluid">

<div class="card shadow-sm">

<div class="card-body">

<div class="d-flex justify-content-between align-items-center mb-3">

<h5 class="card-title mb-0">Manage Quizzes</h5>

<a href="createquiz.php" class="btn btn-primary btn-sm">
<i class="mdi mdi-plus"></i> Add New Quiz
</a>

</div>


<div class="row mb-3">

<div class="col-md-4">
<input type="text" id="quizSearch" class="form-control form-control-sm" placeholder="Search quiz">
</div>

<div class="col-md-3">
<select id="categoryFilter" class="form-control form-control-sm">
<option value="">All Categories</option>
</select>
</div>

</div>


<div class="table-responsive">

<table id="quizTable" class="table table-striped table-bordered table-sm">

<thead class="thead-light">

<tr>
<th>Quiz Name</th>
<th>Category</th>
<th>Duration</th>
<th>Questions</th>
<th>Marks</th>
<th>Status</th>
<th class="text-center">Action</th>
</tr>

</thead>

<tbody id="quizBody"></tbody>

</table>

</div>

</div>

</div>

</div>

</div>

<?php include("includes/footer.php"); ?>



<script>

var api_url = "<?php echo $api_url; ?>";

$(document).ready(function(){

loadCategories();
loadQuizzes();

});


function loadQuizzes(){

$.get(api_url+"quiz/getallquiz.php",function(res){

if(res.status!="success") return;

let html="";

res.data.forEach(function(q){

let statusBadge="";

if(q.status=="published"){
statusBadge='<span class="badge bg-success status-badge">Published</span>';
}else{
statusBadge='<span class="badge bg-warning status-badge">Draft</span>';
}

html+=`

<tr data-category="${q.category}">

<td class="quiz-title">${q.title}</td>

<td>${q.category}</td>

<td>${q.duration} min</td>

<td>${q.total_questions}</td>

<td>${q.total_marks}</td>

<td>${statusBadge}</td>

<td class="text-center action-icons">

<a href="editquiz.php?quiz_id=${q.quiz_id}" title="Edit Quiz">
<i class="mdi mdi-pencil text-primary"></i>
</a>

<a href="#" onclick="deleteQuiz(${q.quiz_id})" title="Delete Quiz">
<i class="mdi mdi-delete text-danger"></i>
</a>

</td>

</tr>

`;

});

$("#quizBody").html(html);

$('#quizTable').DataTable({
pageLength:10,
lengthChange:false
});

},"json");

}



function loadCategories(){

$.get(api_url+"category/getcategories.php",function(res){

if(res.status!="success") return;

let html='<option value="">All Categories</option>';

res.data.forEach(function(cat){

html+=`<option value="${cat.category_name}">${cat.category_name}</option>`;

});

$("#categoryFilter").html(html);

},"json");

}



$("#categoryFilter").on("change",function(){

let category=$(this).val();

$("#quizTable tbody tr").each(function(){

if(category=="" || $(this).data("category")==category){
$(this).show();
}else{
$(this).hide();
}

});

});



function publishQuiz(id){

if(!confirm("Publish this quiz?")) return;

$.post(api_url+"quiz/publishquiz.php",{quiz_id:id},function(res){

if(res.status=="success"){
alert("Quiz published successfully");
location.reload();
}

},"json");

}


function deleteQuiz(id){

if(!confirm("Delete this quiz?")) return;

$.post(api_url+"quiz/deletequiz.php",{id:id},function(res){

if(res.status=="success"){
alert("Quiz deleted successfully");
location.reload();
}

},"json");

}

</script>