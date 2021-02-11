<?php
if (isset($message_display)) {
      echo "<div class='alert alert-danger' role='alert'>";
      echo $message_display . '</div>';
}
if (validation_errors()) {
      echo "<div class='alert alert-danger' role='alert'>" . validation_errors() . "</div>";
}

if ($project == 'Production') {
      $fuild_type = 'text';
      $client = '';
} else {
      $fuild_type = 'hidden';
      $client = $client_name;
}

?>
<div id="form-messages" class='alert hidden' data-url="/rma/view_project_rma/<?php echo $client . "/" . $project ?>" role='alert'></div>
<nav id='nav_main_category_data' data-url="/rma/view_project_rma/<?php echo $client . "/" . $project ?>" data-url-name="<?=$client." ".$project?> RMA" hidden></nav>
<main role="main">
      <div class="jumbotron">
            <div class="container">
                  <center>
                        <h2 class="display-3">New <?php echo $project ?> RMA</h2>
                  </center>
            </div>
      </div>
      <div class="container">
            <?php echo form_open("rma/create_rma/", "id=ajax-form"); ?>
            <input type='hidden' name='project' value='<?php echo $project ?>'>
            <input type='hidden' name='assembler' value='<?php echo $this->session->userdata['logged_in']['name'] ?>'>
            <div class="mx-auto text-center p-4 col-12 ">
                  <div class="form-row">
                        <div class="input-group mb-2 col-lg-4">
                              <div class="input-group-prepend">
                                    <div class="input-group-text">RMA Number</div>
                              </div>
                              <input type='text' class="form-control" name='number' required>
                        </div>
                        <div class="input-group mb-2 col-lg-5">
                              <div class="input-group-prepend">
                                    <div class="input-group-text">Serial Number</div>
                              </div>
                              <input type='text' class="form-control" name='serial'>
                        </div>
                        <div class="input-group mb-2 col-lg-3">
                              <div class="input-group-prepend">
                                    <div class="input-group-text">Date</div>
                              </div>
                              <input type='date' class="form-control" name='date' value="<?php echo date("Y-m-d"); ?>">
                        </div>
                  </div>
                  <div class="form-row">
                        <div class="input-group mb-2 col-lg-6">
                              <div class="input-group-prepend">
                                    <div class="input-group-text">Client Name: <?php echo $client ?></div>
                              </div>
                              <input type='<?php echo $fuild_type ?>' class="form-control" name='client' value='<?php echo $client ?>'>
                        </div>
                        <div class="input-group mb-2 col-lg-6">
                              <div class="input-group-prepend">
                                    <div class="input-group-text">Product Number</div>
                              </div>
                              <input type='text' class="form-control" name='product_num' value=''>
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
                  <div class="form-row">
                        <div class="input-group mb-2 col-12">
                              <div class="input-group-prepend">
                                    <div class="input-group-text">Repair Description</div>
                              </div>
                              <textarea type='text' rows="5" class="form-control" name='repair'></textarea>
                        </div>
                  </div>
                  <div class="form-row">
                        <div class="input-group mb-2 col-12">
                              <div class="input-group-prepend">
                                    <div class="input-group-text">Required Parts for Repair</div>
                              </div>
                              <textarea type='text' rows="5" class="form-control" name='parts'></textarea>
                        </div>
                  </div>
                  <input type='submit' class="btn btn-info my-5" name='submit' value='Submit'>
            </div>
            <?php echo form_close(); ?>
      </div>
</main>