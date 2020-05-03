<main role="main">
	<div class="container">
		<div class="jumbotron">
			<div class="container">
				<center>
					<h2 class="display-3">Edit User</h2>
				</center>
			</div>
		</div>
		<center>
			<?php
			$id = "";
			$role = "";
			$name =  "";
			$pass = "";
			if (isset($message_display)) {
				echo "<div class='alert alert-danger' role='alert'>";
				echo $message_display . '</div>';
			}
			if (validation_errors()) {
				echo "<div class='alert alert-danger' role='alert'>" . validation_errors() . "</div>";
			}

			if (isset($user)) {
				$id = $user[0]['id'];
				$role = $user[0]['userrole'];
				$name =  $user[0]['username'];
				$pass = $user[0]['password'];
			}
			?>

			<?php echo form_open('users/edit', 'class=user-create'); ?>
			<input type='hidden' name='id' value="<?php echo $id ?>">
			<input type='text' class="form-control" name='username' value="<?php echo $name ?>" disabled></br>
			<select class="form-control" name='userrole'>
                        <?php if (isset($settings)) {
                              $arr = explode(",", $settings[0]['userroles']);
                              foreach ($arr as $crole) {
																if($crole ==$role ){
																	echo '<option selected>' . $crole . '</option>';
																}else{
																	echo '<option>' . $crole . '</option>';
																}
                                    
                              }
                        }
                        ?>
                  </select></br>
			<input type='text' class="form-control" name='password' value="<?php echo $pass ?>"></br>
			<input type='submit' class="btn btn-info btn-block" name='submit' value='update'>
			<?php echo form_close(); ?>
		</center>
	</div>
</main>