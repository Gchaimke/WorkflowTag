<?php
if (isset($this->session->userdata['logged_in'])) {
	$username = ($this->session->userdata['logged_in']['name']);
	$user_id = ($this->session->userdata['logged_in']['id']);
	$role = ($this->session->userdata['logged_in']['role']);

	if ($checklist['pictures'] != '') {
		$pictures = $checklist['pictures'];
	}
	$id = $checklist['id'];
	$serial = $checklist['serial'];
	$checklist_data = $checklist['data'];
	$log = $checklist['log'];
	$progress = $checklist['progress'];
	$assembler = $checklist['assembler'];
	$qc = $checklist['qc'];
	$scans = $checklist['scans'];
	$date = $checklist['date'];
	$note = $checklist['note'];
	$logo = $client['logo'];
	$client_name = $client['name'];
	$pictures = 0;

	$file = "./Uploads/" . $client_name . "/" . $project_name . "/assembly.pdf";
	if (file_exists($file)) {
		$assembly = true;
	} else {
		$assembly = false;
	}

	$file = "./Uploads/" . $client_name . "/" . $project_name . "/assembly.pdf";
	$dispaly_file = "hidden";
	$dispaly_link = "hidden";
	if ($project['assembly']) {
		$dispaly_link = "d-none d-xl-inline me-3";
	} else {
		if (file_exists($file)) {
			$dispaly_file = "d-none d-xl-inline me-3";
		} else {
		}
	}

	$revision = "";
	if (isset($checklist['version'])) {
		$re = '/rev_(?<rev>\d+)\.txt/m';
		preg_match($re, $checklist['version'], $rev_arr,);

		$revision = $rev_arr['rev'];
	}

	$working_dir = 'Uploads/' . $client_name . '/' . $project_name . '/' . $serial . '/';
} else {
	exit();
}
?>
<script src="<?php echo base_url('assets/js/jQUpload/jquery.ui.widget.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jQUpload/jquery.iframe-transport.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jQUpload/jquery.fileupload.js'); ?>"></script>
<link rel="stylesheet" href="<?= base_url('assets/css/checklist_create.css?' . filemtime('assets/css/checklist_create.css')); ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/print.css?' . filemtime('assets/css/print.css')); ?>">
<main role="main" class="container ltr">
	<div class="upload_photo_spinner text-center" style="display: none;">
		<div class="spinner-border" role="status">
			<span class="visually-hidden">Loading...</span>
		</div>
	</div>
	<nav id="navbar" class="navbar checklist navbar-light bg-light px-3">
		<div class="checklist-logo">
			<?= "<img class='img-thumbnail' src='$logo'>" ?>
		</div>
		<div class="checklist-data">
			<b id="project" class="navbar-text mobile-hide" href="#">Project Name: <?= $project_name ?></b>
			<b id="project_num" class="navbar-text mobile-hide" style="display: none;">PN: <?= $project_num ?></b>
			<b id="project_assembly" class="navbar-text" style="display: none;">Assembly: <?= $project_name ?>_<?= $project_num ?>_REV_<?= $revision ?>.pdf</b>
			<b id="paka" class="navbar-text mobile-hide" href="#">WO: <?= $checklist['paka'] ?></b>
			<b id="sn" class="navbar-text" href="#">SN: <?= $serial ?></b>
			<b id="date" class="navbar-text mobile-hide" href="#">Date: <?= $date ?></b>
		</div>
		<ul class="nav navbar-nav navbar-right">
			<li class="nav-item">
				<div class="print-hide">
					<a class="btn btn-warning  <?= $dispaly_link ?>" target="_blank" href="<?= $project['assembly'] ?>"><i class="fas fa-file-pdf"></i> <?= lang('assembly') ?> </a>
					<a class="btn btn-warning  <?= $dispaly_file ?>" target="_blank" href="/<?= $file ?>"><i class="fas fa-file-pdf"></i> <?= lang('assembly') ?> </a>
				</div>
				<?php if ($role != 'Assembler') { ?>
					<button class="btn btn-warning me-3 qc_note_btn"><i class="fas fa-sticky-note"></i></button>
				<?php } ?>
				<a class="btn btn-info me-3" href="#scansTable"><i class="fa fa-list"></i></a>
				<button class="btn btn-dark mx-3 not-print" onclick="document.getElementById('upload').click();"><i class="fas fa-paperclip"></i></button>
				<button id="snap1" class="btn btn-info" onclick="document.getElementById('browse').click();"><i class="fa fa-camera"></i></button>
				<?= form_open('production/save_checklist/' . $id, 'id=ajax-form', 'class=saveData'); ?>
				<input id='input_data' type='hidden' name='data' value="<?= $checklist_data ?>">
				<input id='version' type='hidden' name='version' value="<?= $checklist['version'] ?>">
				<input id='input_progress' type='hidden' name='progress' value="<?= $progress ?>">
				<input id='assembler' type='hidden' name='assembler' value="<?= $assembler ?>">
				<input id="input_qc" type='hidden' name='qc' value="<?= $qc ?>">
				<input type='hidden' name='serial' value="<?= $serial ?>">
				<input type='hidden' name='client' value="<?= $client_name ?>">
				<input type="hidden" name="client_id" value="<?= $client['id'] ?>" />
				<input type='hidden' name='project' value="<?= $project_name ?>">
				<input type='hidden' name='paka' value="<?= $checklist['paka'] ?>">
				<input type='hidden' name='logo' value="<?= $logo ?>">
				<input type='hidden' name='date' value="<?= $date ?>">
				<input id="input_log" type='hidden' name='log' value="<?= $log ?>">
				<input id="input_scans" type='hidden' name='scans' value="<?= $scans ?>">
				<input id="input_note" type='hidden' name='note' value="<?= $note ?>">
				<input id="picrures_count" type='hidden' name='pictures' value="<?= $pictures ?>">
				<button id="save" type='submit' class="btn btn-success navbar-btn " value="Save"><i class="fa fa-save"></i></button>
				<?= form_close(); ?>
			</li>
		</ul>
		<div class="progress fixed-bottom">
			<div id="progress-bar" class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 0" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
		</div>
	</nav>
	<div id="form-messages" class='alert hidden' role='alert'></div>
	<div id="workTable">
		<?= $checklist_rows ?>
	</div>
	<div id="scansTable">
		<form action="/production/save_scans/<?= $id ?>" id="ajax-form-scans" method="post" accept-charset="utf-8">
			<?= $scans_rows ?>
		</form>
	</div>
	<div class="mt-2" id="note_row">
		<div class="form-floating">
			<textarea id="note" type='text' class="form-control" name='note' style="height: 100px;"><?= $note ?></textarea>
			<label>Note</label>
		</div>
	</div>

	<div class="row m-3">
		<center>
			<h3>System Files</h3>
		</center>
		<?php include("application/views/storage/view_files.php") ?>
	</div>

	<div id="photo-stock" class="container">
		<center>
			<h3>System Photos</h3>
		</center>
		<div id="photo-messages" class='alert hidden' role='alert'></div>
		<?php
		echo "<script>
				var photoCount=0;
				var log ='$log';
				var id='$id';
				var project='$project_name';
				var serial='$serial';
				var assembler ='$username';
				var client='$client_name';
				var working_dir='$working_dir';
				var progress='$progress';
            </script>";  //pass PHP data to JS
		include("application/views/storage/view_pictures.php");

		?>
	</div>
	<input id="browse" type="file" onchange="snapPhoto()" name="photos" hidden>
	<input id="upload" type="file" name="files" data-url="/storage/save_file/<?= $id ?>?type=checklist" hidden />
	<div id="preview"></div>

	<div id="qc-checklist-note" style="display:none">
		<span class="btn btn-danger qc_note_btn close-notes"><i class="fas fa-times"></i></span>
		<?= form_open('production/add_qc_note/', 'id=ajax-form-qc'); ?>
		<input type="hidden" name="checklist_id" value="<?= $id ?>" />
		<input type="hidden" name="checklist_sn" value="<?= $serial ?>" />
		<input type="hidden" name="qc_id" value="<?= $user_id ?>" />
		<input type="hidden" name="client_id" value="<?= $client['id'] ?>" />
		<input type="hidden" name="project" value="<?= $project_name ?>" />

		<div class="row mb-3">
			<div class="col">
				<div class="form-floating">
					<input type="text" name="row" placeholder="checklist row" class="form-control col-md-6 mb-2" />
					<label for="row" class="m-1 text-black">Row</label>
				</div>
			</div>
			<div class="col">
				<div class="form-floating">
					<select class='form-select' name="assembler_id">
						<option value='0'>Select</option>
						<?php foreach ($users as $user) {
							if (strpos($checklist_data, $user['name']) !== false)
								echo "<option value=" . $user['id'] . ">" . $user['name'] . "</option>";
						}
						?>
					</select>
					<label for="row" class="m-1 text-black">User</label>
				</div>
			</div>
			<div class="col">
				<div class="form-floating">
					<select class='form-select' name="fault">
						<option value='0'>Select</option>
						<?php
						$faults = array("Cables Routing", "Connector Connection", "Screws", "Assembly", "Labels & Documentetion", "Scratches & Stains");
						foreach ($faults as $fault) {
							if (isset($note->fault) && $fault == $note->fault) {
								$selected = 'selected';
							} else {
								$selected = '';
							}
							echo "<option value='$fault' $selected>$fault</option>";
						}
						?>
					</select>
					<label for="row" class="m-1 text-black">Fault Description</label>
				</div>
			</div>
			<div class="col">
				<div class="form-floating">
					<select class='form-select' name="action">
						<option value='0'>Select</option>
						<?php
						$actions = array("Repaired", "Replaced", "Returned");
						foreach ($actions as $action) {
							if (isset($note->action) && $action == $note->action) {
								$selected = 'selected';
							} else {
								$selected = '';
							}
							echo "<option value='$action' $selected>$action</option>";
						}
						?>
					</select>
					<label for="row" class="m-1 text-black">Action</label>
				</div>
			</div>

			<input name="note" placeholder="note" class="form-control col-md-12">
		</div>
		<button type='submit' class="btn btn-success" value="Save"><i class="fa fa-save me-1"></i>Add</button>
		<div class="btn btn-danger qc_note_btn" value="Close"><i class="fas fa-times me-1"></i>Close</div>
		<?= form_close(); ?>
		<?php
		echo "<div style='height:250px' class='overflow-auto mb-3 mt-3 notes'>";
		if (is_array($notes)) {
			foreach ($notes as $note) {
				echo "<div class='col-md-5 border p-1 bg-light text-dark m-auto note'>";
				echo "<span>
					<button id='$note->id' class='btn btn-danger' onclick='trashNote(this.id)'><i class='fa fa-trash'></i></button>
					<a target='_blank' class='btn btn-success me-2 fa fa-edit' href='/production/edit_note/$note->id'></a> 
					</span>";
				echo "<span>ROW: $note->row | </span>";
				echo "<span> $note->note | </span>";
				echo "<span> $note->action </span>";
				echo "</div>";
			}
		} else {
			echo "<div class='col-md-5 border p-1 bg-light text-dark m-auto note'>";
			echo "<p class='col-full col-md-4'>
				<button id='$notes->id' class='btn btn-danger' onclick='trashNote(this.id)'><i class='fa fa-trash'></i></button>
				<a target='_blank' class='btn btn-success me-2 fa fa-edit' href='/production/edit_note/$notes->id'></a> 
				</p>";
			echo "<b class='col-full'>ROW: $notes->row </b>";
			echo "<p class='col-full col-md-8 m-2'>$notes->note</p>";
			echo "";
			echo "</div>";
		}
		echo "</div>";
		?>
	</div>
	<!-- Context-menu -->
	<div class='context-menu'>
		<ul>
			<li><span class='checkbox_yes'></span>&nbsp;<span>Yes</span></li>
			<li><span class='checkbox_no'></span>&nbsp;<span>No</span></li>
		</ul>
		<div value='' id='checkbox_id' class="hidden"></div>
	</div>
</main>
<script>
	// self executing function here
	(function() {
		document.title = '<?= $serial ?>';
	})();

	var checklist_data = "<?= $checklist_data ?>";
	window.onscroll = function() {
		stickHeader()
	};

	$('.scans').on('change', function() {
		$('#ajax-form-scans').submit();
	})

	$('.qc_note_btn').on('click', function() {
		$('#qc-checklist-note').toggle(300);
	})

	var navbar = document.getElementById("navbar");
	var sticky = navbar.offsetTop;

	function stickHeader() {
		if (window.pageYOffset >= sticky) {
			navbar.classList.add("sticky-top")
		} else {
			navbar.classList.remove("sticky-top");
		}
	}
	$('#sn').on('click', function() {
		if (progress == 100) {
			window.location.assign('/Uploads/' + client + '/' + project + '/' + serial)
		}
	})

	function trashNote(id) {
		var r = confirm("Trash note " + id + "?");
		if (r == true) {
			$.post("/production/trash_qc_note", {
				id: id,
			}).done(function(o) {
				location.reload();
			});
		}
	}
</script>