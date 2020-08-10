<?php
if (isset($message_display)) {
      echo "<div class='alert alert-danger' role='alert'>";
      echo $message_display . '</div>';
}
if (validation_errors()) {
      echo "<div class='alert alert-danger' role='alert'>" . validation_errors() . "</div>";
}

if (!isset($rma_form)) {
      header("location: /production/rma/");
} else {
      $data = $rma_form[0];
}
?>
<link rel="stylesheet" href="<?php echo base_url('assets/css/print.css?'.filemtime('assets/css/print.css')); ?>">
<?php echo "<img class='img-thumbnail checklist-logo' src='/assets/img/logo.png'>" ?>
<div id="form-messages" class='alert hidden' data-url="/production/rma/<?php echo $data['project'] ?>" role='alert'></div>
<main role="main">
      <div class="jumbotron">
            <div class="container">
                  <center>
                        <h2 class="display-3"><?php echo $data['project'] ?> RMA #<?php echo $data['number'] ?></h2>
                  </center>
            </div>
      </div>
      <div class="container">

            <?php echo form_open("production/update_rma/", "id=ajax-form"); ?>
            <input type='hidden' name='client' value='<?php echo $data['client'] ?>'>
            <input type='hidden' name='project' value='<?php echo $data['project'] ?>'>
            <input type='hidden' name='id' value='<?php echo $data['id'] ?>'>
            <div class="mx-auto text-center p-4 col-12 ">
                  <div class="form-row">
                        <div class="input-group mb-2 col-md-4">
                              <div class="input-group-prepend">
                                    <div class="input-group-text">RMA #</div>
                              </div>
                              <input type='text' class="form-control" name='number' value='<?php echo $data['number'] ?>' disabled>
                        </div>
                        <div class="input-group mb-2 col-md-4">
                              <div class="input-group-prepend">
                                    <div class="input-group-text">SN</div>
                              </div>
                              <input type='text' class="form-control" name='serial' value='<?php echo $data['serial'] ?>' required>
                        </div>
                        <div class="input-group mb-2 col-md-4">
                              <div class="input-group-prepend">
                                    <div class="input-group-text">Date</div>
                              </div>
                              <input type='date' class="form-control" name='date' value="<?php echo $data['date'] ?>">
                        </div>
                  </div>
                  <div class="form-row">
                        <div class="input-group mb-2 col-12">
                              <div class="input-group-prepend">
                                    <div class="input-group-text">Client Problem Description</div>
                              </div>
                              <textarea type='text' rows="5" class="form-control" name='problem'><?php echo $data['problem'] ?></textarea>
                        </div>
                  </div>
                  <div class="form-row">
                        <div class="input-group mb-2 col-12">
                              <div class="input-group-prepend">
                                    <div class="input-group-text">Repair Description</div>
                              </div>
                              <textarea type='text' rows="5" class="form-control" name='repair'><?php echo $data['repair'] ?></textarea>
                        </div>
                  </div>
                  <div class="form-row">
                        <div class="input-group mb-2 col-12">
                              <div class="input-group-prepend">
                                    <div class="input-group-text">Required Parts for Repair</div>
                              </div>
                              <textarea type='text' rows="5" class="form-control" name='parts'><?php echo $data['parts'] ?></textarea>
                        </div>
                  </div>
                  <div class="form-row">
                        <div class="input-group mb-2 col-md-6">
                              <div class="input-group-prepend">
                                    <div class="input-group-text">Rma From</div>
                              </div>
                              <input type='text' class="form-control" name='assembler' value='<?php echo $data['client'] ?>'>
                        </div>
                        <div class="input-group mb-2 col-md-6">
                              <div class="input-group-prepend">
                                    <div class="input-group-text">Checked by</div>
                              </div>
                              <input type='text' class="form-control" name='assembler' value='<?php echo $data['assembler'] ?>'>
                        </div>
                  </div>

                  <input type='submit' class="btn btn-info my-5 print-hide" name='submit' value='Update'>
            </div>
            <?php echo form_close(); ?>
      </div>
</main>