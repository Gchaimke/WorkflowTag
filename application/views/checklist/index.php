<main role="main">

      <div class="jumbotron">
            <div class="container">
                  <center>
                        <h2 class="display-3">Manage checklists</h2>
                  </center>
            </div>
      </div>
      <div class="container">
      <b><?php if (isset($response)) echo $response; ?></b>
      <form method='post' action='<?php echo base_url('/checklist/create'); ?>'>

            <table>
                  <tr>
                        <td>Project</td>
                        <td><input type='text' name='pr'></td>
                  </tr>
                  <tr>
                        <td>Serial Number</td>
                        <td><input type='text' name='sn'></td>
                  </tr>
                  <tr>
                        <td>&nbsp;</td>
                        <td><input type='submit' name='submit' value='Submit'></td>
                  </tr>
            </table>
      </form>
      </div>
</main>