<link href="<?php echo base_url('assets/css/checklist_create.css'); ?>" rel="stylesheet">
<nav class="navbar checklist navbar-light fixed-top bg-light">
    <b id="project" class="navbar-text" href="#">Project: </b>
    <b id="sn" class="navbar-text" href="#">SN: </b>
    <b id="date" class="navbar-text" href="#">Date: </b>
    <ul class="nav navbar-nav navbar-right">
        <li lass="nav-item">
            <button id="snap" class="btn btn-info">Snap Photo</button>
            <button id="save" class="btn btn-success navbar-btn">Save</button>
        </li>
    </ul>
    <div class="progress fixed-bottom">
        <div id="progress-bar" class="progress-bar progress-bar-striped bg-warning" role="progressbar" style="width: 0" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
</nav>
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

            if (isset($_GET['sn'])) {
                  $working_dir = '/Production/' . $_GET['pr'] . '/' . $_GET['sn'] . '/';
                  echo '<script>var pictueCount=0;</script>';
                  if (file_exists(".$working_dir")) {
                        if ($handle = opendir(".$working_dir")) {
                              while (false !== ($entry = readdir($handle))) {
                                    if ($entry != "." && $entry != ".." && pathinfo($entry, PATHINFO_EXTENSION )== 'png') {
                                          echo '<span id="' . pathinfo($entry, PATHINFO_FILENAME) . '" onclick="delPhoto(this.id)" class="btn btn-danger delete-photo">delete</span><img id="' . pathinfo($entry, PATHINFO_FILENAME) . '" src="' . $working_dir . $entry . '" class="respondCanvas" >';
                                          echo '<script>pictueCount++</script>';
                                    }
                              }
                              closedir($handle);
                        }
                  }
            }
            ?>
      </div>
</main>