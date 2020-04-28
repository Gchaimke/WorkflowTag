<main role="main" class="container">
      <div class="video-frame container-sm">
            <div class="controls">
                  <button id="select_camera" class="btn btn-success">Select camera</button>
                  <select id="select">
                        <option></option>
                  </select>
                  <button id="close_camera" class="btn btn-danger">Close camera</button>
            </div>
            <video id="video" width="100%" autoplay playsinline></video>
      </div>
      <div id="workTable"></div>
      <div id="photo-stock" class="container-sm">
            <canvas id="canvas" style="display:none;" width="1920" height="1080"></canvas>

            <?php
            $working_dir = '/Production/' . $_GET['pr'] . '/' . $_GET['sn'] . '/';
            echo '<script>var pictueCount=0;</script>';
            if (file_exists(".$working_dir")) {
                  if ($handle = opendir(".$working_dir")) {
                        while (false !== ($entry = readdir($handle))) {
                              if ($entry != "." && $entry != "..") {
                                    echo '<span id="' . pathinfo($entry, PATHINFO_FILENAME) . '" onclick="delPhoto(this.id)" class="btn btn-danger delete-photo">delete</span><img id="' . pathinfo($entry, PATHINFO_FILENAME) . '" src="' . $working_dir . $entry . '" class="respondCanvas" >';
                                    echo '<script>pictueCount++</script>';
                              }
                        }
                        closedir($handle);
                  }
            }

            ?>
      </div>
</main>