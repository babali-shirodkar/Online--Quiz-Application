

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

<div class="col-md-3" id="createdByFilterBox">
<select id="instructorFilter" class="form-control form-control-sm">
<option value="">Created By</option>
</select>
</div>

<div class="col-md-2">
<button onclick="resetFilters()" class="btn btn-secoundary btn-sm">
<i class="mdi mdi-refresh"></i> Reset
</button>
</div>

</div>


<div class="table-responsive">

<table id="quizTable" class="table table-striped table-bordered table-sm">

<thead class="thead-light">


<tr>
<th>Quiz Name</th>
<th id="createdByHeader">Created By</th>
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
var user_role = "<?php echo $_SESSION['role'] ?? ''; ?>";

$(document).ready(function(){

    if(user_role === "instructor"){
        $("#createdByHeader").hide();     
        $("#createdByFilterBox").hide();  
    }

    loadCategories();
    loadQuizzes();
});


function loadQuizzes(){

$.get(api_url+"quiz/getallquiz.php",function(res){

if(res.status!="success") return;

loadInstructorFilter(res.data);

let html="";

res.data.forEach(function(q){

let statusBadge="";

if(q.status=="published"){
    statusBadge='<span class="badge bg-success">Published</span>';
}
else if(q.status=="deleted"){
    statusBadge='<span class="badge bg-danger">Deleted</span>';
}
else{
    statusBadge='<span class="badge bg-warning">Draft</span>';
}


/* ACTION BUTTONS */

let actionBtns = "";

if(q.status === "draft"){

actionBtns = `
<a href="editquiz.php?quiz_id=${q.quiz_id}">
<i class="mdi mdi-pencil text-primary"></i>
</a>

<a href="#" onclick="deleteQuiz(${q.quiz_id})">
<i class="mdi mdi-delete text-danger"></i>
</a>
`;

}
else if(q.status === "published"){

actionBtns = `
<a href="#" onclick="deleteQuiz(${q.quiz_id})">
<i class="mdi mdi-delete text-danger"></i>
</a>
`;

}
else{
actionBtns = `<span class="text-muted small">No actions</span>`;
}


/* ROW */

let createdByColumn = "";

if(user_role === "admin"){
    createdByColumn = `<td>${q.instructor_name ?? '-'}</td>`;
}

html+=`

<tr 
data-category="${q.category}" 
data-instructor="${q.created_by}">

<td>${q.title}</td>
${createdByColumn}

<td>${q.category}</td>
<td>${q.duration} min</td>
<td>${q.total_questions}</td>
<td>${q.total_marks}</td>
<td>${statusBadge}</td>

<td class="text-center">
${actionBtns}
</td>

</tr>

`;

});

$("#quizBody").html(html);

/* DATATABLE */

$('#quizTable').DataTable({
    pageLength: 5,
    lengthChange: false,
    destroy: true,
    paging: true,          
    searching: false,      
    info: true,            
    ordering: true,
    dom: 'tip'            
});

},"json");

}


/* ================= LOAD CATEGORY ================= */

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


/* ================= INSTRUCTOR FILTER ================= */

function loadInstructorFilter(data){

let unique = {};

data.forEach(q=>{
    if(q.created_by){
        unique[q.created_by] = q.instructor_name;
    }
});

let html = '<option value="">Created By</option>';

Object.keys(unique).forEach(id=>{
    html += `<option value="${id}">${unique[id]}</option>`;
});

$("#instructorFilter").html(html);

}



function applyFilters(){

let category = $("#categoryFilter").val();
let instructor = $("#instructorFilter").val();
let search = $("#quizSearch").val().toLowerCase();

$("#quizTable tbody tr").each(function(){

let rowCategory = $(this).data("category");
let rowInstructor = $(this).data("instructor");
let rowText = $(this).text().toLowerCase();

let matchCategory = (category=="" || rowCategory==category);
let matchInstructor = (instructor=="" || rowInstructor==instructor);
let matchSearch = (search=="" || rowText.includes(search));

if(matchCategory && matchInstructor && matchSearch){
    $(this).show();
}else{
    $(this).hide();
}

});

}


$("#categoryFilter").on("change", applyFilters);

$("#instructorFilter").on("change", applyFilters);

$("#quizSearch").on("keyup", applyFilters);



function resetFilters(){

$("#quizSearch").val("");
$("#categoryFilter").val("");
$("#instructorFilter").val("");

applyFilters(); 

}


/* publish quiz */

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

$.post(api_url+"quiz/deletequiz.php",{quiz_id:id},function(res){

if(res.status=="success"){
alert("Quiz deleted successfully");
location.reload();
}else{
alert(res.message);
}

},"json");

}

</script>