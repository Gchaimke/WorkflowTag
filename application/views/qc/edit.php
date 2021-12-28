<?php
if (isset($message_display)) {
      echo "<div class='alert alert-danger' role='alert'>";
      echo $message_display . '</div>';
}
if (validation_errors()) {
      echo "<div class='alert alert-danger' role='alert'>" . validation_errors() . "</div>";
}

if (!isset($form)) {
      header("location: /forms/");
}

$client_id = null;
if (isset($_GET['client'])) {
      $client_id = $_GET['client'];
}
if (isset($client) && isset($client['id'])) {
      $client_id = $client['id'];
}
?>
<link rel="stylesheet" href="<?php echo base_url('assets/css/print.css?' . filemtime('assets/css/print.css')); ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/rma.css?' . filemtime('assets/css/rma.css')); ?>">
<?php echo "<img class='img-thumbnail checklist-logo' src='/assets/img/logo.png'>" ?>
<div id="form-messages" class='alert hidden' data-url="/forms/edit?type=qc&client=<?= $client_id ?>&id=<?php echo $form->id ?>" role='alert'></div>
<nav id='nav_main_category_data' data-url="/forms?type=qc&client=<?= $client_id . "&project=" . $form->project ?>" data-url-name="<?= $form->project ?> QC " hidden></nav>
<main role="main">
      <div class="jumbotron">
            <div class="container">
                  <center>
                        <h2 class="display-3"><?php echo $form->project ?> qc #<?php echo $form->number ?></h2>
                  </center>
            </div>
      </div>
      <div class="control_btn_container d-print-none">
            <button class="btn btn-info mx-3" onclick="document.getElementById('browse').click();"><i class="fa fa-camera"></i></button>
            <button id="save" type='submit' class="btn btn-success navbar-btn mx-3" value="Save" onclick="document.getElementById('update_btn').click();"><i class="fa fa-save"></i></button>
      </div>
      <div class="container">
            <?php echo form_open("forms/update/", "id=ajax-form"); ?>
            <input type='hidden' name='type' value='<?php echo $_GET['type'] ?>'>
            <input type='hidden' name='client' value='<?php echo $form->client ?>'>
            <input type='hidden' name='project' value='<?php echo $form->project ?>'>
            <input type='hidden' name='id' value='<?php echo $form->id ?>'>
            <input id="picrures_count" type='hidden' name='pictures' value=''>
            <div class="mx-auto text-center col-12 ">
                  <div class="row">
                        <div class="mb-2 col-md-3">
                              <div class="form-floating">
                                    <input type='number' class="form-control" name='number' value='<?php echo $form->number ?>' disabled>
                                    <label>QC #</label>
                              </div>
                        </div>
                        <div class="mb-2 col-md-3">
                              <div class="form-floating">
                                    <input type='text' class="form-control" name='serial' value='<?php echo $form->serial ?>'>
                                    <label>SN</label>
                              </div>
                        </div>
                        <div class="mb-2 col-md-3">
                              <div class="form-floating">
                                    <input type='text' class="form-control" name='product_num' value='<?php echo $form->product_num ?>'>
                                    <label>Product Number</label>
                              </div>
                        </div>
                        <div class="mb-2 col-md-3">
                              <div class="form-floating">
                                    <input type='date' class="form-control" name='date' value="<?php echo $form->date ?>">
                                    <label>Date</label>
                              </div>
                        </div>

                        <div class="mb-2 col-md-12">
                              <div class="form-floating">
                                    <textarea class="form-control" name='problem' style="height: 150px;"><?php echo $form->problem ?></textarea>
                                    <label>Client Problem Description</label>
                              </div>
                        </div>
                        <div class="input-group mb-2 col-md">
                              <div class="input-group-text">QC From</div>
                              <input type='text' class="form-control" name='client' value='<?php echo $form->client ?>' disabled>
                        </div>
                        <div class="input-group mb-2 col-md">
                              <div class="input-group-text">Checked by</div>
                              <input type='text' class="form-control" name='user' value='<?php echo $form->user ?>'>
                        </div>
                  </div>
                  <input id="update_btn" type='submit' style="display:none;" class="btn btn-info my-5 print-hide" name='submit' value='Update'>
            </div>
            <?php echo form_close(); ?>
      </div>
      <div id="photo-stock" class="container">
            <center>
                  <h2>Pictures</h2>
            </center>
            <div id="photo-messages" class='alert hidden' role='alert'></div>
            <?php
            $working_dir = 'Uploads/' . $form->client . '/' . $form->project . '/QC/' . $form->number . '/';
            echo "<script>
                  var photoCount=0;
                  var id='" . $form->id . "';
                  var project='" . $form->project . "';
                  var serial='" . $form->number . "';
                  var user ='" . $form->user . "';
                  var client='" . $form->client . "';
                  var working_dir='$working_dir';
            </script>";  //pass PHP data to JS
		include("application/views/storage/view_pictures.php");

            ?>
      </div>
      <input id="browse" style="display:none;" type="file" onchange="snapPhoto()" name="photos" multiple>
      <div id="preview"></div>
</main>
<script>
      $(document).ready(function() {
            $("#picrures_count").val(photoCount);
      });
      document.title = 'qc <?php echo $form->number ?>';
</script>