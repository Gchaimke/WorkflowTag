<?php
if (isset($this->session->userdata['logged_in'])) {
	$user_role = $this->session->userdata['logged_in']['role'];
}
?>
<main role="main">
	<div class="jumbotron">
		<div class="container">
			<center>
				<h2 class="display-4"><?= lang('projects') ?></h2>
			</center>
		</div>
	</div>
	<div class="container">
		<?php
		if (isset($message_display)) {
			echo "<div class='alert alert-success' role='alert'>";
			echo $message_display . '</div>';
		}
		?>

		<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
			<?php if (isset($clients)) :
				foreach ($clients as $key => $client) : ?>
					<div class='col my-3'>
						<div id='<?= $client['id'] ?>' class='card h-100'>
							<div style='background-image:url(<?= $client['logo'] ?>)' class='card-header-bg'></div>
							<div class='card-header'>
								<div class='card-title h5 text-center'><?= $key ?></div>
							</div>
							<div class='card-body'>
								<?php if ($client['status'] == 1) :
									foreach ($client['projects'] as $project) : ?>
										<div class="d-flex">
											<a href='/production/checklists?client=<?= $client['id'] ?>&project=<?= $project['project'] ?>' class=' btn btn-outline-primary flex-fill mb-2'>
												<?php
												echo $project['project'];
												if ($project['project_num']) {
													echo " ({$project['project_num']})";
												} ?>
											</a>
											<?php if ($user_role == "Admin") : ?>
												<a href='/projects/edit_project/<?= $project['id'] ?>' class='btn btn-outline-primary mb-2 mx-1'><i class='fa fa-edit'></i></a>
												<span class="btn btn-outline-primary mb-2 csv_btn" data-bs-toggle="modal" data-bs-target="#csv_month_selector" data-project-name="<?= $project['project'] ?>"><i class="bi bi-file-earmark-spreadsheet"></i></span>
											<?php endif ?>
										</div>
									<?php endforeach ?>
								<?php else : ?>
									<h3>OLD</h3>
								<?php endif ?>
							</div>
						</div>
					</div>
				<?php endforeach ?>
		</div>
	<?php endif ?>
	</div>
	<!-- Modal -->
	<div class="modal fade" id="csv_month_selector" tabindex="-1" aria-labelledby="csv_month_selector" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="csv_month_selector_label">Select month</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="mb-3">
						<label for="recipient-name" class="col-form-label">Month:</label>
						<input id="current_project_name" type="hidden" value="">
						<select type="text" class="form-control" id="csv-month">
							<option value="13">All</option>
							<?php for ($i = 1; $i < 13; $i++) {
								echo "<option value='$i'>$i</option>";
							} ?>
						</select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary export_csv" data-bs-dismiss="modal">Get CSV</button>
				</div>
			</div>
		</div>
	</div>
</main>
<script>
	$(".csv_btn").on('click', function() {
		$("#current_project_name").val($(this).data("project-name"))
	})

	$('.export_csv').on('click', function() {
		let month = $('#csv-month').val();
		let project_name = $("#current_project_name").val();
		window.location.replace("/production/export_checklists_to_csv/" + month + "/" + project_name);
	})
</script>