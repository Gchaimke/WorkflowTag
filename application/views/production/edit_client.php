<?php
if (isset($this->session->userdata['logged_in'])) {
	if ($this->session->userdata['logged_in']['role'] != "Admin") {
		header("location: /dashboard");
	}
}
?>
<main role="main">
	<div class="container">
		<div class="jumbotron">
			<div class="container">
				<center>
					<h2 class="display-3">Edit Client</h2>
				</center>
			</div>
		</div>
		<center>
			<?php
			$id = "";
			$client = "";
			if (isset($message_display)) {
				echo "<div class='alert alert-danger' role='alert'>";
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
			}
			?> 
			<?php echo form_open("production/edit_client/$id", 'class=user-create'); ?>
			<input type='hidden' name='id' value="<?php echo $id ?>">
			<input type='text' class="form-control" name='name' value="<?php echo $client ?>" disabled></br>
			<div class="form-group"><label>Projects</label>
			<input type='text' class="form-control" name='projects' value="<?php echo $projects ?>"></div>
			<input type='submit' class="btn btn-info btn-block" name='submit' value='Submit'>
			<?php echo form_close(); ?>
		</center>
	</div>
</main>