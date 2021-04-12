<?php
$project =  'Trash';
if (!isset($kind)) {
	$kind = '';
}
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
			<input id="ids_values" type="hidden" value="">
			<input id="kind" type="hidden" value="<?= $kind ?>">

			<div class='d-flex flex-row-reverse'>
				<a id='kind_checklist' class="btn btn-success mb-3" href="/admin/manage_trash?kind=checklist"> Checklists </a>
				<a id='kind_rma' class="btn btn-warning mr-3 mb-3" href="/admin/manage_trash?kind=rma"> RMA </a>
				<a id='kind_qc' class="btn btn-warning mr-3 mb-3" href="/admin/manage_trash?kind=qc"> QC </a>
				<button id='batchLink' class="btn btn-danger fa fa-trash disabled mr-5 mb-3" onclick='delete_selected()'> Delete selected</button>
			</div>
		</nav>
		<?php if (isset($results)) { ?>
			<div class="table-responsive">
				<table class="table">
					<thead class="thead-dark">
						<tr>
							<th scope="col"><input type='checkbox' id="select_all"></th>
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
								<td>
									<input type='checkbox' class='check' value='<?php echo $data->id ?>'>
								</td>
								<td class="mobile-hide"><?php echo $data->date ?></td>
								<td>
								<?= $kind ?>
								</td>
								<td><?php echo ($data->serial != '') ? $data->serial : "SN template not found!"; ?></td>
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
			</div>
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
	$('#select_all').on('click', function name(params) {
		var ids = [];
		$('input:checkbox').not(this).prop('checked', this.checked);
		$('.check[type=checkbox]:checked').each(function() {
			ids.push($(this).val());
		});
		$('#ids_values').val(ids);
		$('#batchLink').removeClass('disabled');
	});
	$(".check").change(function() {
		var ids = [];
		$('.check[type=checkbox]:checked').each(function() {
			ids.push($(this).val());
		});
		if (ids.length > 0) {
			$('#batchLink').removeClass('disabled');
		} else {
			$('#batchLink').addClass('disabled');
		}
		$('#ids_values').val(ids);
	});


	function delete_item(id, project, serial, kind) {
		var r = confirm("Delete Item with id: " + id + "?");
		if (r == true) {
			$.post("/admin/delete_from_trash", {
				id: id,
				project: project,
				serial: serial,
				kind: kind
			}).done(function(o) {
				location.reload();
			});
		}
	}

	function delete_selected() {
		var ids = $('#ids_values').val();
		var kind = $('#kind').val();
		var r = confirm("Delete Items with id: " + ids + "?");
		if (r == true) {
			$.post("/admin/delete_batch", {
				ids: ids,
				kind: kind
			}).done(function(o) {
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
				alert(o);
				location.reload();
			});
		}
	}
</script>