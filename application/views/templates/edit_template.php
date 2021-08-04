<?php
if (isset($this->session->userdata['logged_in'])) {
	if ($this->session->userdata['logged_in']['role'] != "Admin") {
		header("location: /");
	}
}

if (isset($clients)) {
	echo '<script>var clients = {};' . PHP_EOL;
	echo "var curent_project ='" . $project['project'] . "';" . PHP_EOL;
	foreach ($clients as $client) {
		echo 'clients["' . $client['name'] . '"]="' . $client['projects'] . '";' . PHP_EOL;
	}
	echo '</script>';
}
?>
<main role="main">
	<div class="jumbotron">
		<div class="container">
			<center>
				<h2 class="display-3">Edit Template</h2>
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

			if (isset($project)) {
				$id = $project['id'];
				$client = $project['client'];
				$pr =  $project['project'];
				$dt = $project['data'];
				$tp =  $project['template'];
				$sd =  $project['scans'];
			?>

				<?php echo form_open("templates/edit_template/$id", 'class=user-create'); ?>
				<input type='hidden' name='id' value="<?php echo $id ?>">
				<div class="input-group mb-2">
					<div class="input-group-prepend">
						<div class="input-group-text"><?= lang('Select Client') ?></div>
					</div>
					<select id="select_client" class="form-control" name='client'>
						<?php if (isset($clients)) {
							foreach ($clients as $client_val) {
								if ($client_val['name'] == $client) {
									echo '<option selected>' . $client_val['name'] . '</option>';
								} else {
									echo '<option>' . $client_val['name'] . '</option>';
								}
							}
						}
						?>
					</select>
				</div>
				<div class="input-group mb-2">
					<div class="input-group-prepend">
						<div class="input-group-text"><?= lang('Select Project') ?></div>
					</div>
					<select id="select_project" class="form-control" name='project'>
					</select>
				</div>
				<div class="form-group">
					<label>yy = Year | mm = Month | x,xx,xxx,xxxx = Serialized number | pattern = AVxxx-mm-yy</label>
					<div class="input-group">
						<div class="input-group-prepend">
							<div class="input-group-text"><?= lang('Serial template') ?></div>
						</div>
						<input type="text" name="template" value="<?php echo $tp ?>" class="form-control">
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
					<textarea class="form-control" name='data' rows="10" cols="170"><?php echo $dt ?></textarea></br>
				</div>
				<div class="form-group">
					<label>Scan Data</label><br>
					<label>Last column is function mark, columns separated by ';'. Functions: HD = Table Header </label>
					<textarea class="form-control" name='scans' rows="5" cols="170"><?php echo $sd ?></textarea></br>
				</div>
				<input type='submit' class="btn btn-info btn-block" name='submit' value='Submit'></ <?php echo form_close(); ?> </center>
			<?php } ?>
	</div>
</main>