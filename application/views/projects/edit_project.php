<?php
if (isset($this->session->userdata['logged_in'])) {
	if ($this->session->userdata['logged_in']['role'] != "Admin") {
		header("location: /");
	}
}
$file = "./Uploads/" . urldecode($project['client']) . "/" . $project['project'] . "/assembly.pdf";
if (file_exists($file)) {
	$dispaly = "";
} else {
	$dispaly = "hidden";
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
				<div class="form-group">
				<label>yy = Year | mm = Month | dm = D-fend Month | ww = week | x,xx,xxx,xxxx = Serialized number | pattern = AVxxx-mm-yy</label>
					<div class="input-group">
						<div class="input-group-prepend">
							<div class="input-group-text"><?= lang('Serial template') ?></div>
						</div>
						<input type="text" name="template" value="<?= $project['template'] ?>" class="form-control">
					</div>
					<div class="input-group my-3">
						<div class="input-group-prepend">
							<div class="input-group-text">
								<input type="checkbox" name="restart_serial" <?= $project['restart_serial'] != null ? "checked" : "" ?>>
								<div class="mx-2"><?= lang('Serial number restarts every month') ?></div>
							</div>
						</div>
					</div>
				</div>
				<a class="btn btn-warning <?= $dispaly ?>" target="_blank" href="/<?= $file ?>"><i class="fas fa-file-pdf"></i> <?= lang('assembly') ?> </a>
				<div class="btn btn-info mx-3 not-print" onclick="document.getElementById('upload').click();"><i class="fa fa-file"></i> Upload Assembly</div>
				<input id="upload" type="file" name="files" data-url="/projects/assembly_upload?client=<?= $project['client'] ?>&project=<?= $project['project'] ?>" hidden />
				<hr>
				<div class="form-group">
					<div class="input-group mb-2">
						<div class="input-group-prepend">
							<div class="input-group-text">Create <?= lang('checklist_version') ?></div>
						</div>
						<input class="form-control col-3 new_version" type="number" step="0.01">
						<div class="btn btn-outline-success create_checklist_version">Create</div>
					</div>
				</div>
				<div class="form-group">
					<div class="input-group mb-2">
						<div class="input-group-prepend">
							<div class="input-group-text"><?= lang('checklist_version') ?></div>
						</div>
						<select class="form-control col-4 checklist_version" name='checklist_version'>
						<option>Select Version</option>
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
				<div class="form-group">

					<label>Checklist Data</label><br>
					<label>Last column is function mark, columns separated by ';'.
						<br> Functions: HD = Table Header | QC = QC Select | V = Regular Checkbox | N = Name Selection | I = data input</label>
					<textarea class="form-control data" name='data' rows="10" cols="170"><?= $project['data'] ?></textarea></br>
				</div>
				<div class="form-group">
					<label>Scan Data</label><br>
					<label>Last column is function mark, columns separated by ';'. Functions: HD = Table Header </label>
					<textarea class="form-control" name='scans' rows="5" cols="170"><?= $project['scans'] ?></textarea></br>
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
			$('.submit').click();
		});
	})

	$('.checklist_version').on("change", function() {
		$.post("/projects/get_checklist_version/<?= $project['id'] ?>", {
			version: $(this).val()
		}).done(function(o) {
			$('.data').text(o);
		});
	});

	//Uploader for assembly
	if ($("#upload").length) {
		$("#upload").fileupload({
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