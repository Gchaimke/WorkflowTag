<center>
    <div class="jumbotron jumbotron-fluid">
        <div class="container">
            <h1 class="display-4">
                <?php
                if (isset($message_display)) {
                    echo "<div class='alert alert-error' role='alert'>";
                    echo $message_display . '</div>';
                }
                ?>
            </h1>
        </div>
    </div>
</center>