<main role="main">
      <div class="container">
            <div class="jumbotron">
                  <div class="container">
                        <center>
                              <h2 class="display-3">Create checklist</h2>
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

                  <?php echo form_open('checklist/create', 'class=user-create'); ?>
                  <select class="form-control" name='project'>
                        <option>Flex2</option>
                        <option>Lap3</option>
                        <option>Flex Leg</option>
                  </select>
                  <input class="form-control " type='text' name='serial' placeholder="Serial Number">
                  <input type='hidden' name='data' value="">
                  <input type='hidden' name='progress' value="0">
                  <input type='hidden' name='assembler' value="">
                  <input type='hidden' name='qc' value="">
                  <input type='text' class="form-control" name='date' value="<?php echo date("Y-m-d"); ?>">
                  <input type='submit' class="btn btn-info btn-block" name='submit' value='Submit'>
                  <?php echo form_close(); ?>
            </center>
      </div>