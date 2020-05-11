<?php
$project =  explode("/", $_SERVER['REQUEST_URI'])[3];
echo urldecode($project);
?>
<main role="main">
	<div class="jumbotron">
		<div class="container">
			<center>
				<h2 class="display-3"><?php echo urldecode($project); ?> Checklists</h2>
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
		<a class="btn btn-success" href="/production/add_checklist/<?php echo $project; ?>">New Checklist</a>
		<a class="btn btn-info" onclick="gen_checklist('<?php echo $project; ?>',1)">+1</a>
		<a class="btn btn-info"  onclick="gen_checklist('<?php echo $project; ?>',5)">+5</a>
		<table class="table">
			<thead class="thead-dark">
				<tr>
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
						echo  '<td>' . $checklist['serial'] . '</td>';
						echo  '<td>' . $checklist['project'] . '</td>';
						echo  '<td class="mobile-hide">' . $checklist['progress'] . ' %</td>';
						echo  '<td class="mobile-hide">' . $checklist['assembler'] . '</td>';
						echo  '<td class="mobile-hide">' . $checklist['qc'] . '</td>';
						echo  '<td class="mobile-hide">' . $checklist['date'] . '</td>';
						echo "<td><a href='/production/edit_checklist/" . $checklist['id'] . "' class='btn btn-info'>Edit</a></td>";
						echo "<td><button id='" . $checklist['id'] . "' class='btn btn-danger' onclick='delChecklist(this.id)'>Delete</button></td>";
						echo '</tr>';
					}
				} ?>
			</tbody>
		</table>
	</div>
</main>
<script>
	function delChecklist(id) {
		var r = confirm("Delete checklist with id: " + id + "?");
		if (r == true) {
			$.post("/production/delete", {
				id: id
			}).done(function(o) {
				$('[id^=' + id + ']').remove();
				console.log('checklist deleted from the server.');
			});
		}
	}

	function gen_checklist(project,count) {
		var r = confirm("Add "+ count + " checklist/s to " + project + "?");
		if (r == true) {
			$.post("/production/gen_checklist", {
				client:'<?php echo $checklist['client']?>',
				project: project,
				count:count
			}).done(function(o) {
				console.log(o+' checklist/s added to the server.');
				location.reload();
			});
		}
	}
</script>