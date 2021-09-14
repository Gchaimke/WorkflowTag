<?php
if (isset($this->session->userdata['logged_in'])) {
	$username = ($this->session->userdata['logged_in']['name']);
	$user_id = ($this->session->userdata['logged_in']['id']);
	$role = ($this->session->userdata['logged_in']['role']);
	if ($checklist['pictures'] != '') {
		$pictures = $checklist['pictures'];
	}
	$id = $checklist['id'];
	$project = $checklist['project'];
	$checklist_client = $checklist['client'];
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
} else {
	exit();
}
?>
<link rel="stylesheet" href="<?= base_url('assets/css/checklist_create.css?' . filemtime('assets/css/checklist_create.css')); ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/print.css?' . filemtime('assets/css/print.css')); ?>">
<main role="main" class="container ltr">
	<nav id="navbar" class="navbar checklist navbar-light bg-light">
		<?= "<img class='img-thumbnail checklist-logo' src='$logo'>" ?>
		<b id="project" class="navbar-text mobile-hide" href="#">Project: <?= $project ?></b>
		<b id="sn" class="navbar-text" href="#">SN: <?= $serial ?></b>
		<b id="date" class="navbar-text mobile-hide" href="#">Date: <?= $date ?></b>
		<ul class="nav navbar-nav navbar-right">
			<li class="nav-item">
				<?php if ($role != 'Assembler') { ?>
					<button class="btn btn-warning mr-3 qc_note_btn"><i class="fas fa-sticky-note"></i></button>
				<?php } ?>
				<a class="btn btn-info mr-3" href="#scansTable"><i class="fa fa-list"></i></a>
				<button id="snap1" class="btn btn-info" onclick="document.getElementById('browse').click();"><i class="fa fa-camera"></i></button>
				<?= form_open('production/save_checklist/' . $id . '?sn=' . $serial, 'id=ajax-form', 'class=saveData'); ?>
				<input id='input_data' type='hidden' name='data' value="<?= $checklist_data ?>">
				<input id='version' type='hidden' name='version' value="<?= $checklist['version'] ?>">
				<input id='input_progress' type='hidden' name='progress' value="<?= $progress ?>">
				<input id='assembler' type='hidden' name='assembler' value="<?= $assembler ?>">
				<input id="input_qc" type='hidden' name='qc' value="<?= $qc ?>">
				<input type='hidden' name='serial' value="<?= $serial ?>">
				<input type='hidden' name='client' value="<?= $client_name ?>">
				<input type='hidden' name='project' value="<?= $project ?>">
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
		<div class="input-group mb-2 col-12">
			<div class="input-group-prepend">
				<div class="input-group-text">Note</div>
			</div>
			<textarea id="note" type='text' rows="2" class="form-control" name='note'><?= $note ?></textarea>
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
				var client='$client_name';
				var working_dir='$working_dir';
				var progress='$progress';
            </script>";  //pass PHP data to JS
		if (file_exists("./$working_dir")) {
			if ($handle = opendir("./$working_dir")) {
				while (false !== ($entry = readdir($handle))) {
					if (
						$entry != "." && $entry != ".." &&
						(pathinfo($entry, PATHINFO_EXTENSION) == 'jpeg' || pathinfo($entry, PATHINFO_EXTENSION) == 'png') &&
						PATHINFO_FILENAME != '' &&
						pathinfo($entry, PATHINFO_FILENAME) != 'logo'
					) {
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
	<input id="browse" style="display:none;" type="file" onchange="snapPhoto()" name="photos" multiple>
	<div id="preview"></div>

	<div id="qc-checklist-note" style="display:none">
		<span class="btn btn-danger qc_note_btn close-notes"><i class="fas fa-times"></i></span>
		<?= form_open('production/add_qc_note/', 'id=ajax-form-qc'); ?>
		<input type="hidden" name="checklist_id" value="<?= $id ?>" />
		<input type="hidden" name="checklist_sn" value="<?= $serial ?>" />
		<input type="hidden" name="qc_id" value="<?= $user_id ?>" />
		<input type="hidden" name="client_id" value="<?= $client['id'] ?>" />
		<input type="hidden" name="project" value="<?= $project ?>" />
		<div class="form-row mb-3">
			<div class="col-md-6 mb-2">
				<select class='form-control' name="assembler_id">
					<option value='0'>Select</option>
					<?php foreach ($users as $user) {
						if (strpos($checklist_data, $user['name']) !== false)
							echo "<option value=" . $user['id'] . ">" . $user['name'] . "</option>";
					}
					?>
				</select>
			</div>
			<input type="text" name="row" placeholder="checklist row" class="form-control col-md-6 mb-2" />
			<textarea name="note" placeholder="note" class="form-control col-md-12"></textarea>
		</div>
		<button type='submit' class="btn btn-success" value="Save"><i class="fa fa-save mr-1"></i>Add</button>
		<div class="btn btn-danger qc_note_btn" value="Close"><i class="fas fa-times mr-1"></i>Close</div>
		<?= form_close(); ?>
		<?php
		echo "<div style='height:250px' class='overflow-auto mb-3 mt-3 notes'>";
		if (is_array($notes)) {
			foreach ($notes as $note) {
				echo "<div class='col-md-5 border p-1 bg-light text-dark m-auto note'>";
				echo "<span>
					<button id='$note->id' class='btn btn-danger' onclick='trashNote(this.id)'><i class='fa fa-trash'></i></button>
					<a target='_blank' class='btn btn-success mr-2 fa fa-edit' href='/production/edit_note/$note->id'></a> 
					</span>";
				echo "<span>ROW: $note->row </span>";
				echo "<span>$note->note</span>";
				echo "</div>";
			}
		} else {
			echo "<div class='col-md-5 border p-1 bg-light text-dark m-auto note'>";
			echo "<p class='col-full col-md-4'>
				<button id='$notes->id' class='btn btn-danger' onclick='trashNote(this.id)'><i class='fa fa-trash'></i></button>
				<a target='_blank' class='btn btn-success mr-2 fa fa-edit' href='/production/edit_note/$notes->id'></a> 
				</p>";
			echo "<b class='col-full'>ROW: $notes->row </b>";
			echo "<p class='col-full col-md-8 m-2'>$notes->note</p>";
			echo "";
			echo "</div>";
		}
		echo "</div>";
		?>
	</div>
</main>
<script>
	var checklist_data = "<?= $checklist_data ?>";
	window.onscroll = function() {
		stickHeader()
	};

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