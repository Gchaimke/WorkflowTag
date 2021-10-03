<?php
$assembler = $this->session->userdata['logged_in']['name'];
if (isset($message_display)) {
      echo "<div class='alert alert-danger' role='alert'>";
      echo $message_display . '</div>';
}
if (validation_errors()) {
      echo "<div class='alert alert-danger' role='alert'>" . validation_errors() . "</div>";
}

if (!isset($project)) {
      $fuild_type = 'text';
      $client = '';
      $project = 'Other';
} else {
      $fuild_type = 'hidden';
      $client = $client_name;
}

?>
<link rel="stylesheet" href="<?= base_url('assets/css/rma.css?' . filemtime('assets/css/rma.css')); ?>">
<div id="form-messages" class='alert hidden' data-url="/forms/edit?type=rma&client=<?= $_GET['client'] ?>&id=" role='alert'></div>
<nav id='nav_main_category_data' data-url="/forms?type=rma&client=<?= $_GET['client'] . "&project=" . $project ?>" data-url-name="<?= $project ?> RMA" hidden></nav>
<main role="main">
      <div class="jumbotron">
            <div class="container">
                  <center>
                        <h2 class="display-3"><?php echo $project ?> RMA receiving form</h2>
                  </center>
            </div>
      </div>
      <div class="container">
            <?php echo form_open("forms/new/", "id=ajax-form"); ?>
            <input type='hidden' name='type' value='<?php echo $_GET['type'] ?>'>
            <input type='hidden' name='project' value='<?php echo $project ?>'>
            <div class="mx-auto text-center col">
                  <div class="row">
                        <div class="input-group mb-2 col-md">
                              <div class="input-group-prepend">
                                    <div class="input-group-text">Received from: <?php echo $client ?></div>
                              </div>
                              <input type='<?php echo $fuild_type ?>' class="form-control" name='client' value='<?php echo $client ?>'>
                        </div>
                        <div class="input-group mb-2 col-md">
                              <div class="input-group-prepend">
                                    <div class="input-group-text">Received by: <?= $assembler ?></div>
                              </div>
                              <input type='<?php echo $fuild_type ?>' class="form-control" name='user' value='<?= $assembler ?>'>
                        </div>
                        <div class="input-group mb-2 col-md">
                              <div class="input-group-prepend">
                                    <div class="input-group-text">Date</div>
                              </div>
                              <input type='date' class="form-control" name='date' value="<?php echo date("Y-m-d"); ?>">
                        </div>
                  </div>
                  <hr>
                  <div class="row">
                        <div class="input-group mb-2 col-md">
                              <div class="input-group-prepend">
                                    <div class="input-group-text">RMA Number</div>
                              </div>
                              <input type='number' class="form-control" name='number' required>
                        </div>
                        <div class="input-group mb-2 col-md">
                              <div class="input-group-prepend">
                                    <div class="input-group-text">Serial Number</div>
                              </div>
                              <input type='text' class="form-control" name='serial'>
                        </div>
                        <div class="input-group mb-2 col-md">
                              <div class="input-group-prepend">
                                    <div class="input-group-text">Product Number</div>
                              </div>
                              <input type='text' class="form-control" name='product_num' value=''>
                        </div>
                  </div>
                  <button id="save" type='submit' class="btn btn-success"><i class="fa fa-save"></i> Add Form</button>
                  <?php echo form_close(); ?>
            </div>
</main>
<script>
      var assembler = '<?= $assembler ?>';
      var name = '';
      $("input:checkbox").click(function(e) {
            name = $(this).attr('name');
            if ($(event.target).is(":checked")) {
                  $("[name=" + name + "]").val(assembler);
                  $(this).val(assembler);
            } else {
                  $("[name=" + name + "]").val('');
                  $(this).val("");
            }
      });
</script>