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
			}
			?>
			<?php echo form_open("clients/edit/$id", 'class=user-create'); ?>
			<input type='hidden' name='id' value="<?php echo $id ?>">
			<label>Client</label><input type='text' class="form-control" name='name' value="<?php echo $client ?>" disabled></br>
			<label>Logo</label></br>
			<div id="photo-stock" class="container"></div>
			<div id="preview"></div>
			<img class="img-thumbnail" src="<?php echo $logo ?>" onclick="document.getElementById('browse').click();">
			<input id="browse" style="display:none;" type="file" onchange="snapPhoto()" multiple>
			<div id="preview"></div>
			<div class="form-group"><label>Projects</label>
				<textarea name="projects" class="form-control" cols="40" rows="5"><?php echo $projects ?></textarea>
			</div>
			<input type='submit' class="btn btn-info btn-block" name='submit' value='Update'>
			<?php echo form_close(); ?>
		</center>
	</div>
</main>
<script>
	var client = '<?php echo $client ?>';

	function snapPhoto() {
		var preview = document.querySelector('#preview');
		var files = document.querySelector('input[type=file]').files;

		function readAndPreview(file) {
			// Make sure `file.name` matches our extensions criteria
			if (/\.(jpe?g|jpeg|jpg|png|gif)$/i.test(file.name)) {
				var reader = new FileReader();
				reader.addEventListener("load", function() {
					var image = new Image();
					image.title = file.name;
					image.src = this.result;
					preview.appendChild(image);
					saveToServer(this.result);					
				}, false);
				reader.readAsDataURL(file);
			}
		}
		if (files) {
			[].forEach.call(files, readAndPreview);
		}
	}

	function saveToServer(file) {
		$.post("/clients/logo_upload", {
			data: file,
			client: client
		}).done(function(o) {
			console.log('photo saved to server.');
			console.log(o);
			//$("#photo-stock").append('<img id="logo-image" src="/Uploads/Clients/' + client + '_logo' + '.jpeg' + '" class="respondCanvas" >');
		});
	}
</script>