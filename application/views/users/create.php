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
                        <h5>New User Registaration</h5>
                  </center>
            </div>
      </div>
      <div class="container">
            <div class="row">
                  <div class="col-xl-5 col-lg-6 col-md-8 col-sm-10 mx-auto text-center form p-4">
                        <?php
                        if (isset($message_display)) {
                              echo "<div class='alert alert-danger' role='alert'>";
                              echo $message_display . '</div>';
                        }
                        if (validation_errors()) {
                              echo "<div class='alert alert-danger' role='alert'>" . validation_errors() . "</div>";
                        }
                        ?>
                        <?php echo form_open('users/create', 'class=user-create'); ?>
                        <div class="form-row">
                              <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                          <div class="input-group-text">Role</div>
                                    </div>
                                    <select class="form-control" name='role'>
                                          <?php if (isset($settings)) {
                                                $arr = explode(",", $settings[0]['roles']);
                                                foreach ($arr as $role) {
                                                      echo '<option>' . $role . '</option>';
                                                }
                                          }
                                          ?>
                                    </select>
                              </div>
                        </div>
                        <div class="form-row">
                              <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                          <div class="input-group-text">Username</div>
                                    </div>
                                    <input type='text' class="form-control" name='name' required>
                              </div>
                        </div>
                        <div class="form-row">
                              <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                          <div class="input-group-text">Real Name</div>
                                    </div>
                                    <input type='text' class="form-control" name='view_name' required>
                              </div>
                        </div>

                        <div class="form-row">
                              <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                          <div class="input-group-text">Password</div>
                                    </div>
                                    <input type='text' class="form-control" name='password' required>
                              </div>
                        </div>

                        <div class="form-row">
                              <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                          <div class="input-group-text">Email</div>
                                    </div>
                                    <input type='text' class="form-control ltr" name='email' required>
                              </div>
                        </div>
                        <input type='submit' class="btn btn-info" name='submit' value='Add User'>
                        <?php echo form_close(); ?>
                  </div>
            </div>
      </div>
</main>