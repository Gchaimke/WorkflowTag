    <footer class="container">
      <p>
        <center>&copy; WorkflowTag 2020</center>
      </p>
    </footer>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="<?php echo base_url('assets/js/bootstrap.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/all.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/' . pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME) . '.js'); ?>"></script>
    <?php
    // Get the content that is in the buffer and put it in your file //
    if (isset($_GET['sn'])) {
      echo '<script src="' . base_url("assets/js/camera.js") . '"></script>';
    }
    ?>

    </body>

    </html>