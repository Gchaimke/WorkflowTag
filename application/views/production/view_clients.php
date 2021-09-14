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

		echo '<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">';
		foreach ($clients as $key => $client) {
			
			echo "<div class='col my-3'><div id='{$client['id']}' class='card h-100'>
			<div style='background-image:url({$client['logo']})' class='card-header-bg'></div>
			<div class='card-header'>		
				<div class='card-title h5 text-center'>$key</div>
			</div>
			<div class='card-body'>
			";
			if ($client['status'] == 1) {
				foreach ($client['projects'] as $project) {
					echo  "<a href='/production/checklists?client={$client['id']}&project=" . $project['project'] . "' class='btn btn-primary  btn-block text-nowrap'>" . $project['project'] . "</a>";
				}
			} else {
				echo '<h3>OLD</h3>';
			}
			echo '</div></div></div>';
		}
		echo "</div>";
		?>
	</div>
</main>