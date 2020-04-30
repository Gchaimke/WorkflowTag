<main role="main">
    <div class="jumbotron">
        <div class="container">
            <center>
                <h2 class="display-3">All users.</h2>
            </center>
        </div>
    </div>
    <div class="container">
    </div>
    <table class="table">
        <thead class="thead-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Username</th>
                <th scope="col">User Role</th>
                <th scope="col">Edit</th>
                <th scope="col">Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php if (isset($users)) {
                foreach ($users as $user) {
                    echo '<tr>';
                    echo '<tr id="' . $user['id'] . '">';
                    echo  '<td>' . $user['id'] . '</td>';
                    echo  '<td>' . $user['username'] . '</td>';
                    echo  '<td>' . $user['userrole'] . '</td>';
                    echo "<td><a href='/checklist/edit/" . $user['id'] ."' class='btn btn-info'>Edit</a></td>";
                    echo "<td><button id='" . $user['id'] . "' class='btn btn-danger' onclick='delPhoto(this.id)'>Delete</button></td>";
                    echo '</tr>';
                }
            } ?>
        </tbody>
    </table>
</main>
<script>
      function delPhoto(id) {
            $.post("/users/delete", {
                  id: id
            }).done(function(o) {
                  console.log('user deleted from the server.');
                  $('[id^=' + id + ']').remove();
            });
      }
</script>