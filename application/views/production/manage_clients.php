<?php
if (isset($this->session->userdata['logged_in'])) {
	if ($this->session->userdata['logged_in']['role'] != "Admin") {
		header("location: /");
	}
}
?>
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
		?>
		<a class="btn btn-success" href="/production/add_client">Add Client</a>
		<table class="table">
			<thead class="thead-dark">
				<tr>
					<th scope="col">Client</th>
					<th scope="col">Edit</th>
					<th scope="col">Delete</th>
				</tr>
			</thead>
			<tbody>
				<?php if (isset($clients)) {
					foreach ($clients as $client) {
						echo '<tr id="' . $client['id'] . '">';
						echo  '<td>' . $client['name'] . '</td>';
						echo "<td><a href='/production/edit_client/" . $client['id'] .
							"' class='btn btn-info'>Edit</a></td>";
						echo "<td><button id='" . $client['id'] .
							"' class='btn btn-danger' onclick='deleteClient(this.id)'>Delete</button></td>";
						echo '</tr>';
					}
				} ?>
			</tbody>
		</table>
	</div>
</main>
<script>
	function deleteClient(id) {
		var r = confirm("Delete Client with id: " + id + "?");
		if (r == true) {
			$.post("/production/delete_client", {
				id: id
			}).done(function(o) {
				console.log('Client deleted.');
				$('[id^=' + id + ']').remove();
			});
		}
	}
</script>