<?= form_open('users/create', 'id=user-create'); ?>
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
<div class="modal-footer">
      <input type='submit' class="btn btn-primary" name='submit' value='<?= lang('add_user') ?>'>
</div>
<?= form_close(); ?>