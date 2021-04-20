<main role="main">
	<div class="jumbotron">
		<div class="container">
			<center>
				<h2 class="display-4"><?=lang('projects')?></h2>
			</center>
		</div>
	</div>
	<div class="container">
		<?php
		if (isset($message_display)) {
			echo "<div class='alert alert-success' role='alert'>";
			echo $message_display . '</div>';
		} ?>
			<?php
			echo '<div class="card-columns">';
			foreach ($clients as $client) {
				echo '<div id="' . $client['name'] . '" class="card"><center><div class="card-body"><h5 class="card-title">';
				echo $client['name'];
				echo '</h5></div>';
				echo '<div class="card-footer">';
				if ($client['projects'] != "" && $client['status']==1) {
					$arr = explode(',', $client['projects']);
					foreach ($arr as $project) {
						echo  "<a href='/production/checklists/$project' class='btn btn-primary  btn-block'>$project</a>";
					}
				}else{
					echo '<h3>OLD</h3>';
				}
				echo '</div></center></div>';
			}

			?>
	</div>
	</div>
</main>