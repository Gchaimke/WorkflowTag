<?php
$id = $checklist[0]['id'];
$project = $checklist[0]['project'];
$serial = $checklist[0]['serial'];
$checklist_data = $checklist[0]['data'];
$log = $checklist[0]['log'];
$progress = $checklist[0]['progress'];
$assembler = $checklist[0]['assembler'];
$qc = $checklist[0]['qc'];
$date = $checklist[0]['date'];

if (isset($this->session->userdata['logged_in'])) {
	$username = ($this->session->userdata['logged_in']['name']);
	$role = ($this->session->userdata['logged_in']['role']);
	if ($assembler != $username) {
		$assembler = $username;
	}
}
?>
<link rel="stylesheet" href="<?php echo base_url('assets/css/checklist_create.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/css/print.css'); ?>">
<nav class="navbar checklist navbar-light fixed-top bg-light">
	<button id="snap" class="btn btn-info">Snap Photo</button>
	<b id="project" class="navbar-text mobile-hide" href="#">Project: <?php echo $project ?></b>
	<b id="sn" class="navbar-text" href="#">SN: <?php echo $serial ?></b>
	<b id="date" class="navbar-text mobile-hide" href="#">Date: <?php echo $date ?></b>
	<ul class="nav navbar-nav navbar-right">
		<li class="nav-item">
			<?php echo form_open('production/save_checklist/' . $id.'?sn='.$serial, 'class=user-create'); ?>
			<input id="input_data" type='hidden' name='data' value="<?php echo $checklist_data ?>">
			<input id="input_progress" type='hidden' name='progress' value="<?php echo $progress ?>">
			<input type='hidden' name='assembler' value="<?php echo $assembler ?>">
			<input id="input_qc" type='hidden' name='qc' value="<?php echo $qc ?>">
			<input id="input_log" type='hidden' name='log' value="<?php echo $log ?>">
			<input id="save" type='submit' class="btn btn-success navbar-btn" value="Save">
			</form>
		</li>
	</ul>
	<div class="progress fixed-bottom">
		<div id="progress-bar" class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 0" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
	</div>
</nav>
<main role="main" class="container">
	<?php
	if (isset($message_display)) {
		echo "<div class='alert alert-success' role='alert'>";
		echo $message_display . '</div>';
	}
	?>
	<div class="name_badges">
		<h4><span class="badge badge-secondary">Assembler: <?php echo $username ?> </span></h4>
		<h4><span class="badge badge-secondary">QC: <?php echo $qc ?></span></h4>
	</div>
	<div class="video-frame container-sm">
		<div class="controls">
			<button id="select_camera" class="btn btn-success">Select camera</button>
			<button id="close_camera" class="btn btn-danger">Close camera</button>
			<select class="form-control" id="select">
				<option></option>
			</select>
		</div>
		<video id="video" width="100%" autoplay playsinline></video>
	</div>
	<div id="workTable">
		<?php echo $data ?>
	</div>
	<div id="photo-stock" class="container-sm">
		<canvas id="canvas" style="display:none;" width="1920" height="1080"></canvas>
		<?php
		$working_dir = '/Uploads/' . $project . '/' . $serial . '/';
		echo "<script>var photoCount=0; var id='$id'; var pr='$project'; var sn='$serial';"; //pass PHP data to JS
		echo "var log='$log'; var assembler =' $assembler'</script>";  //pass PHP data to JS
		if (file_exists(".$working_dir")) {
			if ($handle = opendir(".$working_dir")) {
				while (false !== ($entry = readdir($handle))) {
					if ($entry != "." && $entry != ".." && pathinfo($entry, PATHINFO_EXTENSION) == 'png') {
						echo '<span id="' . pathinfo($entry, PATHINFO_FILENAME) . '" onclick="delPhoto(this.id)" class="btn btn-danger delete-photo">delete</span><img id="' . pathinfo($entry, PATHINFO_FILENAME) . '" src="' . $working_dir . $entry . '" class="respondCanvas" >';
						echo '<script>photoCount++</script>';
					}
				}
				closedir($handle);
			}
		}
		?>
	</div>
</main>