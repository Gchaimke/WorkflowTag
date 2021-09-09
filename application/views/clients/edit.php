<?php
if (isset($this->session->userdata['logged_in'])) {
	if ($this->session->userdata['logged_in']['role'] != "Admin") {
		header("location: /");
	}
}

if (isset($id)) {
	//print_r($name);
?>
	<main role="main">
		<div class="jumbotron">
			<div class="container">
				<center>
					<h2 class="display-3">Edit Client: <?= $name ?></h2>
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
				?>
				<?= form_open("clients/edit/$id", 'class=user-create'); ?>
				<input type='hidden' name='id' value="<?= $id ?>">
				<input id='client_name' type='hidden' name='name' value="<?= $name ?>"></hr>
				<div class="form-group mb-3">
					<label>Select Logo : </label>
					<input id="logo_path" type='hidden' name='logo' value="<?= $logo ?>">
					<button class="btn btn-outline-secondary" type="button" onclick="document.getElementById('browse').click();">Upload</button>
					<img id="logo_img" class="img-thumbnail" src="<?= $logo ?>" onclick="document.getElementById('browse').click();">
					<input id="browse" style="display:none;" type="file" onchange="snapLogo()"></hr>
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
				<input type='submit' class="btn btn-info" name='submit' value='Update'>
				<?= form_close(); ?>
			<?php } ?>
			</center>
		</div>
	</main>
	<script>
		var client = document.getElementById("client_name").value;
		var ext = '';
	</script>