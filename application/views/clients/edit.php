<?php
if (isset($this->session->userdata['logged_in'])) {
	if ($this->session->userdata['logged_in']['role'] != "Admin") {
		header("location: /");
	}
}

if (isset($client)) {
	//print_r($name);
?>
	<main role="main">
		<div class="jumbotron">
			<div class="container">
				<center>
					<h2 class="display-3">Edit Client: <?= $client['name'] ?></h2>
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
				<?= form_open("clients/edit/" . $client['id'], 'class=user-create'); ?>
				<input type='hidden' name='id' value="<?= $client['id'] ?>">
				<input id='client_name' type='hidden' name='name' value="<?= $client['name'] ?>"></hr>
				<div class="form-group mb-3">
					<label>Select Logo : </label>
					<input id="logo_path" type='hidden' name='logo' value="<?= $client['logo'] ?>">
					<button class="btn btn-outline-secondary" type="button" onclick="document.getElementById('browse').click();">Upload</button>
					<img id="logo_img" class="img-thumbnail" src="<?= $client['logo'] ?>" onclick="document.getElementById('browse').click();">
					<input id="browse" style="display:none;" type="file" onchange="snapLogo()"></hr>
				</div>
				<div class="form-row">
					<div class="form-floating mb-2">
						<select class="form-select" name='status'>
							<?php
							$arr = array(
								'0' => 'Old',
								'1' => 'Active',
							);
							foreach ($arr as $value => $cstatus) {
								if ($value == $client['status']) {
									echo '<option value="' . $value . '" selected>' . $cstatus . '</option>';
								} else {
									echo '<option value="' . $value . '">' . $cstatus . '</option>';
								}
							}
							?>
						</select>
						<label>Status</label>
					</div>
				</div>
				<div class="row">
					<div><?= lang('users') ?></div>
					<div class='form-check text-start'>
						<input class='form-check-input' type="checkbox" id="check_all"><label class='form-check-label' for='users'>Select all</label>
					</div>
					<div class="users_check">
						<?php
						$user_clients = explode(",", $client['users']);
						foreach ($users as $key => $user) {
							echo "<div class='form-check'>";
							if (in_array($user['id'], $user_clients)) {
								$checked = "checked";
							} else {
								$checked = "";
							}
							echo "<input class='form-check-input' type='checkbox' name='users[{$user['id']}]' value='{$user['id']}' aria-label='{$user['name']}' $checked>
						<label class='form-check-label' for='users'>{$user['name']} ({$user['role']})</label>";
							echo "</div>";
						}
						?>
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
		$("#check_all").click(function() {
			$('input:checkbox').not(this).prop('checked', this.checked);
		});
	</script>