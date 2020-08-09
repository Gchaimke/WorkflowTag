<main role="main">
	<div class="jumbotron rma">
		<div class="container">
			<center>
				<h2 class="display-4">Select RMA Project</h2>
			</center>
		</div>
	</div>
	<div class="container">
		<?php
		if (isset($message_display)) {
			echo "<div class='alert alert-success' role='alert'>";
			echo $message_display . '</div>';
		} ?>
		<form id="form">
			<div class="input-group mb-3">
				<input id='inputSearch' type="text" class="form-control" placeholder="Search for RMA Form #" aria-label="Search for serial number" aria-describedby="basic-addon2" autofocus>
				<div class="input-group-append">
					<button class="btn btn-secondary" type="button" onclick="rmaSearch()">Search</button>
				</div>
			</div>
			<div id='searchResult'></div>
		</form>
			<?php
			echo '<div class="card-columns">';
			foreach ($clients as $client) {
				echo '<div id="' . $client['name'] . '" class="card"><center><div class="card-body"><h5 class="card-title">';
				echo $client['name'].' RMA';
				echo '</h5><p class="card-text">Select Project:</p></div>';
				echo '<div class="card-footer">';
				if ($client['projects'] != "") {
					$arr = explode(',', $client['projects']);
					foreach ($arr as $project) {
						echo  "<a href='/production/rma/$project' class='btn btn-primary  btn-block'>$project</a>";
					}
				}
				echo '</div></center></div>';
			}

			?>
	</div>
	</div>
</main>
<script>
	function rmaSearch() {
		var search = document.getElementById("inputSearch").value;
		if (sn.length >= 3) {
			$.post("/production/rma_search", {
				sn: search
			}).done(function(e) {
				if (e.length > 0) {
					$('#searchResult').empty();
					$('#searchResult').append( e );
				} else {
					$('#searchResult').empty();
					$('#searchResult').append("<h2>RMA form with "+search+" not found!</h2>");
				}
			});
		} else {
			$('#searchResult').empty();
			$('#searchResult').append("<h2>Search must be munimum 3 simbols</h2>")
		}
	}

	document.onkeydown = function(e) {
		var pathname = window.location.pathname.split("/");
		if (e.which == 13) { //enter
			e.preventDefault();
			serialSearch();
		}
	};
</script>