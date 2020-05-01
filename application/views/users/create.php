<main role="main">
      <div class="jumbotron">
            <div class="container">
                  <center>
                        <h2 class="display-3">Create new user.</h2>
                  </center>
            </div>
      </div>
      <div class="container">
            <center>
                  <?php
                  echo "<div class='error_msg'>";
                  if (isset($message_display)) {
                        echo $message_display;
                  }
                  if (isset($response)) {
                        echo '<div class="alert alert-success" role="alert">';
                        echo $response . ' </div>';
                  }
                  echo "<div class='error_msg'>".validation_errors()."</div>";
                  echo form_open('users/create', 'class=user-create'); ?>
                  <select class="form-control" name='userrole'>
                        <option>Assembler</option>
                        <option>QC</option>
                        <option>Admin</option>
                  </select>
                  <?php 
                  echo form_input('username').'<br/>';
                  echo form_password('password'); ?>
                  <td><input type='submit' class="btn btn-info btn-block" name='submit' value='Submit'>
                        <?php echo form_close(); ?>
            </center>
      </div>
</main>