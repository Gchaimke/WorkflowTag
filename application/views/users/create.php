<main role="main">
      <div class="jumbotron">
            <div class="container">
                  <center>
                        <h2 class="display-3">Create new user.</h2>
                  </center>
            </div>
      </div>
      <div class="container">
      <b><?php if (isset($response)) echo $response; ?></b>
      <form method='post' action='<?php echo base_url('/users/create'); ?>'>

            <table>
                  <tr>
                        <td>Role</td>
                        <td><input type='text' name='txt_role'></td>
                  </tr>
                  <tr>
                        <td>Username</td>
                        <td><input type='text' name='txt_name'></td>
                  </tr>
                  <tr>
                        <td>Password</td>
                        <td><input type='text' name='txt_pass'></td>
                  </tr>
                  <tr>
                        <td>&nbsp;</td>
                        <td><input type='submit' name='submit' value='Submit'></td>
                  </tr>
            </table>
      </form>
      </div>
      
</main>