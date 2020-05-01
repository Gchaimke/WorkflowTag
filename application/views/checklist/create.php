<main role="main">

      <div class="jumbotron">
            <div class="container">
                  <center>
                        <h2 class="display-3">Create checklist</h2>
                  </center>
            </div>
      </div>
      <div class="container">
            <center>
                  <tr>
                        <?php
                        if (isset($response)) {
                              echo '<div class="alert alert-success" role="alert">';
                              echo $response . ' </div>';
                        }
                        ?>
                  </tr>
                  <form method='post' action='<?php echo base_url('/checklist/create'); ?>'>
                        <table>
                              <tr>
                                    <td><select class="form-control" name='project'>
                                                <option>Flex2</option>
                                                <option>Lap3</option>
                                                <option>Flex Leg</option>
                                          </select></td>
                              </tr>
                              <tr>
                                    <td><input class="form-control" type='text' name='serial' placeholder="Serial Number">
                                          <input type='hidden' name='data' value="">
                                          <input type='hidden' name='progress' value="0">
                                    </td>
                              </tr>
                              <tr>
                                    <td><input type='text' class="form-control" name='date' value="<?php echo date("Y-m-d"); ?>"></td>
                              </tr>
                              <tr>
                                    <td><input type='submit' class="btn btn-info btn-block" name='submit' value='Submit'></td>
                              </tr>
                        </table>
                  </form>
            </center>
      </div>
</main>