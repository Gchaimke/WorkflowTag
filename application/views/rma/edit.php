<?php
$assembler = $this->session->userdata['logged_in']['name'];
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

$working_dir = 'Uploads/' . $form->client . '/' . $form->project . '/RMA/' . $form->number . '/';

$client_id = null;
if (isset($_GET['client'])) {
      $client_id = $_GET['client'];
}
if (isset($client) && isset($client['id'])) {
      $client_id = $client['id'];
}

?>
<script src="<?php echo base_url('assets/js/jQUpload/jquery.ui.widget.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jQUpload/jquery.iframe-transport.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jQUpload/jquery.fileupload.js'); ?>"></script>
<link rel="stylesheet" href="<?= base_url('assets/css/print.css?' . filemtime('assets/css/print.css')); ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/rma.css?' . filemtime('assets/css/rma.css')); ?>">
<img class='img-thumbnail forms-logo' src='/assets/img/logo.png'>
<div id="form-messages" class='alert hidden' data-url="/forms/edit?type=rma&client=<?= $client_id ?>&id=<?= $form->id ?>" role='alert'></div>
<nav id='nav_main_category_data' data-url="/forms?type=rma&client=<?= $client_id . "&project=" . $form->project ?>" data-url-name="<?= $form->project ?> RMA " hidden></nav>
<main role="main">
      <div class="upload_photo_spinner text-center" style="display: none;">
            <div class="spinner-border" role="status">
                  <span class="visually-hidden">Loading...</span>
            </div>
      </div>
      <div class="jumbotron">
            <div class="container">
                  <center>
                        <h2 class="display-3"><?= $form->project ?> RMA</h2>
                  </center>
            </div>
      </div>
      <div class="control_btn_container  d-print-none">
            <div class="btn btn-dark mx-3 my-3 not-print" onclick="document.getElementById('upload').click();"><i class="fas fa-paperclip"></i></div>
            <button id="snap1" class="btn btn-info mx-3" onclick="document.getElementById('browse').click();"><i class="fa fa-camera"></i></button>
            <button id="save" type='submit' class="btn btn-success navbar-btn mx-3" value="Save" onclick="document.getElementById('update_btn').click();"><i class="fa fa-save"></i></button>
      </div>
      <div class="container">
            <?= form_open("forms/update/", "id=ajax-form"); ?>
            <input type='hidden' name='type' value='<?= $_GET['type'] ?>'>
            <input type='hidden' name='client' value='<?= $form->client ?>'>
            <input type='hidden' name='project' value='<?= $form->project ?>'>
            <input type='hidden' name='id' value='<?= $form->id ?>'>
            <input id="pictures_count" type='hidden' name='pictures' value=''>

            <div class="row">
                  <div class="input-group mb-2 col-md">
                        <div class="input-group-text">Received from: <?= $form->client ?></div>
                        <input type='hidden' class="form-control" name='client' value='<?= $form->client ?>'>
                  </div>
                  <div class="input-group mb-2 col-md">
                        <div class="input-group-text">Received by: <?= $form->user ?></div>
                        <input type='hidden' class="form-control" name='user' value='<?= $form->user ?>'>
                  </div>
                  <div class="input-group mb-2 col-md">
                        <div class="input-group-text">Date</div>
                        <input type='date' class="form-control" name='date' value="<?= $form->date ?>">
                  </div>
            </div>
            <div class="row">
                  <div class="input-group mb-2 col-md">
                        <div class="input-group-text">RMA Number</div>
                        <input type='text' class="form-control" name='number' value='<?= $form->number ?>' disabled>
                  </div>
                  <div class="input-group mb-2 col-md">
                        <div class="input-group-text">Serial Number</div>
                        <input type='text' class="form-control" name='serial' value='<?= $form->serial ?>'>
                  </div>
                  <div class="input-group mb-2 col-md">
                        <div class="input-group-text">Product Number</div>
                        <input type='text' class="form-control" name='product_num' value='<?= $form->product_num ?>'>
                  </div>
            </div>
            <hr>
            <h2>Receive</h2>
            <div class="row m-3">
                  <div class="col m-1 bg-light border border-primary">
                        <div class="form-check form-check-inline p-md-4">
                              <input class="form-check-input" type="radio" name="package" id="package1" value="1" <?= $form->package == 1 ? "checked" : "" ?>>
                              <label class="form-check-label" for="package1">Package picture</label>
                        </div>
                        <div class="form-check form-check-inline p-md-4">
                              <input class="form-check-input" type="radio" name="package" id="package2" value="0" <?= $form->package == 0 ? "checked" : "" ?>>
                              <label class="form-check-label" for="package2">No package</label>
                        </div>
                  </div>
                  <div class="col m-1 bg-light border border-primary ">
                        <div class="form-check form-check-inline p-md-4">
                              <input class="form-check-input" type="radio" name="accessories" id="accessories1" value="1" <?= $form->accessories == 1 ? "checked" : "" ?>>
                              <label class="form-check-label text-nowrap" for="accessories1">Accessories pictures</label>
                        </div>
                        <div class="form-check form-check-inline p-md-4">
                              <input class="form-check-input" type="radio" name="accessories" id="accessories2" value="0" <?= $form->accessories == 0 ? "checked" : "" ?>>
                              <label class="form-check-label" for="accessories2">No accessories</label>
                        </div>
                  </div>
                  <div class="col m-1 bg-light border border-primary">
                        <div class="form-check form-check-inline p-md-4">
                              <input class="form-check-input" type="radio" name="warranty" id="warranty1" value="2" <?= $form->warranty == 2 ? "checked" : "" ?>>
                              <label class="form-check-label" for="warranty1">With warranty</label>
                        </div>
                        <div class="form-check form-check-inline p-md-4">
                              <input class="form-check-input" type="radio" name="warranty" id="warranty2" value="1" <?= $form->warranty == 1 ? "checked" : "" ?>>
                              <label class="form-check-label" for="warranty2">No warranty</label>
                        </div>
                        <div class="form-check form-check-inline px-md-4">
                              <input class="form-check-input" type="radio" name="warranty" id="warranty3" value="0" <?= $form->warranty == 0 ? "checked" : "" ?>>
                              <label class="form-check-label text-nowrap" for="warranty3">Warranty not defined</label>
                        </div>
                  </div>
            </div>
            <div class="row m-3">
                  <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="<?= $form->receive_pictures ?>" id="receive_pictures" name="receive_pictures" <?= $form->receive_pictures != '' ? "checked" : "" ?>>
                        <input type="hidden" value="<?= $form->receive_pictures ?>" name="receive_pictures">
                        <label class="form-check-label" for="receive_pictures">
                              Device pictures (serials, sides, top, bottom). Verified by <b><?= $form->receive_pictures ?></b>
                        </label>
                  </div>
            </div>
            <div class="row m-3">
                  <div class="input-group mb-2 ">
                        <div class="input-group-text">Comments if damaged:</div>
                        <textarea type='text' rows="1" class="form-control not-print" name='receive_comments'><?= $form->receive_comments ?></textarea>
                        <div class="to-print">
                              <pre><?= $form->receive_comments ?></pre>
                        </div>
                  </div>
            </div>
            <div class="row m-3">
                  <div class="input-group mb-2 ">
                        <div class="input-group-text">Accessories list:</div>
                        <textarea type='text' rows="3" class="form-control not-print" name='accessories_list'><?= $form->accessories_list ?></textarea>
                        <div class="to-print">
                              <pre><?= $form->accessories_list ?></pre>
                        </div>
                  </div>
            </div>
            <hr>
            <div class="row">
                  <h2>Problem</h2>
                  <div class="input-group mb-2 ">
                        <div class="input-group-text">Customer failure description:</div>
                        <textarea type='text' rows="2" class="form-control not-print" name='problem'><?= $form->problem ?></textarea>
                        <div class="to-print">
                              <pre><?= $form->problem ?></pre>
                        </div>
                  </div>
            </div>
            <div class="row m-3">
                  <div class="form-check form-check-inline col-md-3">
                        <input class="form-check-input" type="radio" name="mrb" id="mrb1" value="1" <?= $form->mrb == 1 ? "checked" : "" ?>>
                        <label class="form-check-label" for="mrb1">Scan client MRB / RMA form</label>
                  </div>
                  <div class="form-check form-check-inline col-md-3">
                        <input class="form-check-input" type="radio" name="mrb" id="mrb2" value="0" <?= $form->mrb == 0 ? "checked" : "" ?>>
                        <label class="form-check-label" for="mrb2">No client MRB / RMA form</label>
                  </div>
            </div>
            <div class="row m-3">
                  <div class="input-group mb-2 ">
                        <div class="input-group-text">Other failure</div>
                        <textarea type='text' rows="3" class="form-control not-print" name='other_failure'><?= $form->other_failure ?></textarea>
                        <div class="to-print">
                              <pre><?= $form->other_failure ?></pre>
                        </div>
                  </div>
            </div>
            <hr>
            <div class="row m-3">
                  <h2>Repair</h2>
                  <div class="form-check form-check-inline col-md-3">
                        <input class="form-check-input" type="radio" name="failure_veriffication" id="failure_veriffication1" value="1" <?= $form->failure_veriffication == 1 ? "checked" : "" ?>>
                        <label class="form-check-label" for="failure_veriffication1">Failure verified</label>
                  </div>
                  <div class="form-check form-check-inline col-md-3">
                        <input class="form-check-input" type="radio" name="failure_veriffication" id="failure_veriffication2" value="0" <?= $form->failure_veriffication == 0 ? "checked" : "" ?>>
                        <label class="form-check-label" for="failure_veriffication2">Failure not verified</label>
                  </div>
            </div>
            <div class="row m-3">
                  <div class="input-group mb-2 ">
                        <div class="input-group-text">Repair information:</div>
                        <textarea type='text' rows="3" class="form-control not-print" name='repair'><?= $form->repair ?></textarea>
                        <div class="to-print">
                              <pre><?= $form->repair ?></pre>
                        </div>
                  </div>
            </div>
            <div class="row m-3">
                  <div class="input-group mb-2 ">
                        <div class="input-group-text">Required Parts for Repair</div>
                        <textarea type='text' rows="3" class="form-control not-print" name='parts'><?= $form->parts ?></textarea>
                        <div class="to-print">
                              <pre><?= $form->parts ?></pre>
                        </div>
                  </div>
            </div>
            <div class="row m-3">
                  <h3>Files</h3>
                  <?php include("application/views/storage/view_files.php") ?>
            </div>
            <input id="upload" type="file" name="files" data-url="/storage/save_file/<?= $form->id ?>?type=<?= $_GET['type'] ?>" hidden />
            <hr>
            <div class="row m-3">
                  <h2>QA</h2>
                  <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="qa_pics" name="qa_pics" <?= $form->qa_pics != '' ? "checked" : "" ?>>
                        <input type="hidden" value="<?= $form->qa_pics ?>" name="qa_pics">
                        <label class="form-check-label" for="qa_pics">
                              Get unit pictures before closing. Verified by <b><?= $form->qa_pics ?></b>
                        </label>
                  </div>
            </div>
            <div class="row m-3">
                  <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="closing_the_unit" name="closing_the_unit" <?= $form->closing_the_unit != '' ? "checked" : "" ?>>
                        <input type="hidden" value="<?= $form->closing_the_unit ?>" name="closing_the_unit">
                        <label class="form-check-label" for="closing_the_unit">
                              Close unit verify. Verified by <b><?= $form->closing_the_unit ?></b>
                        </label>
                  </div>
            </div>
            <div class="row m-3">
                  <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="full_unit_test" name="full_unit_test" <?= $form->full_unit_test != '' ? "checked" : "" ?>>
                        <input type="hidden" value="<?= $form->full_unit_test ?>" name="full_unit_test">
                        <label class="form-check-label" for="full_unit_test">
                              Full unit test. Verified by <b><?= $form->full_unit_test ?></b>
                        </label>
                  </div>
            </div>
            <hr>
            <div class="row m-3">
                  <h2>Packaging</h2>
                  <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="pack_unit_pics" name="pack_unit_pics" <?= $form->pack_unit_pics != '' ? "checked" : "" ?>>
                        <input type="hidden" value="<?= $form->pack_unit_pics ?>" name="pack_unit_pics">
                        <label class="form-check-label" for="pack_unit_pics">
                              Get unit pictures (serial, sides, top, bottom). Verified by <b><?= $form->pack_unit_pics ?></b>
                        </label>
                  </div>
            </div>
            <div class="row m-3">
                  <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="pack_accessories" name="pack_accessories" <?= $form->pack_accessories != '' ? "checked" : "" ?>>
                        <input type="hidden" value="<?= $form->pack_accessories ?>" name="pack_accessories">
                        <label class="form-check-label" for="pack_accessories">
                              Check unit and accessories packing (acording arriving list). Verified by <b><?= $form->pack_accessories ?></b>
                        </label>
                  </div>
            </div>
            <div class="row m-3">
                  <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="pack_accessories_pics" name="pack_accessories_pics" <?= $form->pack_accessories_pics != '' ? "checked" : "" ?>>
                        <input type="hidden" value="<?= $form->pack_accessories_pics ?>" name="pack_accessories_pics">
                        <label class="form-check-label" for="pack_accessories_pics">
                              Get packing picture. Verified by <b><?= $form->pack_accessories_pics ?></b>
                        </label>
                  </div>
            </div>
            <div class="row m-3">
                  <div class="input-group mb-2 col-lg-6">
                        <div class="input-group-text">Final Documentation Check:</div>
                        <select class="form-select" name='final_user'>
                              <option value='NA' $selected>Select</option>
                              <?php
                              foreach ($this->users as $user) {
                                    $selected = "";
                                    if ($user['name'] == $form->final_user) {
                                          $selected = "selected";
                                    }
                                    echo "<option value='{$user['name']}' $selected >{$user['name']}</option>";
                              }
                              ?>
                        </select>
                  </div>
            </div>
            <input id="update_btn" type='submit' style="display:none;" class="btn btn-info my-5 print-hide" name='submit' value='Update'>
            <?= form_close(); ?>
      </div>
      <div style="page-break-after:always"></div>
      <div id="photo-stock" class="container">
            <center>
                  <h2>Pictures</h2>
            </center>
            <div id="photo-messages" class='alert hidden' role='alert'></div>
            <?php
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
      $(document).ready(function() {
            $("#pictures_count").val(photoCount);
      });
      document.title = 'RMA <?= $form->number ?>';
</script>