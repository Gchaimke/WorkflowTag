<?php
$project =  urldecode($_GET['project']);
$client['name'] = is_array($client) ? $client['name'] : "error";
$file = "./Uploads/" . urldecode($client['name']) . "/" . $project . "/assembly.pdf";
if (file_exists($file)) {
	$assembly = true;
} else {
	$assembly = false;
}
?>
<main role="main">
	<div class="jumbotron">
		<div class="container">
			<center>
				<h2 class="display-3"><?= $project ?></h2>
				<?php if ($assembly) { ?>
					<a class="btn btn-warning d-none d-xl-inline" target="_blank" href="/<?= $file ?>"><i class="fas fa-file-pdf"></i> <?= lang('assembly') ?> </a>
				<?php }; ?>
			</center>
		</div>
	</div>
	<div class="container">
		<div>
			<?php
			if (isset($message_display)) {
				echo "<div class='alert alert-success' role='alert'>";
				echo $message_display . '</div>';
			}
			?>
			<nav class="pagination-nav" aria-label="Checklist navigation">
				<ul class="pagination-nav-menu">
					<a class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#add_one"><i class="fas fa-file-alt"></i> <?= lang('new') ?></a>
					<a class="btn btn-info" data-bs-toggle="modal" data-bs-target="#add_many"><i class="fas fa-copy"></i> <?= lang('new') ?><?= lang('batch') ?></a>
					<a id='batchLink' class="btn btn-info disabled" href="/production/edit_batch/?client=<?= $client['id'] ?>&checklists="><i class="fa fa-tasks"></i> Edit Selected </a>
				</ul>
				<?php if (isset($links)) {
					echo $links;
				} ?>
			</nav>
		</div>
		<!-- Modal -->
		<div class="modal fade" id="add_one" tabindex="-1" aria-labelledby="add_oneLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="staticBackdropLabel"><?= lang('add_checklist') ?></h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<center>
							<form id="add_checklist" action="production/add_checklist" class="my-4">
								<h5 style="color: red;"><?= sprintf(lang('batch_msg'), $project); ?></h5>
								<input type='hidden' name='client' value='<?= $client['name'] ?>'>
								<input type='hidden' name='project' value='<?= $project ?>'>
								<div class="form-group"><label>Serial template <?= $template ?></label>
									<input class="form-control col-md-3" type='text' name='serial' placeholder="Serial Number">
								</div></br>
								<input type='date' class="form-control col-md-3" name='date' value="<?= date("Y-m-d"); ?>"></br>
							</form>
						</center>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" onclick="$('#add_checklist').submit()"><?= lang('add') ?></button>
					</div>
				</div>
			</div>
		</div>
		<!-- Modal -->
		<div class="modal fade" id="add_many" tabindex="-1" aria-labelledby="add_manyLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="staticBackdropLabel"><?= lang('add_checklist') ?></h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<center>
							<form id="add_batch" action="/production/gen_checklists" class="my-4">
								<h3><?= lang('add_batch') ?></h3>
								<h5 style="color: red;"><?= sprintf(lang('batch_msg'), $project); ?></h5>
								<input type='hidden' name='client' value='<?= $client['name'] ?>'>
								<input type='hidden' name='project' value='<?= $project ?>'>
								<input class="form-control col-md-3" type='number' name='count' placeholder="Quantity"></br>
								<input type='date' class="form-control col-md-3" name='date' value="<?= date("Y-m-d"); ?>"></br>
							</form>
						</center>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" onclick="$('#add_batch').submit()"><?= lang('add') ?></button>
					</div>
				</div>
			</div>
		</div>

		<?php if (isset($results)) { ?>
			<div class="table-responsive">
				<table class="table table-striped table-hover">
					<thead class="table-dark">
						<tr>
							<th scope="col">*</th>
							<th scope="col">Serial Number</th>
							<th scope="col" class="mobile-hide">Project</th>
							<th scope="col" class="mobile-hide"><i class="fa fa-calendar"></i></th>
							<th scope="col"><i class="fa fa-user"></i></th>
							<th scope="col"><i class="fa fa-tasks"></i></th>
							<th scope="col"><i class="fas fa-image"></i></th>
							<th scope="col"><i class="fas fa-clipboard-check"></i></th>
							<th scope="col">Edit</th>
							<th scope="col">Trash</th>
						</tr>
					</thead>
					<tbody>

						<?php
						if (is_array($results)) {
							foreach ($results as $data) {
								$editors_array = array_unique(array_filter(explode(",", str_replace(' ', '', $data->data))));
								$editors = array();
								foreach ($editors_array as $editor) {
									if (in_array($editor, $users)) {
										array_push($editors, $editor);
									}
								}
								$editors = implode(', ', $editors);
								$scans = explode(",", $data->scans);
						?>
								<tr id='<?= $data->id ?>'>
									<td>
										<div class='checkbox'><input type='checkbox' class='select' id='<?= $data->id ?>' $checked></div>
									</td>
									<td><?php if ($data->serial != '') {
											echo $data->serial;
										} else {
											echo "SN template not found!";
										}  ?></td>
									<td class="mobile-hide"><?= $data->project ?></td>
									<td class="mobile-hide"><?= $data->date ?></td>
									<td><?= $editors ?></td>
									<td><?= count(array_filter($scans)) ?></td>
									<td><?= $data->pictures ?></td>
									<td>
										<div class="div_link" id='<?= $data->id ?>' onclick='showLog("<?= $data->log ?>","<?= $data->serial ?>")'>
											<?= $data->progress ?>%</div>
									</td>
									<td><a id='edit_checklist' target="_blank" href='/production/edit_checklist/<?= $data->id ?>?sn=<?= $data->serial ?>&client=<?= $client['id'] ?>' class='btn btn-info'><i class="fa fa-edit"></i></a></td>
									<td><button id='<?= $data->id ?>' class='btn btn-danger' onclick='trashChecklist(this.id,"<?= $project ?>","<?= $data->serial; ?>")'><i class="fa fa-trash"></i></button></td>
								</tr>
						<?php }
						} ?>
					</tbody>
				</table>
			</div>
		<?php } else { ?>
			<div>No checklist(s) found.</div>
		<?php } ?>
	</div>
	<div id='show-log' style='display:none;'>
		<div id="show-log-header">
			<div id="serial-header"></div><button type="button" class="close" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
		</div>
		<ul class="list-group list-group-flush">
		</ul>
	</div>
</main>
<script>
	var client = '<?= $client['name'] ?>';
	var checklists = [];

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

	$('#add_checklist').submit(function(event) {
		// Stop the browser from submitting the form.
		event.preventDefault();
		let serial = $('#add_checklist').find("input[name=serial]").val();
		let formData = $('#add_checklist').serialize();
		if (serial != "") {
			//let r = confirm("Add " + $('input[name=count]').val() + " checklist to " + $('input[name=project]').val() + "?");
			$.ajax("/production/add_checklist/", {
				type: 'POST',
				data: formData
			}).done(function(out) {
				if (out != 1) {
					alert(out);
				}
				location.reload();
			});
		} else {
			alert("input serial is empty");
		}
	});

	$('#add_batch').submit(function(event) {
		// Stop the browser from submitting the form.
		event.preventDefault();
		let count = $('#add_batch').find("input[name=count]").val();
		let formData = $('#add_batch').serialize();
		// let r = confirm("Add " + $('input[name=count]').val() + " checklist/s to " + $('input[name=project]').val() + "?");
		if (count != "") {
			$.ajax("/production/gen_checklists", {
				type: 'POST',
				data: formData
			}).done(function(o) {
				if (o != 1) {
					alert(o);
				}
				location.reload();
			});
		}
	});


	$('.select').click(function() {
		var id = $(this).attr('id');
		var link = document.getElementById('batchLink');
		if ($(event.target).is(":checked")) {
			checklists.push(id);
			count += 1;
		} else {
			checklists = checklists.filter(item => item !== id)
			count -= 1;
		}

		if (count > 0) {
			$('#batchLink').removeClass('disabled');
		} else {
			$('#batchLink').addClass('disabled');
		}
		console.log(checklists);
	});


	$("#batchLink").on('click', function(e) {
		let link = $(this).attr('href');
		link = link + checklists.join(":");
		$(this).attr('href', link);
	})
</script>