<?php
if (isset($this->session->userdata['logged_in'])) {
    if ($this->session->userdata['logged_in']['role'] != "Admin") {
        header("location: /");
    }
}
?>

<main role="main">
    <div class="jumbotron">
        <div class="container">
            <center>
                <h2 class="display-3"><?= lang('settings') ?></h2>
            </center>
        </div>
    </div>
    <div class="container">
        <?php
        $users_count = 0;
        $clients_count = 0;
        $checklists_count = 0;
        $rma_forms_count = 0;
        if (isset($message_display)) {
            echo "<div class='alert alert-success' role='alert'>";
            echo $message_display . '</div>';
        }

        if (isset($users) and isset($clients) and isset($checklists)) {
            $users_count = $users;
            $clients_count = $clients;
            $checklists_count = $checklists;
            $rma_forms_count = $rma_forms;
        }
        ?>
        <ul class="list-group">
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <?= lang('users') ?>
                <span><?php echo $users_count ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <?= lang('companies') ?>
                <span><?php echo $clients_count ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <?= lang('forms') ?><div>You saved minimum <b style="color: green;"><?php echo $checklists_count ?></b> pages!</div>
                <span><?php echo $checklists_count ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                RMA Forms
                <span><?php echo $rma_forms_count ?></span>
            </li>
        </ul><br>
        <div id="form-messages" class='alert hidden' role='alert'></div>
        <?php echo form_open('admin/save_settings', 'id=ajax-form', 'class=user-create'); ?>
        <div class="row">
            <div class="col-md-6">
                <div class="form-floating mb-2">
                    <select class="form-select" name='language'>
                        <?php if (isset($languages)) {
                            foreach ($languages as $lang) {
                                if ($settings['language'] == $lang) {
                                    echo "<option selected>$lang</option>";
                                } else {
                                    echo "<option>$lang</option>";
                                }
                            }
                        }
                        ?>
                    </select>
                    <label><?= lang('language') ?></label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-floating">
                    <?php
                    if (isset($settings) && $settings != "") {
                        echo '<textarea name="user_roles" class="form-control" rows="1" cols="30">' . $settings['user_roles'] . '</textarea>';
                    }
                    ?>
                    <label>
                        <?= lang('roles') ?>.
                    </label>
                    System roles is: <?= $settings['roles'] ?>
                </div>
            </div>

        </div>
        <?php
        echo "<input type='submit' class='btn btn-success' name='submit' value='" . lang('save') . "'>";
        echo form_close();
        ?>
        </br>
        <div class="container">
            <h3>Database Utils</h3>
            <button class="btn btn-warning m-3" onclick="createDB()"><?= lang('create_db') ?></button>
            <button class="btn btn-info m-3" onclick="upgradeDB()">Upgrade DB</button>
            <button class="btn btn-success m-3" onclick="backupDB()"><?= lang('backup_db') ?></button>
            <a id="last-db" class="m-5" style="display: none;" href=""><?= lang('download_db') ?></a>
        </div>
        <hr>
        <div class="container">
            <h3>File system Utils</h3>
            <button class="btn btn-info mt-3" onclick="RemoveEmptySubFolders()">Remove empty folders from uploads</button>
            <a target="_blank" class="btn btn-info mt-3" href="/production/generate_all_offline_files">Generate offline files</a>
        </div>
        <hr>
    </div>
</main>

<script>
    function createDB() {
        $.post("/admin/create_tables", {}).done(function(o) {
            // Make sure that the formMessages div has the 'success' class.
            $('#form-messages').addClass('alert-success');
            // Set the message text.
            $('#form-messages').html(o).fadeIn(1000).delay(3000).fadeOut(1000);
        });
    }

    function upgradeDB() {
        $.post("/admin/upgrade_db", {}).done(function(o) {
            // Make sure that the formMessages div has the 'success' class.
            $('#form-messages').addClass('alert-success');
            // Set the message text.
            $('#form-messages').html(o).fadeIn(1000).delay(3000).fadeOut(1000);
        });
    }

    function backupDB() {
        $.post("/admin/backupDB", {}).done(function(o) {
            // Make sure that the formMessages div has the 'success' class.
            $('#form-messages').addClass('alert-success');
            // Set the message text.
            $('#form-messages').html(o).fadeIn(1000).delay(3000).fadeOut(1000);
            $('#last-db').attr("href", "/" + o);
            $('#last-db').toggle();
        });
    }


    function RemoveEmptySubFolders() {
        $.post("/admin/RemoveEmptySubFolders", {}).done(function(o) {
            // Make sure that the formMessages div has the 'success' class.
            $('#form-messages').addClass('alert-success');
            // Set the message text.
            $('#form-messages').html(o).fadeIn(1000).delay(3000).fadeOut(1000);
        });
    }
</script>