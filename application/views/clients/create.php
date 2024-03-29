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
                        <h2 class="display-3">Add Client</h2>
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

                  <?php echo form_open('clients/create', 'class=user-create'); ?>
                  <input type="hidden" name="status" value="1">
                  <div class="form-floating mb-2">
                        <input id='client_name' type='text' class="form-control" name='name' value="" onchange="updateClient(this.value)">
                        <label>Client Name</label>
                  </div>

                  <div class="form-group mb-3">
                        <label>Select Logo : </label>
                        <input id="logo_path" type='hidden' name='logo'>
                        <button id="upload_logo" class="btn btn-outline-secondary" type="button" onclick="document.getElementById('browse').click();" disabled>Upload</button>
                        <img id="logo_img" class="img-thumbnail" src="" onclick="document.getElementById('browse').click();">
                        <input id="browse" style="display:none;" type="file" onchange="snapLogo()" disabled></hr>
                  </div>
                  <div class="form-floating mb-2">
                        <select class="form-select" name='status'>
                              <option value="1" selected>Active</option>
                              <option value="0">Old</option>
                        </select>
                        <label>Status</label>
                  </div>
                  <div class="row">
                        <div><?= lang('users') ?></div>
                        <div class='form-check text-start'>
                              <input class='form-check-input' type="checkbox" id="check_all"><label class='form-check-label' for='users'>Select all</label>
                        </div>
                        <div class="users_check">
                              <?php
                              $user_clients = array();
                              if (isset($client['users'])) {
                                    $user_clients = explode(",", $client['users']);
                              }
                              foreach ($users as $key => $user) {
                                    echo "<div class='form-check'>";
                                    echo "<input class='form-check-input' type='checkbox' name='users[{$user['id']}]' value='{$user['id']}' aria-label='{$user['name']}'>
						<label class='form-check-label' for='users'>{$user['name']} ({$user['role']})</label>";
                                    echo "</div>";
                              }
                              ?>
                        </div>
                  </div>
                  <input type='submit' class="btn btn-info btn-block" name='submit' value='Submit'>
                  <?php echo form_close(); ?>
            </center>
      </div>
</main>
<script>
      var client = document.getElementById("client_name").value;

      function updateClient(value) {
            client = value;
            $('#upload_logo').prop('disabled', false);
            $('#browse').prop('disabled', false);
      }
      var ext = '';
      $("#check_all").click(function() {
            $('input:checkbox').not(this).prop('checked', this.checked);
      });
</script>