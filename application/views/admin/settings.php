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
        if (isset($response) && $response != "") {
            echo '<div class="alert alert-success" role="alert">';
            echo $response . ' </div>';
        }
        ?>
        <?php
        echo form_open('admin/settings', 'class=user-create'); 
        if (isset($clients) && $clients != "") {
            echo '<textarea rows="4" cols="30">';
            echo $clients[0]['clients'] . ' </textarea>';
        }
        echo "<input type='submit' class='btn btn-info btn-block' name='submit' value='Save'>";
        echo form_close();
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