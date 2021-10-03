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
<div id="form-messages" class='alert hidden' data-url="/forms/edit?type=qc&client=<?= $_GET['client'] . "&project=$project&id=" ?>" role='alert'></div>
<nav id='nav_main_category_data' data-url="/forms?type=qc&client=<?= $_GET['client'] . "&project=" . $project ?>" data-url-name="<?= $client_name . " " . $project ?> QC" hidden></nav>
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
            <div class="mx-auto text-center col-12 ">
                  <div class="row">
                        <div class="mb-2 col-md-3">
                              <div class="form-floating">
                                    <input type='number' class="form-control" name='number' required>
                                    <label>QC #</label>
                              </div>
                        </div>
                        <div class="mb-2 col-md-3">
                              <div class="form-floating">
                                    <input type='text' class="form-control" name='serial'>
                                    <label>SN</label>
                              </div>
                        </div>
                        <div class="mb-2 col-md-3">
                              <div class="form-floating">
                                    <input type='text' class="form-control" name='product_num' value=''>
                                    <label>Product Number</label>
                              </div>
                        </div>
                        <div class="mb-2 col-md-3">
                              <div class="form-floating">
                                    <input type='date' class="form-control" name='date' value="<?php echo date("Y-m-d"); ?>">
                                    <label>Date</label>
                              </div>
                        </div>

                        <div class="mb-2 col-md-12">
                              <div class="form-floating">
                                    <textarea class="form-control" name='problem' style="height: 150px;"></textarea>
                                    <label>Client Problem Description</label>
                              </div>
                        </div>
                  </div>
                  <input type='hidden' class="form-control" name='client' value='<?php echo $client_name ?>'>
                  <input type='submit' class="btn btn-info my-5" name='submit' value='Submit'>
            </div>
            <?php echo form_close(); ?>
      </div>
</main>