<main role="main">
    <div class="jumbotron">
        <div class="container">
            <center>
                <h2 class="display-3">Settings content goes here...</h2>
            </center>
        </div>
    </div>
    <div class="container">
        <?php
        if (isset($responseUsers)) {
            echo '<div class="alert alert-success" role="alert">';
            echo $responseUsers . ' </div>';
        }
        if (isset($responseChecklist)) {
            echo '<div class="alert alert-success" role="alert">';
            echo $responseChecklist . ' </div>';
        }
        ?>
        <form method='post' action='<?php echo base_url('/admin/settings'); ?>'>
            <table>
                <tr>
                    <td><input type='submit' name='submit' value='Create DB' class="btn btn-info"></td>
                </tr>
            </table>
        </form>
    </div>
</main>