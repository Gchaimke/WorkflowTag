<?php
if (isset($this->session->userdata['logged_in'])) {
    if($this->session->userdata['logged_in']['role'] != "Admin"){
        header("location: /");
    }
}
?>

<main role="main">
    <div class="jumbotron">
        <div class="container">
            <center>
                <h2 class="display-3">Settings</h2>
            </center>
        </div>
    </div>
    <div class="container">
    <?php
        $users_count = 0;
        $clients_count = 0;
        $checklists_count = 0;
        if (isset($message_display)) {
            echo "<div class='alert alert-success' role='alert'>";
            echo $message_display . '</div>';
        }

        if (isset($users) and isset($clients) and isset($checklists)) {
            $users_count = $users;
            $clients_count = $clients;
            $checklists_count = $checklists;
        }
        ?>
        <ul class="list-group">
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Users
                <span class="badge badge-primary badge-pill"><?php echo $users_count ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Clients
                <span class="badge badge-primary badge-pill"><?php echo $clients_count ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Checklists
                <span class="badge badge-primary badge-pill"><?php echo $checklists_count ?></span>
            </li>
        </ul><br>
        <?php
        if (isset($response) && $response != "") {
            echo '<div class="alert alert-success" role="alert">';
            echo $response . ' </div>';
        }
        ?>
        <?php
        echo form_open('admin/settings', 'class=user-create');
        echo '<div class="form-group"><label>User Roles</label><textarea name="roles" class="form-control" rows="2" cols="30">';
        if (isset($settings) && $settings != "") {
            echo $settings[0]['roles'];
        }
        echo "</textarea></div>";
        echo "<input type='submit' class='btn btn-info btn-block' name='submit' value='Save'>";
        echo form_close();
        ?>
        </br>
        <button class="btn btn-info" onclick="createDB(0)">Create DB</button>
    </div>
</main>

<script>
    function createDB(id) {
        $.post("/admin/create", {
            id: id
        }).done(function(o) {
            console.log('Databases created');
            alert(o);
        });
    }
</script>