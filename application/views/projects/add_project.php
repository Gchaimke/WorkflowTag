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
				<h2 class="display-3"><?= lang('add_project') ?><?= $client['name'] ?></h2>
			</center>
		</div>
	</div>
	<div class="container">
		<center>
			<?php
			if (isset($message_display)) {
				echo "<div class='alert alert-danger' role='alert'>";
				echo $message_display . '</div>';
			}
			if (validation_errors()) {
				echo "<div class='alert alert-danger' role='alert'>" . validation_errors() . "</div>";
			}
			?>

			<?php echo form_open('projects/add_project/' . $client['id'], 'class=user-create'); ?>
			<input type="hidden" name='data' value=''>
			<input type="hidden" name='scans' value=''>
			<div class="input-group">
				<div class="input-group-prepend">
					<div class="input-group-text"><?= lang('project_name') ?></div>
				</div>
				<input type="text" class="form-control" name='project'>
			</div>
			<label>yy = Year | mm = Month | dm = D-fend Month | ww = week | x,xx,xxx,xxxx = Serialized number | pattern = AVxxx-mm-yy</label>
			<div class="input-group">
				<div class="input-group-prepend">
					<div class="input-group-text"><?= lang('Serial template') ?></div>
				</div>
				<input type="text" name="template" value="AVxxx-mm-yy" class="form-control">
			</div>
			<div class="input-group my-3">
				<div class="input-group-prepend">
					<div class="input-group-text">
						<input type="checkbox" name="restart_serial">
						<div class="mx-2"><?= lang('Serial number restarts every month') ?></div>
					</div>
				</div>
			</div>
			<div class="input-group">
				<div class="input-group-prepend">
					<div class="input-group-text"><?= lang('project_num') ?></div>
				</div>
				<input type="text" class="form-control" name='project_num'>
			</div>
			<input type='submit' class="btn btn-info btn-block my-3" name='submit' value='<?= lang('add')." ". lang('project')?>'>
			<?php echo form_close(); ?>
		</center>
	</div>
</main>