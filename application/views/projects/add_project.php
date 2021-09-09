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
			<div class="input-group">
				<div class="input-group-prepend">
					<div class="input-group-text"><?= lang('project_name') ?></div>
				</div>
				<input type="text" class="form-control" name='project'>
			</div>
			<label>yy = Year | mm = Month | x,xx,xxx,xxxx = Serialized number | pattern = AVxxx-mm-yy</label>
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
			<div class="form-group">
				<hr>
				<label>Checklist Data</label><br>
				<label>Last column is function mark, columns separated by ';'.<br>
					Functions: HD = Table Header | QC = QC Select | V = Regular Checkbox | N = Name Selection | I = data input </label>
				<textarea class="form-control" name='data' rows="10" cols="100">Assembly;Verify;HD&#13;&#10;checkbox 1;V&#13;&#10;checkbox 2;V&#13;&#10;Qc select;QC
                  </textarea></br>
			</div>
			<div class="form-group">
				<label>Scan Data</label><br>
				<label>Last column is function mark, columns separated by ';'. Functions: HD = Table Header </label>
				<textarea class="form-control" name='scans' rows="10" cols="100">PN;SN;HD&#13;&#10;Serail 1&#13;&#10;Serial 2</textarea></br>
			</div>
			<input type='submit' class="btn btn-info btn-block" name='submit' value='Submit'>
			<?php echo form_close(); ?>
		</center>
	</div>
</main>