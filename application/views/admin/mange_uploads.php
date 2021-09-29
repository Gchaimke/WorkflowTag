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
                <h2 class="display-3">File Manager</h2>
            </center>
        </div>
    </div>
    <div class="container">
        <?php
        if (isset($message_display)) {
            echo "<div class='alert alert-success' role='alert'>";
            echo $message_display . '</div>';
        }
        if (isset($folders)) {
            echo $folders;
        }
        ?>
    </div>
</main>
<script>
    function delFile(id) {
        var file = $(id).attr('data-file');
        var r = confirm("Delete File " + file + "?");
        if (r == true) {
            $.post("/production/delete_photo", {
                photo: file
            }).done(function(o) {
                console.log('File deleted from the server.');
                sleep(1000)
                location.reload();
            });
        }
    }
</script>