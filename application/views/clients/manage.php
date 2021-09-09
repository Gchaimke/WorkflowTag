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
				<a class="btn btn-success" href="/clients/create"><?= lang('add') ?><i class="fa fa-user-plus mx-2"></i></a>
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
			echo "<div id='client_{$client['id']}' class='card'><center><div class='clients card-body'><h3 class='card-title'>$key</h3>";
			echo "<div class='m-2'>";
			echo "<a href='/clients/edit/{$client['id']}' class='btn btn-info'><i class='fa fa-edit'></i></a>";
			echo "<button class='btn btn-danger mx-2' onclick='deleteClient({$client['id']},\"$key\")'><i class='fa fa-trash'></i></button>";
			echo "</div></div>";
			echo '<div class="card-footer">';
			if ($client['status'] == 1) {
				foreach ($client['projects'] as $project) {
					echo  "<div id='project_{$project['id']}' class='my-3 project_row'>
					<span class='m-2'>{$project['project']}</span>
					<span><a href='/projects/edit_project/{$project['id']}' class='btn btn-primary'><i class='fa fa-edit'></i></a>
					<button class='btn btn-danger mx-2' onclick='deleteProject({$project['id']},\"{$project['project']}\")'>
					<i class='fa fa-trash'></i></button></span></div>";
				}
				echo "<a class='btn btn-success' href='/projects/add_project/{$client['id']}'>" . lang('add') . "<i class='fas fa-file-alt mx-2'></i></a>";
			} else {
				echo '<h3>OLD</h3>';
			}
			echo "</div></center></div>";
		}
		echo "</div>";
		?>
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