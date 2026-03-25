<?php include("includes/header.php"); ?>
<?php include("includes/sidebar.php"); ?>

<div class="page-wrapper">

	<!-- Breadcrumb -->
	<div class="page-breadcrumb">
		<div class="row">
			<div class="col-12 d-flex align-items-center justify-content-between">
				<h4 class="page-title">My Attempts</h4>

				<ol class="breadcrumb">
					<li class="breadcrumb-item">Quiz</li>
					<li class="breadcrumb-item active">My Attempts</li>
				</ol>
			</div>
		</div>
	</div>

	<div class="container-fluid">

		<div class="card shadow-sm">

			<div class="card-body">

				<div class="d-flex justify-content-between align-items-center mb-3">
					<h5 class="card-title mb-0">Attempted Quizzes</h5>
				</div>

				<div class="table-responsive">

					<table id="attemptTable" class="table table-striped table-bordered table-sm">

						<thead class="thead-light">
							<tr>
								<th>Quiz Name</th>
								<th>Date</th>
								<th>Score</th>
								<th>Status</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>

						<tbody id="attemptBody"></tbody>

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
		loadAttempts();
	});


	function loadAttempts(){

		$.ajax({
			url: api_url + "quiz/getattempts.php",
			type: "POST",
			contentType: "application/json",
			data: JSON.stringify({}),
			dataType: "json",

			success: function(res){

				if(res.status != "success"){
					alert("Failed to load attempts");
					return;
				}

				let html = "";

				res.data.forEach(function(a){

					let statusBadge = "";

					if(a.status == "completed"){
						statusBadge = '<span class="badge bg-success">Completed</span>';
					}else{
						statusBadge = '<span class="badge bg-warning">In Progress</span>';
					}

					html += `

					<tr>

						<td>${a.quiz_name}</td>

						<td>${a.completed_at ?? "-"}</td>

						<td>${a.score ?? 0} / ${a.total_questions ?? 0}</td>

						<td>${statusBadge}</td>

						<td class="text-center action-icons">

							<a href="result.php?attempt_id=${a.attempt_id}" title="View Result">
								<i class="mdi mdi-eye text-info"></i>
							</a>

						</td>

					</tr>

					`;

				});

				$("#attemptBody").html(html);

				$('#attemptTable').DataTable({
					pageLength: 10,
					lengthChange: false,
					destroy: true
				});

			},

			error: function(){
				alert("Server error while loading attempts");
			}

		});

	}

</script>