<?php
$serials_links = '';
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
	$serials_links .= "<a target='_blank' class='badge badge-light' href='/production/edit_checklist/{$checklist['id']}?sn={$checklist['serial']}&client={$client['id']}'>{$checklist['serial']}</a> |";
}
if (isset($this->session->userdata['logged_in'])) {
	$username = ($this->session->userdata['logged_in']['name']);
	$role = ($this->session->userdata['logged_in']['role']);
	if ($assembler == '') {
		$assembler = $username;
	}
}
?>
<link rel="stylesheet" href="<?php echo base_url('assets/css/checklist_create.css?' . filemtime('assets/css/checklist_create.css')); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/css/print.css?' . filemtime('assets/css/print.css')); ?>">
<main role="main" class="container ltr">
	<nav id="navbar" class="navbar checklist navbar-light bg-light">
		<b id="project" class="navbar-text mobile-hide">Project: <?php echo $project ?></b>
		<b id="sn" class="navbar-text" href="#">SN: <?php echo $serials_links ?></b>
		<b id="date" class="navbar-text mobile-hide">Date: <?php echo $date ?></b>
		<ul class="nav navbar-nav navbar-right">
			<li class="nav-item">
				<?php echo form_open('production/save_batch_checklists/' . $ids, 'id=ajax-form', 'class=saveData'); ?>
				<input id="input_data" type='hidden' name='data' value="<?php echo $checklist_data ?>">
				<input id="input_progress" type='hidden' name='progress' value="<?php echo $progress ?>">
				<input id="assembler" type='hidden' name='assembler' value="<?php echo $assembler ?>">
				<input type='hidden' name='client' value="<?php echo $client['name'] ?>">
				<input type='hidden' name='project' value="<?php echo $project ?>">
				<input type='hidden' name='date' value="<?php echo $date ?>">
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
	<div id="form-messages" class='alert hidden' role='alert'></div>
	<div id="workTable">
		<?php echo $checklist_rows ?>
	</div>
</main>
<?php
echo "<script>var photoCount=0; var id='$ids'; var pr='$project'; var ci_session='$session';"; //pass PHP data to JS
echo "var log='$log'; var assembler ='$username'; var qc_name='$qc'</script>";  //pass PHP data to JS
?>
<script>
	var checklist_data = "<?= $checklist_data ?>";
	$("input:checkbox.verify").click(function(e) {
		$('#assembler').val(assembler);
	});
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