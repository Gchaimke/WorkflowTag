<?php
$project =  'Trash';
?>
<main role="main">
	<div class="jumbotron">
		<div class="container">
			<center>
				<h2 class="display-3"><?php echo urldecode($project); ?> </h2>
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
		<nav aria-label="Checklist navigation">
			<?php if (isset($links)) {
				echo $links;
			} ?>
		</nav>
		<?php if (isset($results)) { ?>
			<table class="table">
				<thead class="thead-dark">
					<tr>
						<th scope="col" class="mobile-hide">Date</th>
						<th scope="col">Kind</th>
						<th scope="col">Serial Number</th>
						<th scope="col" class="mobile-hide">Project</th>
						<th scope="col">Restore</th>
						<th scope="col">Delete</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($results as $data) { ?>
						<tr id='<?php echo $data->id; ?>'>
						
						<td class="mobile-hide"><?php echo $data->date ?></td>
							<td><?php $kind = (isset($data->number)) ? 'RMA #'.$data->number : "Checklist"; echo $kind; ?>
							</td>
							<td><?php echo ($data->serial != '')? $data->serial:"SN template not found!";?></td>
							<?php 
							$project = str_replace('Trash ', '', $data->project);
							echo '<td class="mobile-hide"><a class="nav-item btn btn-warning p-1 mx-1 mt-1 mt-lg-0" href="/production/checklists/' . $project . '">' . $project . '</a></td>';
							?>
							<td><button id='<?php echo $data->id ?>' class='btn btn-info' onclick='restore_item(this.id,"<?php echo $data->project ?>","<?php echo $data->serial ?>","<?php echo $kind ?>")'><i class="fa fa-undo"></i></button></td>
							<td><button id='<?php echo $data->id ?>' class='btn btn-danger' onclick='delete_item(this.id,"<?php echo $data->project ?>","<?php echo $data->serial ?>","<?php echo $kind ?>")'><i class="fa fa-trash"></i></button></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		<?php } else { ?>
			<div>No trashed items found.</div>
		<?php } ?>
	</div>
	<div id='show-log' style='display:none;'>
		<div id="show-log-header">
			<div id="serial-header"></div>Click here to move<button type="button" class="close" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
		</div>
		<ul class="list-group list-group-flush">
		</ul>
	</div>
</main>
<script>
	function delete_item(id, project, serial, kind) {
		var r = confirm("Delete Item with id: " + id + "?");
		if (r == true) {
			$.post("/admin/delete_from_trash", {
				id: id,
				project: project,
				serial: serial,
				kind: kind
			}).done(function(o) {
				//$('[id^=' + id + ']').remove();
				location.reload();
			});
		}
	}

	function restore_item(id, project, serial, kind) {
		var r = confirm("Restore checklist with id: " + id + "?");
		if (r == true) {
			$.post("/admin/restore_item", {
				id: id,
				project: project,
				serial: serial,
				kind: kind
			}).done(function(o) {
				//$('[id^=' + id + ']').remove();
				alert(o);
				location.reload();
			});
		}
	}
</script>