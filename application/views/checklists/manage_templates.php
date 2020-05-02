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
		<table class="table">
			<thead class="thead-dark">
				<tr>
					<th scope="col">Project</th>
					<th scope="col">Template</th>
					<th scope="col">Edit</th>
					<th scope="col">Delete</th>
				</tr>
			</thead>
			<tbody>
				<?php if (isset($templates)) {
					foreach ($templates as $template) {
						echo '<tr id="' . $template['id'] . '">';
						echo  '<td>' . $template['project'] . '</td>';
						echo  '<td>' . $template['template'] . '</td>';
						echo "<td><a href='/checklists/edit_template/" . $template['id'] ."' class='btn btn-info'>Edit</a></td>";
						echo "<td><button id='" . $template['id'] . "' class='btn btn-danger' onclick='deleteTemplate(this.id)'>Delete</button></td>";
						echo '</tr>';
					}
				} ?>
			</tbody>
		</table>
	</div>
</main>
<script>
	function deleteTemplate(id) {
		$.post("/checklists/delete_template", {
			id: id
		}).done(function(o) {
			console.log('checklist template deleted.');
			$('[id^=' + id + ']').remove();
		});
	}
</script>