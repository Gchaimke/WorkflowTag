<?php
if (isset($message_display)) {
      echo "<div class='alert alert-danger' role='alert'>";
      echo $message_display . '</div>';
}
if (validation_errors()) {
      echo "<div class='alert alert-danger' role='alert'>" . validation_errors() . "</div>";
}

if (!isset($project)) {
      $fuild_type = 'text';
      $client_name = '';
      $project = 'Other';
} else {
      $fuild_type = 'hidden';
}

?>
<div id="form-messages" class='alert hidden' data-url="/forms?type=qc&client=<?php echo $client_name . "&project=" . $project ?>" role='alert'></div>
<nav id='nav_main_category_data' data-url="/forms?type=qc&client=<?php echo $client_name . "&project=" . $project ?>" data-url-name="<?=$client_name." ".$project?> QC" hidden></nav>
<main role="main">
      <div class="jumbotron">
            <div class="container">
                  <center>
                        <h2 class="display-3">New <?php echo $project ?> qc</h2>
                  </center>
            </div>
      </div>
      <div class="container">
            <?php echo form_open("forms/new/", "id=ajax-form"); ?>
            <input type='hidden' name='type' value='<?php echo $_GET['type'] ?>'>
            <input type='hidden' name='project' value='<?php echo $project ?>'>
            <input type='hidden' name='user' value='<?php echo $this->session->userdata['logged_in']['name'] ?>'>
            <div class="mx-auto text-center p-4 col-12 ">
                  <div class="form-row">
                        <div class="input-group mb-2 col-lg-2">
                              <div class="input-group-prepend">
                                    <div class="input-group-text">QC #</div>
                              </div>
                              <input type='number' class="form-control" name='number' required>
                        </div>
                        <div class="input-group mb-2 col-lg-3">
                              <div class="input-group-prepend">
                                    <div class="input-group-text">SN</div>
                              </div>
                              <input type='text' class="form-control" name='serial'>
                        </div>
                        <div class="input-group mb-2 col-lg-4">
                              <div class="input-group-prepend">
                                    <div class="input-group-text">Product Number</div>
                              </div>
                              <input type='text' class="form-control" name='product_num' value=''>
                        </div>
                        <div class="input-group mb-2 col-lg-3">
                              <div class="input-group-prepend">
                                    <div class="input-group-text">Date</div>
                              </div>
                              <input type='date' class="form-control" name='date' value="<?php echo date("Y-m-d"); ?>">
                        </div>
                  </div>
                  <div class="form-row">
                        <div class="input-group mb-2 col-12">
                              <div class="input-group-prepend">
                                    <div class="input-group-text">Client Problem Description</div>
                              </div>
                              <textarea type='text' rows="5" class="form-control" name='problem'></textarea>
                        </div>
                  </div>
                  <input type='hidden' class="form-control" name='client' value='<?php echo $client_name ?>'>

                  <input type='submit' class="btn btn-info my-5" name='submit' value='Submit'>
            </div>
            <?php echo form_close(); ?>
      </div>
</main>