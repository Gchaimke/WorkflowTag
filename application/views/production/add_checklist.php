<main role="main">
      <div class="jumbotron">
            <div class="container">
                  <center>
                        <h2 class="display-3"><?=lang('add_checklist')?></h2>
                  </center>
            </div>
      </div>
      <div class="container">
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
                  
                  <?php echo form_open("production/add_checklist/$project", 'class=user-create'); ?>
                  <h5 style="color: red;"><?=lang('batch_msg')?></h5>
                  <input type='hidden' name='client' value='<?php echo $client['name'] ?>'>
                  <input type='hidden' name='project' value='<?php echo $project ?>'>
                  <div class="form-group"><label>Serial template <?php echo $template ?></label>
                        <input class="form-control col-md-3" type='text' name='serial' placeholder="Serial Number">
                  </div></br>
                  <input type='date' class="form-control col-md-3" name='date' value="<?php echo date("Y-m-d"); ?>"></br>
                  <input type='submit' class="btn btn-info btn-block col-md-3" name='submit' value='<?=lang('save')?>'>
                  <?php echo form_close(); ?>
            </center>
      </div>
</main>