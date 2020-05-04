<main role="main">

	<div class="container">
		<div class="jumbotron">
			<div class="container">
				<center>
					<h2 class="display-3">Projects</h2>
				</center>
			</div>
		</div>
		<?php
		if (isset($message_display)) {
			echo "<div class='alert alert-success' role='alert'>";
			echo $message_display . '</div>';
		}
		?>
		<a class="btn btn-success" href="/production/add_project">Add Project</a>
		<table class="table">
			<thead class="thead-dark">
				<tr>
					<th scope="col">Client</th>
					<th scope="col">Project</th>
					<th scope="col">Edit</th>
					<th scope="col">Delete</th>
				</tr>
			</thead>
			<tbody>
				<?php if (isset($projects)) {
					foreach ($projects as $project) {
						echo '<tr id="' . $project['id'] . '">';
						echo  '<td>' . $project['client'] . '</td>';
						echo  '<td>' . $project['project'] . '</td>';
						echo "<td><a href='/production/edit_project/" . $project['id'] . 
						"' class='btn btn-info'>Edit</a></td>";
						echo "<td><button id='" . $project['id'] . 
						"' class='btn btn-danger' onclick='deleteProject(this.id)'>Delete</button></td>";
						echo '</tr>';
					}
				} ?>
			</tbody>
		</table>
	</div>
</main>
<script>
	function deleteProject(id) {
		$.post("/production/delete_project", {
			id: id
		}).done(function(o) {
			console.log('Project deleted.');
			$('[id^=' + id + ']').remove();
		});
	}
</script>