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
<div id="form-messages" class='alert hidden' data-url="/forms?type=rma&client=<?php echo $client . "&project=" . $project ?>" role='alert'></div>
<nav id='nav_main_category_data' data-url="/forms?type=rma&client=<?php echo $client . "&project=" . $project ?>" data-url-name="<?= $client . " " . $project ?> RMA" hidden></nav>
<main role="main">
      <div class="jumbotron">
            <div class="container">
                  <center>
                        <h2 class="display-3">New <?php echo $project ?> RMA</h2>
                  </center>
            </div>
      </div>
      <div class="container">
            <?php echo form_open("forms/new/", "id=ajax-form"); ?>
            <input type='hidden' name='type' value='<?php echo $_GET['type'] ?>'>
            <input type='hidden' name='project' value='<?php echo $project ?>'>
            <div class="mx-auto text-center p-4 col-12 ">
                  <div class="form-row">
                        <div class="input-group mb-2 col-lg-4">
                              <div class="input-group-prepend">
                                    <div class="input-group-text">Client Name: <?php echo $client ?></div>
                              </div>
                              <input type='<?php echo $fuild_type ?>' class="form-control" name='client' value='<?php echo $client ?>'>
                        </div>
                        <div class="input-group mb-2 col-lg-4">
                              <div class="input-group-prepend">
                                    <div class="input-group-text">Received by: <?= $assembler ?></div>
                              </div>
                              <input type='<?php echo $fuild_type ?>' class="form-control" name='user' value='<?= $assembler ?>'>
                        </div>
                        <div class="input-group mb-2 col-lg-3">
                              <div class="input-group-prepend">
                                    <div class="input-group-text">Date</div>
                              </div>
                              <input type='date' class="form-control" name='date' value="<?php echo date("Y-m-d"); ?>">
                        </div>
                  </div>
                  <hr>
                  <div class="form-row">
                        <div class="input-group mb-2 col-lg-4">
                              <div class="input-group-prepend">
                                    <div class="input-group-text">RMA Number</div>
                              </div>
                              <input type='number' class="form-control" name='number' required>
                        </div>
                        <div class="input-group mb-2 col-lg-4">
                              <div class="input-group-prepend">
                                    <div class="input-group-text">Serial Number</div>
                              </div>
                              <input type='text' class="form-control" name='serial'>
                        </div>
                        <div class="input-group mb-2 col-lg-4">
                              <div class="input-group-prepend">
                                    <div class="input-group-text">Product Number</div>
                              </div>
                              <input type='text' class="form-control" name='product_num' value=''>
                        </div>
                  </div>
                  <hr>
                  <h2>Receive</h2>
                  <div class="form-row">
                        <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="package" id="package1" value="1">
                              <label class="form-check-label" for="package1">Package picture</label>
                        </div>
                        <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="package" id="package2" value="0">
                              <label class="form-check-label" for="package2">No package</label>
                        </div>
                  </div>
                  <div class="form-row">
                        <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="accessories" id="accessories1" value="1">
                              <label class="form-check-label" for="accessories1">Accessories pictures</label>
                        </div>
                        <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="accessories" id="accessories2" value="0">
                              <label class="form-check-label" for="accessories2">No accessories</label>
                        </div>
                  </div>
                  <div class="form-row">
                        <div class="form-check">
                              <input class="form-check-input" type="checkbox" value="" id="recive_pictures" name="recive_pictures">
                              <label class="form-check-label" for="recive_pictures">
                                    Device pictures (serials, sides, top, bottom)
                              </label>
                        </div>
                  </div>
                  <div class="form-row">
                        <div class="input-group mb-2 col-12">
                              <div class="input-group-prepend">
                                    <div class="input-group-text">Comments if damaged:</div>
                              </div>
                              <textarea type='text' rows="1" class="form-control" name='recive_comments'></textarea>
                        </div>
                  </div>

                  <div class="form-row">
                        <div class="input-group mb-2 col-12">
                              <div class="input-group-prepend">
                                    <div class="input-group-text">Accessories list:</div>
                              </div>
                              <textarea type='text' rows="6" class="form-control" name='accessories_list'></textarea>
                        </div>
                  </div>
                  <hr>
                  <h2>Problem</h2>
                  <div class="form-row">
                        <div class="input-group mb-2 col-12">
                              <div class="input-group-prepend">
                                    <div class="input-group-text">Customer failure description:</div>
                              </div>
                              <textarea type='text' rows="2" class="form-control" name='problem'></textarea>
                        </div>
                  </div>
                  <div class="form-row">
                        <div class="form-check">
                              <input class="form-check-input" type="checkbox" value="" id="mrb" name="mrb">
                              <label class="form-check-label" for="mrb">
                                    Scan client MRB / RMA form
                              </label>
                        </div>
                  </div>
                  <div class="form-row">
                        <div class="form-check">
                              <input class="form-check-input" type="checkbox" value="" id="failure_veriffication" name="failure_veriffication">
                              <label class="form-check-label" for="failure_veriffication">
                                    Failure verification
                              </label>
                        </div>
                  </div>
                  <div class="form-row">
                        <div class="form-check">
                              <input class="form-check-input" type="checkbox" value="" id="nff" name="nff">
                              <label class="form-check-label" for="nff">
                                    NFF
                              </label>
                        </div>
                  </div>
                  <div class="form-row">
                        <div class="input-group mb-2 col-12">
                              <div class="input-group-prepend">
                                    <div class="input-group-text">Other failure</div>
                              </div>
                              <textarea type='text' rows="1" class="form-control" name='other_failure'></textarea>
                        </div>
                  </div>
                  <hr>
                  <h2>Repair</h2>
                  <div class="form-row">
                        <div class="input-group mb-2 col-12">
                              <div class="input-group-prepend">
                                    <div class="input-group-text">Repair information:</div>
                              </div>
                              <textarea type='text' rows="3" class="form-control" name='repair'></textarea>
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
                  <div class="form-row">
                        <div class="form-check">
                              <input class="form-check-input" type="checkbox" value="" id="serial_updates" name="serial_updates">
                              <label class="form-check-label" for="serial_updates">
                                    Serial updates
                              </label>
                        </div>
                  </div>
                  <hr>
                  <h2>QA</h2>
                  <div class="form-row">
                        <div class="form-check">
                              <input class="form-check-input" type="checkbox" value="" id="qa_pics" name="qa_pics">
                              <label class="form-check-label" for="qa_pics">
                                    Unit pics before closing
                              </label>
                        </div>
                  </div>
                  <div class="form-row">
                        <div class="form-check">
                              <input class="form-check-input" type="checkbox" value="" id="closing_the_unit" name="closing_the_unit">
                              <label class="form-check-label" for="closing_the_unit">
                                    Closing the unit
                              </label>
                        </div>
                  </div>
                  <div class="form-row">
                        <div class="form-check">
                              <input class="form-check-input" type="checkbox" value="" id="full_unit_test" name="full_unit_test">
                              <label class="form-check-label" for="full_unit_test">
                                    Full unit test
                              </label>
                        </div>
                  </div>
                  <hr>
                  <h2>Packaging</h2>
                  <div class="form-row">
                        <div class="form-check">
                              <input class="form-check-input" type="checkbox" value="" id="pack_unit_pics" name="pack_unit_pics">
                              <label class="form-check-label" for="pack_unit_pics">
                                    Unit picture (serial, sides, top, bottom)
                              </label>
                        </div>
                  </div>
                  <div class="form-row">
                        <div class="form-check">
                              <input class="form-check-input" type="checkbox" value="" id="pack_accessories" name="pack_accessories">
                              <label class="form-check-label" for="pack_accessories">
                                    Unit and accessories packing (acording arriving list)
                              </label>
                        </div>
                  </div>
                  <div class="form-row">
                        <div class="form-check">
                              <input class="form-check-input" type="checkbox" value="" id="pack_accessories_pics" name="pack_accessories_pics">
                              <label class="form-check-label" for="pack_accessories_pics">
                                    Packing picture
                              </label>
                        </div>
                  </div>
                  <div class="input-group mb-2 col-lg-4">
                        <div class="input-group-prepend">
                              <div class="input-group-text">Final Documentation Check:</div>
                        </div>
                        <input type='text' class="form-control" name='final_user' value='<?= $assembler ?>'>
                  </div>
                  <input type='submit' class="btn btn-info my-5" name='submit' value='Submit'>
            </div>
            <?php echo form_close(); ?>
      </div>
</main>
<script>
      var assembler = '<?= $assembler ?>';
      $("input:checkbox").click(function(e) {
            if ($(event.target).is(":checked")) {
                  $(this).val(assembler);
            }else{
                  $(this).val("");
            }
      });
</script>