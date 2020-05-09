<?php
if (isset($this->session->userdata['logged_in'])) {
    if($this->session->userdata['logged_in']['role'] != "Admin"){
        header("location: /dashboard");
    }
}
?>
<main role="main">
      <div class="container">
            <div class="jumbotron">
                  <div class="container">
                        <center>
                              <h2 class="display-3">Add Client</h2>
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

                  <?php echo form_open('production/add_client', 'class=user-create'); ?>
                  <input type='text' class="form-control" name='name' value=""></br>
                  <textarea class="form-control" name='projects' rows="10" cols="100"></textarea></br>
                  <input type='submit' class="btn btn-info btn-block" name='submit' value='Submit'>
                  <?php echo form_close(); ?>
            </center>
      </div>
</main>