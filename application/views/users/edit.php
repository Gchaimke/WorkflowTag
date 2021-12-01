<?php
if (isset($this->session->userdata['logged_in']) && isset($user)) {
	$current_role = ($this->session->userdata['logged_in']['role']);
	if ($this->session->userdata['logged_in']['id'] != $user['id']) {
		if ($current_role != "Admin") {
			header("location: /");
		}
	}
} else {
	exit();
}
?>
<div id="form-messages" class='alert hidden' role='alert'></div>
<main role="main">
	<div class="jumbotron">
		<div class="container">
			<center>
				<h5><?= lang('edit_details') . " " . $user['name'] ?></h5>

			</center>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xl-5 col-lg-6 col-md-8 col-sm-10 mx-auto text-center form p-4">
				<?php
				if (validation_errors()) {
					echo "<div class='alert alert-danger' role='alert'>" . validation_errors() . "</div>";
				}
				?>
				<?php
				$attributes = ['class' => '', 'id' => 'ajax-form'];
				echo form_open('users/edit', $attributes); ?>
				<input type='hidden' name='id' value="<?php echo $user['id'] ?>">
				<div class="form-row">
					<div class="input-group mb-2">
						<?php
						if ($current_role == "Admin") {
							echo '<div class="input-group-prepend"><div class="input-group-text">' . lang('role') . '</div></div>';
							echo "<select class='form-select' name='role'>";
							if (isset($settings)) {
								$arr = explode(",", $settings['roles']);
								foreach ($arr as $crole) {
									$role_lang =  lang($crole);
									if ($crole == $user['role']) {
										echo "<option selected value='$crole'>$role_lang</option>";
									} else {
										echo "<option value='$crole'>$role_lang</option>";
									}
								}
								$arr = explode(",", $settings['user_roles']);
								foreach ($arr as $crole) {
									if ($crole == $user['role']) {
										echo "<option selected value='$crole'>$crole</option>";
									} else {
										echo "<option value='$crole'>$crole</option>";
									}
								}
							}
							echo "</select>";
						}
						?>
					</div>
				</div>
				<div class="form-row">
					<div class="input-group mb-2">
						<div class="input-group-prepend">
							<div class="input-group-text"><?= lang('language') ?></div>
						</div>
						<select class="form-select" name='language'>
							<?php if (isset($languages)) {
								echo "<option value='system'>" . lang('default') . "</option>";
								foreach ($languages as $lang) {
									if ($user['language'] == $lang) {
										echo "<option selected>$lang</option>";
									} else {
										echo "<option>$lang</option>";
									}
								}
							}
							?>
						</select>
					</div>
				</div>

				<div class="form-row">
					<div class="input-group mb-2">
						<div class="input-group-prepend">
							<div class="input-group-text"><?= lang('username') ?></div>
						</div>
						<input type='text' class="form-control" name='name' value="<?php echo $user['name'] ?>" <?php if ($current_role != "Admin") {
																													echo 'disabled';
																												} ?>>
					</div>
				</div>
				<div class="form-row">
					<div class="input-group mb-2">
						<div class="input-group-prepend">
							<div class="input-group-text"><?= lang('view_name') ?></div>
						</div>
						<input type='text' class="form-control" name='view_name' value="<?php echo $user['view_name'] ?>" required>
					</div>
				</div>

				<div class="form-row">
					<div class="input-group mb-2">
						<div class="input-group-prepend">
							<div class="input-group-text"><?= lang('password') ?></div>
						</div>
						<input type='password' class="form-control" name='password'>
					</div>
				</div>

				<div class="form-row">
					<div class="input-group mb-2">
						<div class="input-group-prepend">
							<div class="input-group-text"><?= lang('email') ?></div>
						</div>
						<input type='text' class="form-control ltr" name='email' value="<?php echo $user['email'] ?>">
					</div>
				</div>
				<input type='submit' class="btn btn-info" name='submit' value='<?= lang('update') ?>'>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</main>