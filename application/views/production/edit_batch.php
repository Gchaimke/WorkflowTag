<?php
$id = $ids;
$serials = '';
$checklist_data = $checklists[0]['data'];
$log = $checklists[0]['log'];
$progress = $checklists[0]['progress'];
$assembler = $checklists[0]['assembler'];
$qc = $checklists[0]['qc'];
$scans = $checklists[0]['scans'];
$date = $checklists[0]['date'];

$this->load->helper('cookie');
$session = get_cookie('ci_session');

foreach ($checklists as $checklist) {
	$serials .= $checklist['serial'] . '|';
}
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
	<button id="snap" class="btn btn-info"><i class="fa fa-camera"></i></button>
	<b id="project" class="navbar-text mobile-hide" href="#">Project: <?php echo $project ?></b>
	<b id="sn" class="navbar-text" href="#">SN: <?php echo $serials ?></b>
	<b id="date" class="navbar-text mobile-hide" href="#">Date: <?php echo $date ?></b>
	<ul class="nav navbar-nav navbar-right">
		<li class="nav-item">
			<?php echo form_open('production/save_batch_checklists/' . $ids, 'class=saveData'); ?>
			<input id="input_data" type='hidden' name='data' value="<?php echo $checklist_data ?>">
			<input id="input_progress" type='hidden' name='progress' value="<?php echo $progress ?>">
			<input type='hidden' name='assembler' value="<?php echo $assembler ?>">
			<input id="input_qc" type='hidden' name='qc' value="<?php echo $qc ?>">
			<input id="input_log" type='hidden' name='log' value="<?php echo $log ?>">
			<input id="input_scans" type='hidden' name='scans' value="<?php echo $scans ?>">
			<button id="save" type='submit' class="btn btn-success navbar-btn " value="Save"><i class="fa fa-save"></i></button>
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
		<?php echo $checklist_rows ?>
	</div>
	<div id="scansTable">
		<?php echo $scans_rows ?>
	</div>
	<div id="photo-stock" class="container">
		<canvas id="canvas" style="display:none;" width="1920" height="1080"></canvas>
		<?php
		foreach ($checklists as $checklist) {
			$working_dir = '/Uploads/' . $project . '/' . $checklist['serial'] . '/';
			echo "<script>var photoCount=0; var id='$id'; var pr='$project'; var sn='" . $checklist['serial'] . "'; var ci_session='$session';"; //pass PHP data to JS
			echo "var log='$log'; var assembler =' $assembler'</script>";  //pass PHP data to JS
			if (file_exists(".$working_dir")) {
				if ($handle = opendir(".$working_dir")) {
					echo '<center><h2>System Photos</h2></center>';
					while (false !== ($entry = readdir($handle))) {
						if ($entry != "." && $entry != ".." && pathinfo($entry, PATHINFO_EXTENSION) == 'png') {
							echo '<span id="' . pathinfo($entry, PATHINFO_FILENAME) . '" onclick="delPhoto(this.id)" class="btn btn-danger delete-photo">Delete '.pathinfo($entry, PATHINFO_FILENAME).'</span><img id="' . pathinfo($entry, PATHINFO_FILENAME) . '" src="' . $working_dir . $entry . '" class="respondCanvas" >';
							echo '<script>photoCount++</script>';
						}
					}
					closedir($handle);
				}
			}
		}
		?>
	</div>
</main>