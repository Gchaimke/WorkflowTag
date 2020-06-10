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
			<input id="logo_path" type='text' class="form-control" name='logo' value="<?php echo $logo ?>">
			<img id="logo_img" class="img-thumbnail" src="<?php echo $logo ?>" onclick="document.getElementById('browse').click();">
			<input id="browse" style="display:none;" type="file" onchange="snapPhoto()" multiple>
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
	var ext ='';

	function snapPhoto() {
		var logo_path = document.getElementById('logo_path');
		var logo_img = document.getElementById('logo_img');
		var files = document.querySelector('input[type=file]').files;

		function readAndPreview(file) {
			// Make sure `file.name` matches our extensions criteria
			ext = file.name.substr((file.name.lastIndexOf('.') + 1));
			if (/\.(jpe?g|png|gif)$/i.test(file.name)) {
				var reader = new FileReader();
				reader.addEventListener("load", function() {
					var image = new Image();
					image.title = file.name;
					image.src = this.result;
					saveToServer(this.result);
					logo_path.value = "/Uploads/Clients/"+client+"_logo."+ext;
					logo_img.src =   logo_path.value;
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
			client: client,
			ext : ext
		}).done(function(o) {
			console.log('photo saved to server.');
			console.log(o);
		});
	}
</script>