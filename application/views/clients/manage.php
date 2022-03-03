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
				<h2 class="display-4"><?= lang('clients') ?></h2>
				<a class="btn btn-outline-success" href="/clients/create"><?= lang('add') ?><i class="fa fa-user-plus mx-2"></i></a>
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
			<?php
			if(!isset($clients)) return;
			foreach ($clients as $key => $client) : ?>
				<div class='col my-3'>
					<div id='client_<?= $client['id'] ?>' class='card h-100'>
						<div style='background-image:url(<?= $client['logo'] ?>)' class='card-header-bg'></div>
						<div class='card-header'>
							<div class='row'>
								<span class='card-title h3 col'><?= $key ?></span>
								<a href='/clients/edit/<?= $client['id'] ?>' class='btn btn-info col-2'><i class='fa fa-edit'></i></a>
								<button class='btn btn-danger mx-2 col-2' onclick='deleteClient(<?= $client["id"] ?>,"<?= $key ?>")'>
									<i class='fa fa-trash'></i>
								</button>
							</div>
						</div>
						<div class='clients card-body'>
							<?php if ($client['status'] == 1) :
								foreach ($client['projects'] as $project) : ?>

									<div id='project_<?= $project['id'] ?>' class='my-3 project_row col-12'>
										<span class='m-2 h5'><?= $project['project'] ?></span>
										<span><a href='/projects/edit_project/<?= $project['id'] ?>' class='btn btn-outline-primary'>
												<i class='fa fa-edit'></i></a>
											<button class='btn btn-outline-danger mx-2' onclick='deleteProject(<?= $project["id"] ?>,"<?= $project["project"] ?>")'>
												<i class='fa fa-trash'></i></button></span>
									</div>
								<?php endforeach ?>
						</div>
						<div class="card-footer text-center">
							<a class='btn btn-outline-success' href='/projects/add_project/<?= $client['id'] ?>'><?= lang('add') ?>
								<i class='fas fa-file-alt mx-2'></i>
							</a>
						<?php else : ?>
							<div class="h3 text-center w-100">Old</div>
						<?php endif ?>
						</div>
					</div>
				</div>
			<?php endforeach ?>
		</div>
	</div>
</main>

<script>
	function deleteClient(id, name) {
		$('[id^=client_' + id + ']').remove();
		if (confirm("Delete " + name + "?")) {
			$.post("/clients/delete", {
				id: id
			}).done(function(o) {
				console.log('Client deleted.');
				$('[id^=client_' + id + ']').remove();
			});
		}
	}

	function deleteProject(id, name) {
		if (confirm("Delete " + name + "?")) {
			$.post("/projects/delete_project", {
				id: id
			}).done(function(o) {
				console.log('Project deleted.');
				$('[id^=project_' + id + ']').remove();
			});
		}
	}
</script>