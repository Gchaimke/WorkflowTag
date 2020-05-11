<main role="main">
    <center>
        <div class="jumbotron jumbotron-fluid">
            <div class="container">
                <h1 class="display-4">Workflow Tag</h1>
                <p class="lead">System Status</p>
            </div>
        </div>
    </center>
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
        </ul>
    </div>
</main>