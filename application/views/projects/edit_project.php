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
				<div class="form-group">
					<hr>
					<label>Checklist Data</label><br>
					<label>Last column is function mark, columns separated by ';'.
						<br> Functions: HD = Table Header | QC = QC Select | V = Regular Checkbox | N = Name Selection | I = data input</label>
					<textarea class="form-control" name='data' rows="10" cols="170"><?= $project['data'] ?></textarea></br>
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