<?php
if (isset($this->session->userdata['logged_in'])) {
	$editors = array("Admin", "Engineer");
	$user_role = $this->session->userdata['logged_in']['role'];
	if (!in_array($user_role, $editors)) {
		header("location: /");
	}
}

?>
<script src="<?php echo base_url('assets/js/jQUpload/jquery.ui.widget.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jQUpload/jquery.iframe-transport.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jQUpload/jquery.fileupload.js'); ?>"></script>
<main role="main">
	<div class="jumbotron">
		<div class="container">
			<center>
				<h2 class="display-3">Edit <?= $project['project'] ?></h2>
			</center>
		</div>
	</div>
	<div class="container">
		<center>
			<?php
			if (isset($message_display)) {
				echo "<div class='alert alert-success' role='alert'>";
				echo $message_display . '</div>';
			}
			if (validation_errors()) {
				echo "<div class='alert alert-danger' role='alert'>" . validation_errors() . "</div>";
			}

			if (isset($project)) {	?>
				<?php echo form_open("projects/edit_project/{$project['id']}", 'class=user-create'); ?>

				<div class="row">
					<div class="col-md my-3">
						<div class="input-group">
							<div class="input-group-prepend">
								<div class="input-group-text"><?= lang('project_num') ?></div>
							</div>
							<input type="text" class="form-control" name='project_num' value="<?= $project['project_num'] ?>" placeholder="ZZ0PROJECT">
						</div>
					</div>
					<div class="col-md my-3">
						<div class="input-group">
							<div class="input-group-prepend">
								<div class="input-group-text">
									<input type="checkbox" name="status" <?= $project['status'] != 0 ? "checked" : "" ?> value="1">
									<div class="mx-2"><?= lang('Hide in projects') ?></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<label>yy = Year | mm = Month | dm = D-fend Month | ww = week | x,xx,xxx,xxxx = Serialized number | pattern = AVxxx-mm-yy</label>
				<div class="row">
					<div class="col-md my-3">
						<div class="input-group">
							<div class="input-group-prepend">
								<div class="input-group-text"><?= lang('Serial template') ?></div>
							</div>
							<input type="text" name="template" value="<?= $project['template'] ?>" class="form-control" placeholder="AVD-mm-yy-xxx">
						</div>
					</div>
					<div class="col-md my-3">
						<div class="input-group">
							<div class="input-group-prepend">
								<div class="input-group-text">
									<input type="checkbox" name="restart_serial" <?= $project['restart_serial'] != 0 ? "checked" : "" ?> value="1">
									<div class="mx-2"><?= lang('Serial number restarts every month') ?></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Project Files Section -->
				<?php
				foreach ($this->project_files as $name => $color) {
					$file = "./Uploads/" . urldecode($project['client']) . "/" . $project['project'] . "/" . $name . ".pdf";
					$dispaly_file = "hidden";
					$dispaly_link = "hidden";
					if ($project[$name]) {
						$dispaly_link = "";
					} else {
						if (file_exists($file)) {
							$dispaly_file = "";
						} else {
						}
					}
				?>
					<!-- <?= $name ?> -->
					<div class="card text-white bg-<?= $color ?> mb-3">
						<h5 class="card-header"><?= lang($name) ?></h5>
						<div class="card-body">
							<div class="input-group col-md my-3">
								<div class="input-group-prepend">
									<div class="input-group-text"><?= lang($name) ?> URL</div>
								</div>
								<input type="text" class="form-control" name='<?= $name ?>' value="<?= $project[$name] ?>">
							</div>
							<div class="row">
								<div class="input-group col-md my-3">
									<div class="input-group-prepend">
										<div class="input-group-text"><?= lang($name) ?> <?= lang('name') ?></div>
									</div>
									<input type="text" class="form-control" name='<?= $name ?>_name' value="<?= $project[$name . '_name'] ?>">
								</div>
								<div class="input-group col-md my-3">
									<div class="btn btn-success not-print" onclick="document.getElementById('<?= $name ?>_upload').click();"><i class="fa fa-file"></i> <?= lang('upload') ?> <?= lang($name) ?></div>
									<a class="btn btn-secondary mx-3  <?= $dispaly_link ?>" target="_blank" href="<?= $project[$name] ?>"><i class="fas fa-file-pdf"></i> <?= lang('view') ?> <?= lang($name) ?> </a>
									<a class="btn btn-secondary mx-3  <?= $dispaly_file ?>" target="_blank" href="/<?= $file ?>"><i class="fas fa-file-pdf"></i> <?= lang($name) ?> </a>
									<span id='<?= $name ?>_file' data-file='<?= $file ?>' onclick='delFile(this.id)' class='btn btn-danger <?= $dispaly_file ?>'><?= lang('delete') ?> <?= lang($name) ?></span></td>
									<input id="<?= $name ?>_upload" type="file" name="files" data-url="/projects/file_upload?client=<?= $project['client'] ?>&file=<?= $name ?>&project=<?= $project['project'] ?>" hidden />
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
				<!-- Checklist Section -->
				<div class="card mb-3">
					<h5 class="card-header">Checklist</h5>
					<div class="card-body">
						<div class="row my-3">
							<div class="form-group col-md">
								<div class="input-group mb-2">
									<div class="input-group-prepend">
										<div class="input-group-text"><?= lang('checklist_version') ?></div>
									</div>
									<select class="form-select col-4 checklist_version" name='checklist_version'>
										<option value="">Select Version</option>
										<?php if (isset($checklists)) {
											foreach ($checklists as $checklist) {
												if ($project['checklist_version'] == $checklist) {
													echo "<option selected>$checklist</option>";
												} else {
													echo "<option>$checklist</option>";
												}
											}
										}
										?>
									</select>
								</div>
							</div>
							<div class="form-group col-md">
								<div class="input-group mb-2">
									<div class="input-group-prepend">
										<div class="input-group-text">Create new <?= lang('checklist_version') ?></div>
									</div>
									<input class="form-control col-3 new_version" type="number">
									<div class="btn btn-outline-success create_checklist_version">Create</div>
								</div>
							</div>

						</div>
						<div class="form-group">

							<label>Checklist Data</label><br>
							<label>Last column is function mark, columns separated by ';'.
								<br> Functions: HD = Table Header | QC = QC Select | N = Name Selection | I = data input</label>
							<textarea class="form-control data" name='data' rows="10" cols="170"><?= $project['data'] ?></textarea></br>
						</div>
						<div class="form-group">
							<label>Scan Data</label><br>
							<label>Last column is function mark, columns separated by ';'. Functions: HD = Table Header </label>
							<textarea class="form-control" name='scans' rows="5" cols="170" placeholder="PN;SN;HD"><?= $project['scans'] ?></textarea></br>
						</div>
					</div>
				</div>
				<input type='submit' class="btn btn-info btn-block submit" name='submit' value='Submit'></ <?php echo form_close(); ?> </center>
			<?php } ?>
	</div>
</main>
<script>
	$(document).ready(function() {

	});
	$('.create_checklist_version').on('click', function() {
		$.post("/projects/create_checklist_version", {
			project_id: <?= $project['id'] ?>,
			version: $('.new_version').val()
		}).done(function(o) {
			console.log('new version created');
			console.log(o);
			location.reload();
		});
	})

	$('.checklist_version').on("change", function() {
		$.post("/projects/get_checklist_version/<?= $project['id'] ?>", {
			version: $(this).val()
		}).done(function(o) {
			$('.data').text(o);
		});
	});

	//Uploader assembly
	if ($("#assembly_upload").length) {
		$("#assembly_upload").fileupload({
			autoUpload: true,
			add: function(e, data) {
				data.submit();
			},
			progress: function() {
				$("#upload_spinner").css("display", "inherit");
			},
			done: function(e, data) {
				if (data.result.includes("error")) {
					if (data.result.includes("filetype")) {
						alert("אין אפשרות להעלות קובץ מסוג הזה, אפשר רק קבצי מסוג PDF!");
					} else {
						alert(data.result.replace(/<\/?[^>]+(>|$)/g, ""));
					}
				}
				$('.submit').click();
			}
		});
	}

	//Uploader atp
	if ($("#atp_upload").length) {
		$("#atp_upload").fileupload({
			autoUpload: true,
			add: function(e, data) {
				data.submit();
			},
			progress: function() {
				$("#upload_spinner").css("display", "inherit");
			},
			done: function(e, data) {
				if (data.result.includes("error")) {
					if (data.result.includes("filetype")) {
						alert("אין אפשרות להעלות קובץ מסוג הזה, אפשר רק קבצי מסוג PDF!");
					} else {
						alert(data.result.replace(/<\/?[^>]+(>|$)/g, ""));
					}
				}
				$('.submit').click();
			}
		});
	}

	//Uploader packing
	if ($("#packing_upload").length) {
		$("#packing_upload").fileupload({
			autoUpload: true,
			add: function(e, data) {
				data.submit();
			},
			progress: function() {
				$("#upload_spinner").css("display", "inherit");
			},
			done: function(e, data) {
				if (data.result.includes("error")) {
					if (data.result.includes("filetype")) {
						alert("אין אפשרות להעלות קובץ מסוג הזה, אפשר רק קבצי מסוג PDF!");
					} else {
						alert(data.result.replace(/<\/?[^>]+(>|$)/g, ""));
					}
				}
				$('.submit').click();
			}
		});
	}
</script>