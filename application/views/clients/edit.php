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
			}
			?>
			<?php echo form_open("clients/edit/$id", 'class=user-create'); ?>
			<input type='hidden' name='id' value="<?php echo $id ?>">
			<input type='text' class="form-control" name='name' value="<?php echo $client ?>" disabled></br>
			<div class="form-group"><label>Projects</label>
				<textarea name="projects" class="form-control" cols="40" rows="5"><?php echo $projects ?></textarea>
			</div>
			<input type='submit' class="btn btn-info btn-block" name='submit' value='Update'>
			<?php echo form_close(); ?>
		</center>
	</div>
</main>