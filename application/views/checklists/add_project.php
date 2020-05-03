<main role="main">
      <div class="container">
            <div class="jumbotron">
                  <div class="container">
                        <center>
                              <h2 class="display-3">Add Project</h2>
                        </center>
                  </div>
            </div>
            <center>
                  <?php
                  if (isset($message_display)) {
                        echo "<div class='alert alert-danger' role='alert'>";
                        echo $message_display . '</div>';
                  }
                  if (validation_errors()) {
                        echo "<div class='alert alert-danger' role='alert'>" . validation_errors() . "</div>";
                  }
                  ?>

                  <?php echo form_open('checklists/add_project', 'class=user-create'); ?>
                  <select class="form-control" name='client'>
                        <?php if (isset($settings)) {
                              $arr = explode(",",$settings[0]['clients']);
                              foreach ($arr as $client) {
                                    echo '<option>' . $client . '</option>';
                              }
                        }
                        ?>
                  </select></br>
                  <input type='text' class="form-control" name='project' value=""></br>
                  <textarea class="form-control" name='data' rows="10" cols="100"></textarea></br>
                  <input type='submit' class="btn btn-info btn-block" name='submit' value='Submit'>
                  <?php echo form_close(); ?>
            </center>
      </div>
</main>