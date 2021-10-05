<?php
if (isset($this->session->userdata['logged_in'])) {
      $user = $this->session->userdata['logged_in'];
      if ($user['role'] != "Admin") {
            header("location: /");
      }
}
?>
<main role="main">
      <div class="jumbotron">
            <div class="container">
                  <center>
                        <h5><?= lang('add_user') ?></h5>
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
                                          <div class="input-group-text"><?= lang('role') ?></div>
                                    </div>
                                    <select class="form-select" name='role'>
                                          <?php if (isset($settings)) {
                                                $arr = explode(",", $settings['roles'] . "," . $settings['user_roles']);
                                                foreach ($arr as $role) {
                                                      $role_lang =  lang($role) != "" ? lang($role) : $role;
                                                      echo "<option value='$role'>$role_lang</option>";
                                                }
                                          }
                                          ?>
                                    </select>
                              </div>
                        </div>
                        <div class="form-row">
                              <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                          <div class="input-group-text"><?= lang('language') ?></div>
                                    </div>
                                    <select class="form-select" name='language'>
                                          <?php if (isset($languages)) {
                                                echo "<option value='system'>" . lang('default') . "</option>";
                                                foreach ($languages as $lang) {
                                                      if ($user['language'] == $lang) {
                                                            echo "<option selected>$lang</option>";
                                                      } else {
                                                            echo "<option>$lang</option>";
                                                      }
                                                }
                                          }
                                          ?>
                                    </select>
                              </div>
                        </div>
                        <div class="form-row">
                              <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                          <div class="input-group-text"><?= lang('username') ?></div>
                                    </div>
                                    <input type='text' class="form-control" name='name' required>
                              </div>
                        </div>
                        <div class="form-row">
                              <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                          <div class="input-group-text"><?= lang('view_name') ?></div>
                                    </div>
                                    <input type='text' class="form-control" name='view_name' required>
                              </div>
                        </div>

                        <div class="form-row">
                              <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                          <div class="input-group-text"><?= lang('password') ?></div>
                                    </div>
                                    <input type='text' class="form-control" name='password' required>
                              </div>
                        </div>

                        <div class="form-row">
                              <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                          <div class="input-group-text"><?= lang('email') ?></div>
                                    </div>
                                    <input type='text' class="form-control ltr" name='email'>
                              </div>
                        </div>
                        <div class="row">
                              <div><?= lang('clients') ?></div>
                              <?php
                              $user_clients = explode(",", $user['clients']);
                              foreach ($clients as $key => $client) {
                                    echo "<div class='form-check'>";
                                    echo "<input class='form-check-input' type='checkbox' name='clients[{$client['id']}]' value='{$client['id']}' aria-label='{$client['name']}'>
						<label class='form-check-label' for='clients'>{$client['name']}</label>";
                                    echo "</div>";
                              }
                              ?>
                        </div>
                        <input type='submit' class="btn btn-info" name='submit' value='<?= lang('add_user') ?>'>
                        <?php echo form_close(); ?>
                  </div>
            </div>
      </div>
</main>