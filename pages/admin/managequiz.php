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
						<li class="breadcrumb-item">
							<a href="#">Quiz</a>
						</li>
						<li class="breadcrumb-item active">
							Manage Quiz
						</li>
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
						<input 
							type="text" 
							id="quizSearch" 
							class="form-control form-control-sm" 
							placeholder="Search quiz"
						>
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

		$.ajax({
			url: api_url + "quiz/getallquiz.php",
			type: "GET",
			contentType: "application/json",
			data: JSON.stringify({}),
			dataType: "json",

			success: function(res){

				if(res.status != "success") return;

				loadInstructorFilter(res.data);

				let html = "";

				res.data.forEach(function(q){

					let statusBadge = "";

					if(q.status == "published"){
						statusBadge = '<span class="badge bg-success">Published</span>';
					}
					else{
						statusBadge = '<span class="badge bg-warning">Draft</span>';
					}

					let actionBtns = "";

					if(q.status === "draft"){

						actionBtns = `
							<a href="editquiz.php?quiz_id=${q.quiz_id}" title="Edit">
								<i class="mdi mdi-pencil text-primary"></i>
							</a>

							<a href="#" onclick="publishQuiz(${q.quiz_id})" title="Publish">
								<i class="mdi mdi-upload text-success"></i>
							</a>

							<a href="#" onclick="deleteQuiz(${q.quiz_id})" title="Delete">
								<i class="mdi mdi-delete text-danger"></i>
							</a>
						`;

					}
					else if(q.status === "published"){

						actionBtns = `
							<a href="#" onclick="unpublishQuiz(${q.quiz_id})" title="Unpublish">
								<i class="mdi mdi-download text-warning"></i>
							</a>

							<a href="#" onclick="deleteQuiz(${q.quiz_id})" title="Delete">
								<i class="mdi mdi-delete text-danger"></i>
							</a>
						`;

					}
					else{
						actionBtns = `<span class="text-muted small">No actions</span>`;
					}

					let createdByColumn = "";

					if(user_role === "admin"){
						createdByColumn = `<td>${q.instructor_name ?? '-'}</td>`;
					}

					html += `
					<tr data-category="${q.category}" data-instructor="${q.created_by}">
						<td>${q.title}</td>
						${createdByColumn}
						<td>${q.category}</td>
						<td>${q.duration} min</td>
						<td>${q.total_questions}</td>
						<td>${q.total_marks}</td>
						<td>${statusBadge}</td>
						<td class="text-center">${actionBtns}</td>
					</tr>`;
				});

				$("#quizBody").html(html);

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

			}
		});

	}



	function loadCategories(){

		$.ajax({
			url: api_url + "category/getcategories.php",
			type: "GET",
			contentType: "application/json",
			data: JSON.stringify({}),
			dataType: "json",

			success: function(res){

				if(res.status != "success") return;

				let html = '<option value="">All Categories</option>';

				res.data.forEach(function(cat){
					html += `<option value="${cat.category_name}">${cat.category_name}</option>`;
				});

				$("#categoryFilter").html(html);

			}

		});

	}


	/* Instructor filter  */

	function loadInstructorFilter(data){

		let unique = {};

		data.forEach(q => {
			if(q.created_by){
				unique[q.created_by] = q.instructor_name;
			}
		});

		let html = '<option value="">Created By</option>';

		Object.keys(unique).forEach(id => {
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

			let matchCategory = (category == "" || rowCategory == category);
			let matchInstructor = (instructor == "" || rowInstructor == instructor);
			let matchSearch = (search == "" || rowText.includes(search));

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

		$.ajax({
			url: api_url + "quiz/publishquiz.php",
			type: "POST",
			contentType: "application/json",
			dataType: "json",   
			data: JSON.stringify({ quiz_id: id }),

			success: function(res){

				console.log(res); 

				if(res.status === "success"){
					alert("Quiz published successfully");
					location.reload();
				} else {
					alert(res.message || "Something went wrong");
				}
			},

			error: function(xhr){
				console.log(xhr.responseText);
				alert("Server error");
			}
		});

	}

	/* Un publish Quiz */

	function unpublishQuiz(id){

		if(!confirm("Unpublish this quiz? It will move to draft.")) return;

		$.ajax({
			url: api_url + "quiz/unpublishquiz.php",
			type: "POST",
			contentType: "application/json",
			data: JSON.stringify({ quiz_id: id }),
			dataType: "json",

			success: function(res){

				if(res.status == "success"){
					alert("Quiz moved to draft successfully");
					location.reload();
				}else{
					alert(res.message || "Something went wrong");
				}

			}

		});

	}


	function deleteQuiz(id){

		if(!confirm("Delete this quiz?")) return;

		$.ajax({
			url: api_url + "quiz/deletequiz.php",
			type: "POST",
			contentType: "application/json",
			data: JSON.stringify({ quiz_id: id }),
			dataType: "json",

			success: function(res){

				if(res.status == "success"){
					alert("Quiz deleted successfully");
					location.reload();
				}else{
					alert(res.message);
				}

			}

		});

	}

</script>