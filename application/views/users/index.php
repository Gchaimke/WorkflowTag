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
        </tr>
    </thead>
    <tbody>
        <?php if (isset($users)) {
            foreach ($users as $user) {
                echo '<tr>';
                foreach ($user as $key => $val) {
                    if ($key == 'userid')
                        echo "<td>$val</td>";
                    if ($key == 'username')
                        echo "<td>$val</td>";
                    if ($key == 'userrole')
                        echo "<td>$val</td>";
                }
                echo "<td><button class='btn btn-info'>edit</button</td>";
                echo '</tr>';
            }
        } ?>
    </tbody>
</table>
</main>