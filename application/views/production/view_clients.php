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

		echo '<div class="card-columns">';
		foreach ($clients as $key => $client) {
			echo '<div id="' . $client['id'] . '" class="card"><center><div class="card-body"><h5 class="card-title">';
			echo $key;
			echo '</h5></div>';
			echo '<div class="card-footer">';
			if ($client['status'] == 1) {
				foreach ($client['projects'] as $project) {
					echo  "<a href='/production/checklists?client={$client['id']}&project=" . $project['project'] . "' class='btn btn-primary  btn-block text-nowrap'>" . $project['project'] . "</a>";
				}
			} else {
				echo '<h3>OLD</h3>';
			}
			echo '</div></center></div>';
		}
		echo "</div>";
		?>
	</div>
</main>