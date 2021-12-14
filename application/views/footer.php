    <footer class="container">
      <p>
        <center>Workflow Tag&copy; <?php echo date('Y'); ?></center>
      </p>
    </footer>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="<?php echo base_url('assets/js/bootstrap/bootstrap.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/all.js?' . filemtime('assets/js/all.js')); ?>"></script>
    <?php
    include_once("left_menu.php");
    if (isset($js_to_load)) {
      if (is_array($js_to_load)) {
        foreach ($js_to_load as $row) {
          echo  "<script type='text/javascript' src='" . base_url("assets/js/$row") . "'></script>";
        }
      } else {
        echo  "<script type='text/javascript' src='" . base_url("assets/js/$js_to_load") . "'></script>";
      }
    }
    ?>

    </body>

    </html>