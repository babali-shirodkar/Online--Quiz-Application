

<?php include("includes/header.php"); ?>
<?php include("includes/sidebar.php"); ?>

<div class="page-wrapper">

<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex align-items-center justify-content-between">
            <h4 class="page-title">Instructor</h4>
        </div>
    </div>
</div>

<div class="container-fluid">

<div class="card shadow-sm">
<div class="card-body">

<div class="d-flex justify-content-between align-items-center mb-3">

<h5 class="card-title mb-0">Manage Instructor</h5>

<button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addInstructorModal">
<i class="mdi mdi-plus"></i> Add Instructor
</button>

</div>

<div class="row mb-3">
<div class="col-md-4">
<input type="text" id="searchInstructor" class="form-control form-control-sm" placeholder="Search instructor">
</div>
</div>

<div class="table-responsive">

<table id="instructorTable" class="table table-striped table-bordered table-sm">

<thead class="thead-light">
<tr>
<th>Name</th>
<th>Email</th>
<th>Status</th>
<th class="text-center">Action</th>
</tr>
</thead>

<tbody id="instructorBody"></tbody>

</table>

</div>

</div>
</div>

</div>
</div>



<div class="modal fade" id="addInstructorModal">
<div class="modal-dialog">
<div class="modal-content">

<div class="modal-header">
<h5>Add Instructor</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<input type="text" id="name" class="form-control mb-2" placeholder="Name">
<input type="email" id="email" class="form-control mb-2" placeholder="Email">
<input type="password" id="password" class="form-control mb-2" placeholder="Password">

</div>

<div class="modal-footer">
<button class="btn btn-primary btn-sm" onclick="addInstructor()">Save</button>
</div>

</div>
</div>
</div>

<?php include("includes/footer.php"); ?>

<script>

var api_url = "<?php echo $api_url; ?>";

$(document).ready(function(){
    loadInstructor();
});



function loadInstructor(){

$.get(api_url+"user/getinstructors.php",function(res){

if(res.status!="success") return;

let html="";

res.data.forEach(function(u){

let statusBadge = (u.status=="active") 
? '<span class="badge bg-success">Active</span>'
: '<span class="badge bg-secondary">Inactive</span>';

html+=`

<tr>

<td>${u.name}</td>
<td>${u.email}</td>
<td>${statusBadge}</td>

<td class="text-center action-icons">

<a href="#" onclick="editInstructor(${u.user_id},'${u.name}','${u.email}')" title="Edit">
<i class="mdi mdi-pencil text-primary"></i>
</a>

<a href="#" onclick="toggleStatus(${u.user_id})" title="Disable">
<i class="mdi mdi-block-helper text-warning"></i>
</a>

<a href="#" onclick="deleteInstructor(${u.user_id})" title="Delete">
<i class="mdi mdi-delete text-danger"></i>
</a>

</td>

</tr>
`;

});

$("#instructorBody").html(html);

$('#instructorTable').DataTable({
pageLength:10,
lengthChange:false,
destroy:true
});

},"json");

}



function addInstructor(){

let name=$("#name").val();
let email=$("#email").val();
let password=$("#password").val();

$.post(api_url+"user/addinstructor.php",{
name,email,password
},function(res){

if(res.status=="success"){
alert("Instructor Added");
location.reload();
}else{
alert(res.message);
}

},"json");

}



function deleteInstructor(id){

if(!confirm("Delete instructor?")) return;

$.post(api_url+"user/deleteuser.php",{id:id},function(res){

if(res.status=="success"){
alert("Deleted");
location.reload();
}

},"json");

}



function toggleStatus(id){

$.post(api_url+"user/togglestatus.php",{id:id},function(res){

if(res.status=="success"){
location.reload();
}

},"json");

}


$("#searchInstructor").on("keyup",function(){

let value=$(this).val().toLowerCase();

$("#instructorTable tbody tr").filter(function(){
$(this).toggle($(this).text().toLowerCase().indexOf(value)>-1);
});

});

</script>