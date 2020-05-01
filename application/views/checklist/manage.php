<main role="main">
      <div class="jumbotron">
            <div class="container">
                  <center>
                        <h2 class="display-3">All Checklists.</h2>
                  </center>
            </div>
      </div>
      <div class="container">
      </div>
      <table class="table">
            <thead class="thead-dark">
                  <tr>
                        <th scope="col">#</th>
                        <th scope="col">Serial Number</th>
                        <th scope="col">Project</th>
                        <th scope="col">Progress</th>
                        <th scope="col">Date</th>
                        <th scope="col">Edit</th>
                        <th scope="col">Delete</th>
                  </tr>
            </thead>
            <tbody>
                  <?php if (isset($checklists)) {
                        foreach ($checklists as $checklist) {
                              echo '<tr id="' . $checklist['id'] . '">';
                              echo  '<td>' . $checklist['id'] . '</td>';
                              echo  '<td>' . $checklist['serial'] . '</td>';
                              echo  '<td>' . $checklist['project'] . '</td>';
                              echo  '<td>' . $checklist['progress'] . '</td>';
                              echo  '<td>' . $checklist['date'] . '</td>';
                              echo "<td><a href='/checklist/edit/" . $checklist['serial'] . "/" . $checklist['project'] . "' class='btn btn-info'>Edit</a></td>";
                              echo "<td><button id='" . $checklist['id'] . "' class='btn btn-danger' onclick='delPhoto(this.id)'>Delete</button></td>";
                              echo '</tr>';
                        }
                  } ?>
            </tbody>
      </table>
</main>
<script>
      function delPhoto(id) {
            $.post("/checklist/delete", {
                  id: id
            }).done(function(o) {
                  console.log('checklist deleted from the server.');
                  $('[id^=' + id + ']').remove();
            });
      }
</script>