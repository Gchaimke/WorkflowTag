<?php
if (isset($this->session->userdata['logged_in'])) {
	if ($this->session->userdata['logged_in']['role'] != "Admin") {
		header("location: /");
	}
}
?>
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
					<label>yy = Year | mm = Month | x,xx,xxx,xxxx = Serialized number | pattern = AVxxx-mm-yy</label>
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
				<?php //TODO: add assembly upload 
				?>
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
						<select class="form-control col-3 checklist_version" name='checklist_version'>
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
				<input type='submit' class="btn btn-info btn-block" name='submit' value='Submit'></ <?php echo form_close(); ?> </center>
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
			$('form').submit();
		});
	})

	$('.checklist_version').on("change", function(){
		$.post("/projects/get_checklist_version/<?= $project['id'] ?>", {
			version: $(this).val()
		}).done(function(o) {
			$('.data').text(o);
		});
	})
</script>