<main role="main">
      <div class="container">
            <div class="jumbotron">
                  <div class="container">
                        <center>
                              <h2 class="display-3">Add checklist</h2>
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
                  <?php echo form_open('checklists/add_checklist', 'class=user-create'); ?>
                  <select  class="form-control" name='client' >
                        <?php if (isset($settings)) {
                              $arr = explode(",", $settings[0]['clients']);
                              foreach ($arr as $role) {
                                    echo '<option>' . $role . '</option>';
                              }
                        }
                        ?>
                  </select></br>
                  <select class="form-control" name='project'>
                        <?php if (isset($projects)) {
                              foreach ($projects as $project) {
                                    echo '<option>' . $project['project'] . '</option>';
                              }
                        }
                        ?>
                  </select></br>
                  <input class="form-control " type='text' name='serial' placeholder="Serial Number"></br>
                  <input type='text' class="form-control" name='date' value="<?php echo date("Y-m-d"); ?>"></br>
                  <input type='submit' class="btn btn-info btn-block" name='submit' value='Submit'>
                  <?php echo form_close(); ?>
            </center>
      </div>
</main>
