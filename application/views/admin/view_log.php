<?php
$project =  'Trash';
?>
<main role="main">
	<div class="jumbotron">
		<div class="container">
			<center>
				<h2 class="display-3">Manage Logs </h2>
			</center>
		</div>
	</div>
	<div class="container">
		<nav aria-label="Checklist navigation">
			<?php if (isset($links)) {
				echo $links;
			} ?>
		</nav>
		<?php if (isset($results)) { ?>
			<table class="table table-striped table-hover">
				<thead class="table-dark">
					<tr>
						<th scope="col">Logs</th>
						<th scope="col">Size</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($results as $file) { ?>
						<tr>
							<?php
							$log_name = basename($file['name']);
							echo "<td><a href='#' onclick=showLogFile('$log_name') >$log_name</a></td>";
							echo '<td>', human_filesize($file['size']), '</td>';
							?>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		<?php } else { ?>
			<div>No logs found.</div>
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
	function showLogFile(file) {
		$.post("/admin/get_log", {
			file: file
		}).done(function(o) {
			if (o != '') {
				log_arr = o.split(/\r?\n/)
				$("#show-log").show();
				$("#show-log .list-group").empty();
				$("#serial-header").text(file);
				log_arr.forEach(element => {
					if (element != '') {
						if (~element.indexOf("DELETE")) {
							$("#show-log .list-group").append("<li class='list-group-item list-group-item-danger'>" + element + "</li>");
						} else if (~element.indexOf("TRASH")){
							$("#show-log .list-group").append("<li class='list-group-item list-group-item-warning'>" + element + "</li>");
						}else{
							$("#show-log .list-group").append("<li class='list-group-item list-group-item-info'>" + element + "</li>");
						}
					}
				});
			}
		});

	}
</script>