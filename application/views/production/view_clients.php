<main role="main">
	<div class="container">
		<div class="jumbotron">
			<div class="container">
				<center>
					<h2 class="display-3">Clients</h2>
				</center>
			</div>
		</div>
		<?php
		if (isset($message_display)) {
			echo "<div class='alert alert-success' role='alert'>";
			echo $message_display . '</div>';
		}

		echo '<div class="card-deck mt-4">';
		foreach ($clients as $client) {
			echo '<div id="'.$client['name'].'" class="card"><center><div class="card-body"><h5 class="card-title">';
			echo $client['name'];
			echo '</h5><p class="card-text">Select Project:</p></div>';
			echo '<div class="card-footer">';
			if ($client['projects'] != "") {
				$arr = explode(',', $client['projects']);
				foreach ($arr as $project) {
					echo  "<a href='/production/checklists/$project' class='btn btn-primary  btn-block'>$project</a>";
				}
			}
			echo '</div></center></div>';
		}

		?>
	</div>
	</div>
</main>