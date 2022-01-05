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
										<div class="row">
											<a href='/production/checklists?client=<?= $client['id'] ?>&project=<?= $project['project'] ?>' class=' btn btn-outline-primary col mb-2'>
												<?php
												echo $project['project'];
												if ($project['project_num']) {
													echo " ({$project['project_num']})";
												} ?>
											</a>
											<?php if ($user_role == "Admin") : ?>
												<a href='/projects/edit_project/<?= $project['id'] ?>' class='btn btn-outline-primary col-2 mb-2 mx-1'>
													<i class='fa fa-edit'></i></a>
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
</main>