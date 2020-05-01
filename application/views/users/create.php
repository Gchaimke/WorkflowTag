<main role="main">
      <div class="jumbotron">
            <div class="container">
                  <center>
                        <h2 class="display-3">Create new user.</h2>
                  </center>
            </div>
      </div>
      <div class="container">
            <center>
                        <?php 
                        if (isset($response)) {
                              echo '<div class="alert alert-success" role="alert">';
                              echo $response.' </div>';
                        }
                         ?>
                 
                  <form method='post' action='<?php echo base_url('/users/create'); ?>'>

                        <table>
                              <tr>
                                    <td><select class="form-control" name='txt_role'>
                                                <option>Assembler</option>
                                                <option>QC</option>
                                                <option>Admin</option>
                                          </select></td>
                              </tr>
                              <tr>
                                    <td><input class="form-control" type="text" name='txt_name' placeholder="Username"></td>
                              </tr>
                              <tr>
                                    <td><input class="form-control" type="text" name='txt_pass' placeholder="Password"></td>
                              </tr>
                              <tr>
                                    <td><input type='submit' class="btn btn-info btn-block" name='submit' value='Submit'></td>
                              </tr>
                        </table>
                  </form>
            </center>
      </div>

</main>