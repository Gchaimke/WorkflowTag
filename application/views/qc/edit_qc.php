<?php
if (isset($message_display)) {
      echo "<div class='alert alert-danger' role='alert'>";
      echo $message_display . '</div>';
}
if (validation_errors()) {
      echo "<div class='alert alert-danger' role='alert'>" . validation_errors() . "</div>";
}

if (!isset($qc_form)) {
      header("location: /qc/");
} else {
      $data = $qc_form[0];
}

?>
<link rel="stylesheet" href="<?php echo base_url('assets/css/print.css?' . filemtime('assets/css/print.css')); ?>">
<style>
      .control_btn_container {
            position: fixed;
            right: 0;
            top: 50%;
            display: grid;
            z-index: 1;
      }

      button#save {
            margin-top: 70px;
      }

      @media (max-width: 991.98px) {
            .control_btn_container {
                  position: fixed;
                  top: auto;
                  bottom: 0;
                  display: block;
                  z-index: 1;
                  background: #ebebeb;
                  width: 100%;
                  text-align: center;
            }

            .control_btn_container>button {
                  width: 100px;
            }

            button#save {
                  margin-top: 0;

            }

            .jumbotron {
                  padding: 5rem 0rem;
                  padding-bottom: 1rem;
            }
      }

      @media print {
            .jumbotron {
                  position: absolute;
                  top: 0;
                  left: 0;
                  right: 0;
                  background-color: transparent;
            }

            form#ajax-form {
                  margin-top: 100px;
            }

            .form-row {
                  margin-bottom: 1em;
            }

            .input-group-text {
                  font-weight: bold;
            }

            #save {
                  display: none;
            }
      }
</style>
<?php echo "<img class='img-thumbnail checklist-logo' src='/assets/img/logo.png'>" ?>
<div id="form-messages" class='alert hidden' data-url="/qc/edit_qc/<?php echo $data['id'] ?>" role='alert'></div>
<nav id='nav_main_category_data' data-url="/qc/view_project_qc/<?php echo $data['client'] . "/" . $data['project'] ?>" data-url-name="All <?= $data['project'] ?> qc " hidden></nav>
<main role="main">
      <div class="jumbotron">
            <div class="container">
                  <center>
                        <h2 class="display-3"><?php echo $data['project'] ?> qc #<?php echo $data['number'] ?></h2>
                  </center>
            </div>
      </div>
      <div class="control_btn_container">
            <button id="snap1" class="btn btn-info mx-3" onclick="document.getElementById('browse').click();"><i class="fa fa-camera"></i></button>
            <button id="save" type='submit' class="btn btn-success navbar-btn mx-3" value="Save" onclick="document.getElementById('update_btn').click();"><i class="fa fa-save"></i></button>
      </div>
      <div class="container">

            <?php echo form_open("qc/update_qc/", "id=ajax-form"); ?>
            <input type='hidden' name='client' value='<?php echo $data['client'] ?>'>
            <input type='hidden' name='project' value='<?php echo $data['project'] ?>'>
            <input type='hidden' name='id' value='<?php echo $data['id'] ?>'>
            <input id="picrures_count" type='hidden' name='pictures' value=''>
            <div class="mx-auto text-center p-4 col-12 ">
                  <div class="form-row">
                        <div class="input-group mb-2 col-lg-2">
                              <div class="input-group-prepend">
                                    <div class="input-group-text">qc #</div>
                              </div>
                              <input type='text' class="form-control" name='number' value='<?php echo $data['number'] ?>' disabled>
                        </div>
                        <div class="input-group mb-2 col-lg-3">
                              <div class="input-group-prepend">
                                    <div class="input-group-text">SN</div>
                              </div>
                              <input type='text' class="form-control" name='serial' value='<?php echo $data['serial'] ?>'>
                        </div>
                        <div class="input-group mb-2 col-lg-4">
                              <div class="input-group-prepend">
                                    <div class="input-group-text">Product Number</div>
                              </div>
                              <input type='text' class="form-control" name='product_num' value='<?php echo $data['product_num'] ?>'>
                        </div>
                        <div class="input-group mb-2 col-lg-3">
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
                                    <div class="input-group-text">qc From</div>
                              </div>
                              <input type='text' class="form-control" name='client' value='<?php echo $data['client'] ?>'>
                        </div>
                        <div class="input-group mb-2 col-md-6">
                              <div class="input-group-prepend">
                                    <div class="input-group-text">Checked by</div>
                              </div>
                              <input type='text' class="form-control" name='user' value='<?php echo $data['user'] ?>'>
                        </div>
                  </div>

                  <input id="update_btn" type='submit' style="display:none;" class="btn btn-info my-5 print-hide" name='submit' value='Update'>
            </div>
            <?php echo form_close(); ?>
      </div>
      <div id="photo-stock" class="container">
            <center>
                  <h2>System Photos</h2>
            </center>
            <div id="photo-messages" class='alert hidden' role='alert'></div>
            <?php
            $working_dir = 'Uploads/' . $data['client'] . '/' . $data['project'] . '/qc/' . $data['number'] . '/';
            echo "<script>
                  var photoCount=0;
                  var id='" . $data['id'] . "';
                  var project='" . $data['project'] . "';
                  var serial='" . $data['number'] . "';
                  var user ='" . $data['user'] . "';
                  var client='" . $data['client'] . "';
                  var working_dir='$working_dir';
            </script>";  //pass PHP data to JS
            if (file_exists("./$working_dir")) {
                  if ($handle = opendir("./$working_dir")) {
                        while (false !== ($entry = readdir($handle))) {
                              if ($entry != "." && $entry != ".." && pathinfo($entry, PATHINFO_EXTENSION) == 'jpeg' && PATHINFO_FILENAME != '') {
                                    echo '<span id="' . pathinfo($entry, PATHINFO_FILENAME) .
                                          '" onclick="delPhoto(this.id)" class="btn btn-danger delete-photo fa fa-trash"> ' .
                                          pathinfo($entry, PATHINFO_FILENAME) . '</span><img id="' .
                                          pathinfo($entry, PATHINFO_FILENAME) . '" src="/' . $working_dir . $entry .
                                          '" class="respondCanvas" >';
                                    echo '<script>photoCount++</script>';
                              }
                        }
                        closedir($handle);
                  }
            }
            ?>
      </div>
      <input id="browse" style="display:none;" type="file" onchange="snapPhoto()" multiple>
      <div id="preview"></div>
</main>
<script>
      $(document).ready(function() {
            $("#picrures_count").val(photoCount);
      });
      document.title = 'qc <?php echo $data['number'] ?>';
</script>