<main role="main">
	<div class="container">
		<div class="jumbotron">
			<div class="container">
				<center>
					<h2 class="display-3">Checklists</h2>
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
					<th scope="col" class="mobile-hide">ID</th>
					<th scope="col">Serial Number</th>
					<th scope="col">Project</th>
					<th scope="col" class="mobile-hide">Progress</th>
					<th scope="col" class="mobile-hide">Assembler</th>
					<th scope="col" class="mobile-hide">QC</th>
					<th scope="col" class="mobile-hide">Date</th>
					<th scope="col">Edit</th>
					<th scope="col">Delete</th>
				</tr>
			</thead>
			<tbody>
				<?php if (isset($checklists)) {
					foreach ($checklists as $checklist) {
						echo '<tr id="' . $checklist['id'] . '">';
						echo  '<td class="mobile-hide">' . $checklist['id'] . '</td>';
						echo  '<td>' . $checklist['serial'] . '</td>';
						echo  '<td>' . $checklist['project'] . '</td>';
						echo  '<td class="mobile-hide">' . $checklist['assembler'] . '</td>';
						echo  '<td class="mobile-hide">' . $checklist['qc'] . '</td>';
						echo  '<td class="mobile-hide">' . $checklist['progress'] . '</td>';
						echo  '<td class="mobile-hide">' . $checklist['date'] . '</td>';
						echo "<td><a href='/checklists/edit_checklist/" . $checklist['project'] . "/" . $checklist['serial'] . "' class='btn btn-info'>Edit</a></td>";
						echo "<td><button id='" . $checklist['id'] . "' class='btn btn-danger' onclick='delPhoto(this.id)'>Delete</button></td>";
						echo '</tr>';
					}
				} ?>
			</tbody>
		</table>
	</div>
</main>
<script>
	function delPhoto(id) {
		$.post("/checklists/delete", {
			id: id
		}).done(function(o) {
			console.log('checklist deleted from the server.');
			$('[id^=' + id + ']').remove();
		});
	}
</script>