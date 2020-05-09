<?php
if (isset($this->session->userdata['logged_in'])) {
    $id = ($this->session->userdata['logged_in']['id']);
    $username = ($this->session->userdata['logged_in']['name']);
    $role = ($this->session->userdata['logged_in']['role']);
    if ($role != "Admin") {
        header("location: /dashboard");
    }
}
?>
<main role="main">
    <div class="container">
        <div class="jumbotron">
            <div class="container">
                <center>
                    <h2 class="display-3">Users</h2>
                </center>
            </div>
        </div>
        <?php
        if (isset($message_display)) {
            echo "<div class='alert alert-success' role='alert'>";
            echo $message_display . '</div>';
        }
        ?>
        <a class="btn btn-success" href="/users/create">Add User</a>
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col" class="mobile-hide">#</th>
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
                        echo  '<td class="mobile-hide">' . $user['id'] . '</td>';
                        echo  '<td>' . $user['name'] . '</td>';
                        echo  '<td>' . $user['role'] . '</td>';
                        echo "<td><a href='/users/edit/" . $user['id'] . "' class='btn btn-info'>Edit</a></td>";
                        if ($user['name'] == $username) {
                            echo "<td><button id='" . $user['id'] . "' class='btn btn-danger' onclick='delPhoto(this.id)' disabled>Delete</button></td>";
                        } else {
                            echo "<td><button id='" . $user['id'] . "' class='btn btn-danger' onclick='delPhoto(this.id)'>Delete</button></td>";
                        }

                        echo '</tr>';
                    }
                } ?>
            </tbody>
        </table>
    </div>
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