<?php
if (isset($this->session->userdata['logged_in'])) {
    $id = ($this->session->userdata['logged_in']['id']);
    $username = ($this->session->userdata['logged_in']['name']);
    $role = ($this->session->userdata['logged_in']['role']);
    if ($role != "Admin") {
        header("location: /");
    }
}
?>
<main role="main">
    <div class="jumbotron">
        <div class="container">
            <center>
                <h2 class="display-4">Users</h2>
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
        <a class="btn btn-success" data-bs-toggle="modal" data-bs-target="#add_edit_user"><i class="fa fa-user-plus"></i></a>
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th scope="col">Username</th>
                    <th scope="col" class="mobile-hide">Real Name</th>
                    <th scope="col">Role</th>
                    <th scope="col">Change</th>
                    <th scope="col">Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($users)) {
                    foreach ($users as $user) {
                        echo '<tr>';
                        echo '<tr id="' . $user['id'] . '">';
                        echo  '<td>' . $user['name'] . '</td>';
                        echo  '<td class="mobile-hide">' . $user['view_name'] . '</td>';
                        echo  '<td>' . $user['role'] . '</td>';
                        echo "<td><a href='/users/edit/" . $user['id'] . "' class='btn btn-outline-info'><i class='fa fa-edit'></i></a></td>";
                        if ($user['name'] == $username) {
                            echo "<td><button id='" . $user['id'] . "' class='btn btn-outline-danger' onclick='DeleteUser(this.id)' disabled><i class='fa fa-trash'></i></button></td>";
                        } else {
                            echo "<td><button id='" . $user['id'] . "' class='btn btn-outline-danger' onclick='DeleteUser(this.id)'><i class='fa fa-trash'></i></button></td>";
                        }

                        echo '</tr>';
                    }
                } ?>
            </tbody>
        </table>
    </div>
    <div class="modal fade" id="add_edit_user" tabindex="-1" aria-labelledby="add_edit_userLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel"><?= lang('add_user') ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php include("application/views/users/create.php") ?>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
    function DeleteUser(id) {
        var r = confirm("Delete User with id: " + id + "?");
        if (r == true) {
            $.post("/users/delete", {
                id: id
            }).done(function(o) {
                console.log('user deleted from the server.');
                $('[id^=' + id + ']').remove();
            });
        }
    }
</script>