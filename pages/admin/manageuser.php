<?php include("includes/header.php"); ?>
<?php include("includes/sidebar.php"); ?>

<div class="page-wrapper">

	<div class="page-breadcrumb">
		<div class="row">
			<div class="col-12 d-flex align-items-center justify-content-between">
				<h4 class="page-title">Participant</h4>
			</div>
		</div>
	</div>

	<div class="container-fluid">

		<div class="card shadow-sm">
			<div class="card-body">

				<div class="d-flex justify-content-between align-items-center mb-3">

					<h5 class="card-title mb-0">Manage Participants</h5>

					<!--
					<button 
						class="btn btn-primary btn-sm" 
						data-bs-toggle="modal" 
						data-bs-target="#addParticipantModal"
					>
						<i class="mdi mdi-account-plus"></i> Add Participant
					</button>
					-->

				</div>

				<div class="row mb-3">
					<div class="col-md-4">
						<input 
							type="text" 
							id="searchParticipant" 
							class="form-control form-control-sm" 
							placeholder="Search participant"
						>
					</div>
				</div>

				<div class="table-responsive">

					<table id="participantTable" class="table table-striped table-bordered table-sm">

						<thead class="thead-light">
							<tr>
								<th>Full Name</th>
								<th>Email ID</th>
								<th>Status</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>

						<tbody id="participantBody"></tbody>

					</table>

				</div>

			</div>
		</div>

	</div>

</div>


<div class="modal fade" id="addParticipantModal">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<h5>Add Participant</h5>
				<button 
					type="button" 
					class="btn-close" 
					data-bs-dismiss="modal"
				></button>
			</div>

			<div class="modal-body">

				<input 
					type="text" 
					id="name" 
					class="form-control mb-2" 
					placeholder="Name"
				>

				<input 
					type="email" 
					id="email" 
					class="form-control mb-2" 
					placeholder="Email"
				>

				<input 
					type="password" 
					id="password" 
					class="form-control mb-2" 
					placeholder="Password"
				>

			</div>

			<div class="modal-footer">
				<button 
					class="btn btn-primary btn-sm" 
					onclick="addParticipant()"
				>
					Save
				</button>
			</div>

		</div>
	</div>
</div>

<?php include("includes/footer.php"); ?>

<script>

	var api_url = "<?php echo $api_url; ?>";

	$(document).ready(function(){
		loadParticipants();
	});


	/* LOAD PARTICIPANTS */

	function loadParticipants(){

		$.ajax({
			url: api_url + "user/getparticipant.php",
			type: "GET",
			contentType: "application/json",
			data: JSON.stringify({}),
			dataType: "json",

			success: function(res){

				if(res.status != "success") return;

				let html = "";

				res.data.forEach(function(u){

					let statusBadge = (u.status == "active") 
						? '<span class="badge bg-success">Active</span>'
						: '<span class="badge bg-secondary">Inactive</span>';

					html += `

					<tr>

						<td>${u.name}</td>
						<td>${u.email}</td>
						<td>${statusBadge}</td>

						<td class="text-center action-icons">

							<a href="#" onclick="editParticipant(${u.user_id},'${u.name}','${u.email}')" title="Edit">
								<i class="mdi mdi-pencil text-primary"></i>
							</a>

							<a href="#" onclick="toggleStatus(${u.user_id})" title="Activate / Deactivate">
								<i class="mdi mdi-account-off text-warning"></i>
							</a>

							<a href="#" onclick="deleteParticipant(${u.user_id})" title="Delete">
								<i class="mdi mdi-delete text-danger"></i>
							</a>

						</td>

					</tr>

					`;

				});

				$("#participantBody").html(html);

				$('#participantTable').DataTable({
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


	/* ADD PARTICIPANT */

	function addParticipant(){

		let name = $("#name").val();
		let email = $("#email").val();
		let password = $("#password").val();

		$.ajax({
			url: api_url + "user/addparticipant.php",
			type: "POST",
			contentType: "application/json",
			data: JSON.stringify({
				name: name,
				email: email,
				password: password
			}),
			dataType: "json",

			success: function(res){

				if(res.status == "success"){
					alert("Participant added successfully");
					location.reload();
				}else{
					alert(res.message);
				}

			}

		});

	}


	/* DELETE PARTICIPANT */

	function deleteParticipant(id){

		if(!confirm("Delete participant?")) return;

		$.ajax({
			url: api_url + "user/deleteuser.php",
			type: "POST",
			contentType: "application/json",
			data: JSON.stringify({ id: id }),
			dataType: "json",

			success: function(res){

				if(res.status == "success"){
					alert("Deleted successfully");
					location.reload();
				}else{
					alert(res.message);
				}

			}

		});

	}


	/* TOGGLE STATUS */

	function toggleStatus(id){

		$.ajax({
			url: api_url + "user/togglestatus.php",
			type: "POST",
			contentType: "application/json",
			data: JSON.stringify({ id: id }),
			dataType: "json",

			success: function(res){

				if(res.status == "success"){
					location.reload();
				}else{
					alert(res.message);
				}

			}

		});

	}


	/* SEARCH */

	$("#searchParticipant").on("keyup", function(){

		let value = $(this).val().toLowerCase();

		$("#participantTable tbody tr").filter(function(){
			$(this).toggle(
				$(this).text().toLowerCase().indexOf(value) > -1
			);
		});

	});

</script>