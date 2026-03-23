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
							Create Quiz
						</li>
					</ol>
				</nav>
			</div>
		</div>
	</div>

	<div class="container-fluid">

		<div class="row">
			<div class="col-md-12">

				<div class="card">

					<form id="createQuizForm" onsubmit="return false">

						<div class="card-body">

							<h4 class="card-title mb-4">Create Quiz</h4>

							<div class="row mb-3">

								<div class="col-md-6">
									<label class="form-label">Quiz Title</label>
									<input 
										type="text"
										class="form-control form-control-sm"
										id="qtitle"
										placeholder="Quiz Title"
									>
								</div>

								<div class="col-md-6">
									<label class="form-label">Total Marks</label>
									<input 
										type="number"
										class="form-control form-control-sm"
										id="tmarks"
										placeholder="Total Marks"
									>
								</div>

							</div>

							<div class="row mb-3">

								<div class="col-md-6">
									<label class="form-label">Category</label>

									<select class="form-select form-select-sm" id="category">
										<option value="">Select Category</option>
									</select>
								</div>

								<div class="col-md-6">
									<label class="form-label">Duration (Minutes)</label>
									<input 
										type="number"
										class="form-control form-control-sm"
										id="duration"
										placeholder="Duration"
									>
								</div>

							</div>

							<div class="row mb-3">

								<div class="col-md-6">
									<label class="form-label">Total Questions</label>
									<input 
										type="number"
										class="form-control form-control-sm"
										id="tques"
										placeholder="Total Questions"
									>
								</div>

							</div>

						</div>

						<div class="card-footer text-end">

							<button 
								type="button"
								class="btn btn-primary"
								onclick="createQuiz()"
							>
								<i class="mdi mdi-plus-circle"></i> Create Quiz
							</button>

						</div>

					</form>

				</div>

			</div>
		</div>

	</div>

</div>

<?php include("includes/footer.php"); ?>

<script>

	let api_url = "<?php echo $api_url; ?>";

	$(document).ready(function(){
		loadCategories();
	});


	/* Load Categories */

	function loadCategories(){

		$.ajax({
			url: api_url + "category/getcategories.php",
			type: "GET",
			dataType: "json",

			success: function(res){

				if(res.status === "success"){

					let html = '<option value="">Select Category</option>';

					res.data.forEach(function(cat){

						html += `
							<option value="${cat.id}">
								${cat.category_name}
							</option>
						`;

					});

					$("#category").html(html);

				}else{
					alert(res.message || "Failed to load categories");
				}
			},

			error: function(){
				alert("Error loading categories");
			}
		});

	}


	/* CREATE QUIZ */

	function createQuiz(){

		let title = $("#qtitle").val().trim();
		let total_marks = $("#tmarks").val();
		let category_id = $("#category").val();
		let duration = $("#duration").val();
		let total_questions = $("#tques").val();
		let attempt_limit = $("#attempt").val();

		if(title === "" || category_id === "" || duration === ""){
			alert("Please fill required fields");
			return;
		}

		let quizData = {
			title: title,
			category_id: category_id,
			duration: duration,
			total_marks: total_marks,
			total_questions: total_questions,
			attempt_limit: attempt_limit
		};

		$.ajax({

			url: api_url + "quiz/createquiz.php",
			type: "POST",
			contentType: "application/json",
			data: JSON.stringify(quizData),
			dataType: "json",

			xhrFields:{
				withCredentials: true
			},

			success: function(response){

				console.log(response);

				if(response.status === "success"){

					alert("Quiz Created Successfully");

					window.location.href = "editquiz.php?quiz_id=" + response.quiz_id;

				}else{

					alert(response.message || "Failed to create quiz");

				}

			},

			error: function(xhr){

				console.log(xhr.responseText);
				alert("Server error. Please try again");

			}

		});

	}

</script>