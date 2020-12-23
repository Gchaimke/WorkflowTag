<?php
$project =  explode("/", $_SERVER['REQUEST_URI'])[3];
?>
<main role="main">
	<div class="jumbotron">
		<div class="container">
			<center>
				<h2 class="display-3"><?php echo urldecode($project); ?> Checklists</h2>
			</center>
		</div>
	</div>
	<div class="container">
		<div style="height: 70px;">
			<?php
			if (isset($message_display)) {
				echo "<div class='alert alert-success' role='alert'>";
				echo $message_display . '</div>';
			}
			?>
			<nav aria-label="Checklist navigation">
				<ul class="pagination left">
					<a class="btn btn-warning" href="/production/add_checklist/<?php echo $project; ?>">New <i class="fa fa-file-text"></i></a>
					<a class="btn btn-info" onclick="$('#batch_add_form').toggle()">Add Batch</a>
					<a id='batchLink' class="btn btn-info fa fa-list-ol disabled" href="/production/edit_batch/" onclick="cleanUrl()"></a>
				</ul>

				<?php if (isset($links)) {
					echo $links;
				} ?>
			</nav>
		</div>
		<div id="batch_add_form" style="display: none;">
			<center>
				<h3>Add checklists</h3>
				<form id="add_checklists" action="/production/gen_checklists" class="user-create">
					<input type='hidden' name='client' value='<?php echo $client[0]['name'] ?>'>
					<input type='hidden' name='project' value='<?php echo urldecode($project) ?>'>
					<input class="form-control " type='number' name='count' placeholder="Quantity"></br>
					<input type='date' class="form-control" name='date' value="<?php echo date("Y-m-d"); ?>"></br>
					<input type='submit' class="btn btn-info btn-block" name='submit' value='Submit'></br>
				</form>
			</center>
		</div>
		<?php if (isset($results)) { ?>
			<table class="table">
				<thead class="thead-dark">
					<tr>
						<th scope="col">*</th>
						<th scope="col">Serial Number</th>
						<th scope="col" class="mobile-hide">Project</th>
						<th scope="col"><i class="fa fa-tasks"></i></th>
						<th scope="col"><i class="fa fa-user"></i></th>
						<th scope="col" class="mobile-hide">QC</th>
						<th scope="col" class="mobile-hide"><i class="fa fa-calendar"></i></th>
						<th scope="col"><i class="fa fa-picture-o"></i></th>
						<th scope="col">Edit</th>
						<th scope="col">Trash</th>
					</tr>
				</thead>
				<tbody>

					<?php foreach ($results as $data) { ?>
						<tr id='<?php echo $data->id ?>'>
							<td>
								<div class='checkbox'><input type='checkbox' class='select' id='<?php echo $data->id ?>' $checked></div>
							</td>
							<td><?php if ($data->serial != '') {
									echo $data->serial;
								} else {
									echo "SN template not found!";
								}  ?></td>
							<td class="mobile-hide"><?php echo $data->project ?></td>
							<td>
								<a href='#' id='<?php echo $data->id ?>' onclick='showLog("<?php echo $data->log ?>","<?php echo $data->serial ?>")'>
									<?php echo $data->progress ?>%</a></td>
							<td><?php echo $data->assembler ?></td>
							<td class="mobile-hide"><?php echo $data->qc ?></td>
							<td class="mobile-hide"><?php echo $data->date ?></td>
							<td><?php echo $data->pictures ?></td>
							<td><a id='edit_checklist' target="_blank" href='/production/edit_checklist/<?php echo $data->id ?>?sn=<?php echo $data->serial ?>' class='btn btn-info'><i class="fa fa-edit"></i></a></td>
							<td><button id='<?php echo $data->id ?>' class='btn btn-danger' onclick='trashChecklist(this.id,"<?php echo urldecode($project); ?>","<?php echo $data->serial; ?>")'><i class="fa fa-trash"></i></button></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		<?php } else { ?>
			<div>No checklist(s) found.</div>
		<?php } ?>
	</div>
	<div id='show-log' style='display:none;'>
		<div id="show-log-header">
			<div id="serial-header"></div>Click here to move<button type="button" class="close" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
		</div>
		<ul class="list-group list-group-flush">
		</ul>
	</div>
</main>
<script>
	var client = '<?php echo $client[0]['name'] ?>';

	function trashChecklist(id, project, serial) {
		var r = confirm("Trash checklist " + serial + "?");
		if (r == true) {
			$.post("/production/trashChecklist", {
				id: id,
				project: project,
				serial: serial
			}).done(function(o) {
				//$('[id^=' + id + ']').remove();
				location.reload();
			});
		}
	}

	$('#add_checklists').submit(function(event) {
		// Stop the browser from submitting the form.
		event.preventDefault();
		var formData = $('#add_checklists').serialize();
		var r = confirm("Add " + $('input[name=count]').val() + " checklist/s to " + $('input[name=project]').val() + "?");
		if (r == true) {
			$.ajax("/production/gen_checklists", {
				type: 'POST',
				url: $('#ajax-form').attr('action'),
				data: formData
			}).done(function(o) {
				if (o != 1) {
					alert(o);
				}
				location.reload();
			});
		}
	});
</script>