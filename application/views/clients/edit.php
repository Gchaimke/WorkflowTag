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
				<h2 class="display-3">Edit Client</h2>
			</center>
		</div>
	</div>
	<div class="container">
		<center>
			<?php
			$id = "";
			$client = "";
			if (isset($message_display)) {
				echo "<div class='alert alert-success' role='alert'>";
				echo $message_display . '</div>';
			}
			if (validation_errors()) {
				echo "<div class='alert alert-danger' role='alert'>" . validation_errors() . "</div>";
			}

			if (isset($clients)) {
				//print_r($clients);
				$id = $clients[0]['id'];
				$client = $clients[0]['name'];
				$projects = $clients[0]['projects'];
				$logo = $clients[0]['logo'];
				$status = $clients[0]['status'];
			}
			?>
			<?php echo form_open("clients/edit/$id", 'class=user-create'); ?>
			<input type='hidden' name='id' value="<?php echo $id ?>">
			<label>Client</label><input id='client_name' type='text' class="form-control" name='name' value="<?php echo $client ?>"></hr>
			<label>Logo</label>
			<div class="input-group mb-3">
				<input id="logo_path" type='text' class="form-control" name='logo' value="<?php echo $logo ?>">
				<div class="input-group-append">
					<button class="btn btn-outline-secondary" type="button" onclick="document.getElementById('browse').click();">Upload</button>
				</div>
			</div>
			<img id="logo_img" class="img-thumbnail" src="<?php echo $logo ?>" onclick="document.getElementById('browse').click();">
			<input id="browse" style="display:none;" type="file" onchange="snapLogo()"></hr>

			<div class="form-group"><label>Client Projects</label>
				<textarea name="projects" class="form-control" cols="40" rows="5"><?php echo $projects ?></textarea>
			</div>
			<div class="form-row">
				<div class="input-group mb-2">
					<div class="input-group-prepend">
						<div class="input-group-text">Status</div>
					</div>
					<select class="form-control" name='status'>
						<?php
						$arr = array(
							'0' => 'Old',
							'1' => 'Active',
						);
						print_r($arr);
						foreach ($arr as $value => $cstatus) {
							if ($value == $status) {
								echo '<option value="' . $value . '" selected>' . $cstatus . '</option>';
							} else {
								echo '<option value="' . $value . '">' . $cstatus . '</option>';
							}
						}
						?>
					</select>
				</div>
			</div>
			<input type='submit' class="btn btn-info btn-block" name='submit' value='Update'>
			<?php echo form_close(); ?>
		</center>
	</div>
</main>
<script>
	var client = document.getElementById("client_name").value;
	var ext = '';
</script>