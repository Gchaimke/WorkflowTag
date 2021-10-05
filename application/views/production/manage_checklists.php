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
					<a class="btn btn-warning" onclick="$('#add_form').toggle()"><i class="fas fa-file-alt"></i> <?= lang('new') ?></a>
					<a class="btn btn-info" onclick="$('#batch_add_form').toggle()"><i class="fas fa-copy"></i> <?= lang('new') ?><?= lang('batch') ?></a>
					<a id='batchLink' class="btn btn-info disabled" href="/production/edit_batch/?client=<?= $client['id'] ?>&checklists="><i class="fa fa-tasks"></i> Edit Selected </a>
				</ul>
				<?php if (isset($links)) {
					echo $links;
				} ?>
			</nav>
		</div>
		<div id="add_form" style="display: none;">
			<center>
				<form id="add_checklist" action="production/add_checklist" class="my-4">
					<h3><?= lang('add_checklist') ?></h3>
					<h5 style="color: red;"><?= sprintf(lang('batch_msg'), $project); ?></h5>
					<input type='hidden' name='client' value='<?= $client['name'] ?>'>
					<input type='hidden' name='project' value='<?= $project ?>'>
					<div class="form-group"><label>Serial template <?php echo $template ?></label>
						<input class="form-control col-md-3" type='text' name='serial' placeholder="Serial Number">
					</div></br>
					<input type='date' class="form-control col-md-3" name='date' value="<?php echo date("Y-m-d"); ?>"></br>
					<input type='submit' class="btn btn-info btn-block col-md-3" name='submit' value='<?= lang('add') ?>'>
				</form>
			</center>
		</div>

		<div id="batch_add_form" style="display: none;">
			<center>
				<form id="add_batch" action="/production/gen_checklists" class="my-4">
					<h3><?= lang('add_batch') ?></h3>
					<h5 style="color: red;"><?= sprintf(lang('batch_msg'), $project); ?></h5>
					<input type='hidden' name='client' value='<?= $client['name'] ?>'>
					<input type='hidden' name='project' value='<?= $project ?>'>
					<input class="form-control col-md-3" type='number' name='count' placeholder="Quantity"></br>
					<input type='date' class="form-control col-md-3" name='date' value="<?php echo date("Y-m-d"); ?>"></br>
					<input type='submit' class="btn btn-info btn-block col-md-3" name='submit' value='<?= lang('add') ?>'></br>
				</form>
			</center>
		</div>

		<?php if (isset($results)) { ?>
			<div class="table-responsive">
				<table class="table table-striped table-hover">
					<thead class="table-dark">
						<tr>
							<th scope="col">*</th>
							<th scope="col">Serial Number</th>
							<th scope="col" class="mobile-hide">Project</th>
							<th scope="col"><i class="fa fa-tasks"></i></th>
							<th scope="col"><i class="fa fa-user"></i></th>
							<th scope="col" class="mobile-hide"><i class="fa fa-calendar"></i></th>
							<th scope="col"><i class="fas fa-image"></i></th>
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
						?>
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
										<div class="div_link" id='<?php echo $data->id ?>' onclick='showLog("<?php echo $data->log ?>","<?php echo $data->serial ?>")'>
											<?php echo $data->progress ?>%</div>
									</td>
									<td><?= $editors ?></td>
									<td class="mobile-hide"><?php echo $data->date ?></td>
									<td><?php echo $data->pictures ?></td>
									<td><a id='edit_checklist' target="_blank" href='/production/edit_checklist/<?= $data->id ?>?sn=<?= $data->serial ?>&client=<?= $client['id'] ?>' class='btn btn-info'><i class="fa fa-edit"></i></a></td>
									<td><button id='<?php echo $data->id ?>' class='btn btn-danger' onclick='trashChecklist(this.id,"<?= $project ?>","<?php echo $data->serial; ?>")'><i class="fa fa-trash"></i></button></td>
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
	var client = '<?php echo $client['name'] ?>';
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
		var formData = $('#add_checklist').serialize();
		var r = confirm("Add " + $('input[name=count]').val() + " checklist to " + $('input[name=project]').val() + "?");
		if (r == true) {
			$.ajax("/production/add_checklist/", {
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

	$('#add_batch').submit(function(event) {
		// Stop the browser from submitting the form.
		event.preventDefault();
		var formData = $('#add_batch').serialize();
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