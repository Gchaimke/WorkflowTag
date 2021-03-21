<?php
$id = $checklist[0]['id'];
$project = $checklist[0]['project'];
$checklist_client = $checklist[0]['client'];
$serial = $checklist[0]['serial'];
$checklist_data = $checklist[0]['data'];
$log = $checklist[0]['log'];
$progress = $checklist[0]['progress'];
$assembler = $checklist[0]['assembler'];
$qc = $checklist[0]['qc'];
$scans = $checklist[0]['scans'];
$date = $checklist[0]['date'];
$note = $checklist[0]['note'];
$logo = $client[0]['logo'];
$client = $client[0]['name'];
$pictures = 0;

if (isset($this->session->userdata['logged_in'])) {
	$username = ($this->session->userdata['logged_in']['name']);
	if ($checklist[0]['pictures'] != '') {
		$pictures = $checklist[0]['pictures'];
	}
}
?>
<link rel="stylesheet" href="<?php echo base_url('assets/css/checklist_create.css?' . filemtime('assets/css/checklist_create.css')); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/css/print.css?' . filemtime('assets/css/print.css')); ?>">
<main role="main" class="container">
	<nav id="navbar" class="navbar checklist navbar-light bg-light">
		<?php echo "<img class='img-thumbnail checklist-logo' src='$logo'>" ?>
		<b id="project" class="navbar-text mobile-hide" href="#">Project: <?php echo $project ?></b>
		<b id="sn" class="navbar-text" href="#">SN: <?php echo $serial ?></b>
		<b id="date" class="navbar-text mobile-hide" href="#">Date: <?php echo $date ?></b>
		<ul class="nav navbar-nav navbar-right">
			<li class="nav-item">
				<a class="btn btn-info mr-3" href="#scansTable"><i class="fa fa-list"></i></a>
				<button id="snap1" class="btn btn-info" onclick="document.getElementById('browse').click();"><i class="fa fa-camera"></i></button>
				<?php echo form_open('production/save_checklist/' . $id . '?sn=' . $serial, 'id=ajax-form', 'class=saveData'); ?>
				<input id='input_data' type='hidden' name='data' value="<?php echo $checklist_data ?>">
				<input id='input_progress' type='hidden' name='progress' value="<?php echo $progress ?>">
				<input id='assembler' type='hidden' name='assembler' value="<?php echo $assembler ?>">
				<input id="input_qc" type='hidden' name='qc' value="<?php echo $qc ?>">
				<input type='hidden' name='serial' value="<?php echo $serial ?>">
				<input type='hidden' name='client' value="<?php echo $client ?>">
				<input type='hidden' name='project' value="<?php echo $project ?>">
				<input type='hidden' name='date' value="<?php echo $date ?>">
				<input id="input_log" type='hidden' name='log' value="<?php echo $log ?>">
				<input id="input_scans" type='hidden' name='scans' value="<?php echo $scans ?>">
				<input id="input_note" type='hidden' name='note' value="<?php echo $note ?>">
				<input id="picrures_count" type='hidden' name='pictures' value="<?php echo $pictures ?>">
				<button id="save" type='submit' class="btn btn-success navbar-btn " value="Save"><i class="fa fa-save"></i></button>
				</form>
			</li>
		</ul>
		<div class="progress fixed-bottom">
			<div id="progress-bar" class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 0" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
		</div>
	</nav>
	<div id="form-messages" class='alert hidden' role='alert'></div>
	<div id="workTable">
		<?php echo $checklist_rows ?>
	</div>
	<div id="scansTable">
		<?php echo $scans_rows ?>
	</div>
	<div class="form-row">
		<div class="input-group mb-2 col-12">
			<div class="input-group-prepend">
				<div class="input-group-text">Note</div>
			</div>
			<textarea id="note" type='text' rows="2" class="form-control" name='note'><?php echo $note ?></textarea>
		</div>
	</div>

	<div id="photo-stock" class="container">
		<center>
			<h2>System Photos</h2>
		</center>
		<div id="photo-messages" class='alert hidden' role='alert'></div>
		<?php
		$working_dir = 'Uploads/' . $checklist_client . '/' . $project . '/' . $serial . '/';
		echo "<script>
				  var photoCount=0;
				  var log ='$log';
                  var id='$id';
                  var project='$project';
                  var serial='$serial';
                  var assembler ='$username';
                  var client='$client';
                  var working_dir='$working_dir';
            </script>";  //pass PHP data to JS
		if (file_exists("./$working_dir")) {
			if ($handle = opendir("./$working_dir")) {
				while (false !== ($entry = readdir($handle))) {
					if ($entry != "." && $entry != ".." && pathinfo($entry, PATHINFO_EXTENSION) == 'jpeg' && PATHINFO_FILENAME != '') {
						echo '<span id="' . pathinfo($entry, PATHINFO_FILENAME) .
							'" onclick="delPhoto(this.id)" class="btn btn-danger delete-photo fa fa-trash"> ' .
							pathinfo($entry, PATHINFO_FILENAME) . '</span><img id="' . pathinfo($entry, PATHINFO_FILENAME) .
							'" src="/' . $working_dir . $entry . '" class="respondCanvas" >';
						echo '<script>photoCount++</script>';
					}
				}
				closedir($handle);
			}
		}
		?>
	</div>
	<input id="browse" style="display:none;" type="file" onchange="snapPhoto()" multiple>
	<div id="preview"></div>
</main>
<script>
	window.onscroll = function() {
		myFunction()
	};

	var navbar = document.getElementById("navbar");
	var sticky = navbar.offsetTop;

	function myFunction() {
		if (window.pageYOffset >= sticky) {
			navbar.classList.add("sticky-top")
		} else {
			navbar.classList.remove("sticky-top");
		}
	}
</script>