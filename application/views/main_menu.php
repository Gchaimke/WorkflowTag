<?php
$user_language = $this->config->item('language');
if (isset($this->session->userdata['logged_in'])) {
  $id = ($this->session->userdata['logged_in']['id']);
  $username = ($this->session->userdata['logged_in']['name']);
  $user_view_name = ($this->session->userdata['logged_in']['view_name']);
  $role = ($this->session->userdata['logged_in']['role']);
} else {
  exit();
}

if (isset($_GET['project'])) {
  $project = isset($_GET['project']) ? $_GET['project'] : $project;
}

?>
<nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-dark main-menu">
  <a class="navbar-brand" href="/"><img src="/assets/img/workflow_tag_logo.png" width="40px"></a>
  <div class="navbar-nav mr-auto">
    <a class="nav-item btn btn-outline-success p-1 mx-1 mt-1 mt-lg-0 text-white" href="/"><?= lang('projects') ?></a>
  </div>
  <?php
  if (isset($project) && $project != '') {
    echo "<a class='nav-item btn btn-outline-warning p-1 mx-1 mt-1 mt-lg-0 project' href='/production/checklists?client={$_GET['client']}&project=$project'>$project </a>";
  } ?>
  <button class="search nav-item btn btn-outline-success p-1 mx-1 px-3 mt-1 mt-lg-0 text-white"><i class="search_icon fas fa-search"></i></button>

  <a id="nav_main_category" class="nav-item btn btn-outline-warning p-1 mx-1 mt-1 mt-lg-0" href="/" hidden></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarDefault" aria-controls="navbarDefault" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarDefault">
    <div class="navbar-nav mr-auto">
    </div>
    <ul class="navbar-nav">
      <?php if ($role != 'Assembler') { ?>
        <li class="nav-item dropdown nav-item mt-3 ml-md-3 mt-lg-0">
          <a class="nav-item dropdown-toggle btn btn-outline-warning text-white" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?= lang('forms') ?>
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <?php if (isset($project) && $project != '') { ?>
              <b class="ml-2"><?= $project ?></b><br>
              <a class="nav-item btn btn-outline-warning p-1 mx-md-2 px-2 mt-3 mt-lg-0 text-black" href="/forms?type=rma&client=<?= $_GET['client'] . "&project=" . $project ?>">RMA</a>
              <a class="nav-item btn btn-outline-danger p-1 mx-md-2 px-3 mt-3 mt-lg-0 text-black" href="/forms?type=qc&client=<?= $_GET['client'] . "&project=" . $project ?>">QC</a>
            <?php } ?>
            <hr>
            <b class="ml-2">All</b><br>
            <a class="dropdown-item" href="/forms?type=rma">RMA</a>
            <a class="dropdown-item" href="/forms?type=qc">QC</a>
            <a class="dropdown-item" href="/production/notes"><?= lang('notes') ?></a>
          </div>
        </li>
      <?php } ?>

      <?php if ($role == 'Assembler') { ?>
        <div class="navbar-nav mr-md-auto">
          <?php if (isset($project) && $project != '') { ?>
            <a class="nav-item btn btn-outline-warning p-1 mx-1 mt-1 mt-lg-0 text-white" href="/forms?type=rma&client=<?= $_GET['client'] . "&project=" . $project ?>">RMA</a>
          <?php } ?>
        </div>
      <?php } ?>
      <?php if ($role == 'Admin') { ?>
        <li class="nav-item dropdown nav-item mt-3 ml-md-3 mt-lg-0">
          <a class="nav-item dropdown-toggle btn btn-outline-info text-white" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?= lang('Admin') ?>
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <b class="ml-2"><?= lang('system') ?></b>
            <a class="dropdown-item" href="/clients"><?= lang('clients') ?></a>
            <a class="dropdown-item" href="/users"><?= lang('users') ?></a>
            <hr>
            <b class="ml-2"><?= lang('manage') ?></b>
            <a class="dropdown-item" href="/admin/mange_uploads"><?= lang('uploads') ?></a>
            <a class="dropdown-item" href="/admin/manage_trash?type=checklist"><?= lang('trash') ?></a>
            <a class="dropdown-item" href="/admin/view_log"><?= lang('sys_log') ?></a>
            <a class="dropdown-item" href="/admin/settings"><?= lang('settings') ?></a>
          </div>
        </li>
      <?php } ?>

      <li class="nav-item dropdown mx-1 mx-lg-5 mt-3 mt-lg-0">
        <a class="nav-item dropdown-toggle btn btn-outline-secondary text-white" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <?php
          $name = isset($user_view_name) ? $user_view_name : "";
          printf(lang('menu_hi'), $name)
          ?>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="/users/edit/<?php echo $id ?>"><?= lang('menu_update_my_data') ?></a>
          <a class="dropdown-item" href="/users/logout"><?= lang('menu_logout') ?></a>
        </div>
      </li>
    </ul>
  </div>
</nav>

<div class="search_form bg-dark" style="display: none; ">
  <form id="form">
    <div class="input-group mb-3 col-md-4 m-auto">
      <input id='inputSearch' type="text" class="form-control" placeholder="<?= lang('search_placeholder') ?>" aria-label="Search for serial number" aria-describedby="basic-addon2" autofocus>
      <div class="input-group-append">
        <button class="btn btn-secondary" type="button" onclick="serialSearch()"><?= lang('search') ?></button>
      </div>
    </div>
    <div id='searchResult' class="text-white">
      <div class='col-md-8 m-auto overflow-auto pt-3' style='height:85vh'>
        <table class="table" id='serach_rows'>
        </table>
      </div>
    </div>
  </form>
</div>
<script>
  $('.search').on('click', function() {
    $('.search_form').toggle(400);
    if ($('.search_icon').hasClass('fa-search')) {
      $('.search_icon').removeClass('fa-search');
      $('.search_icon').addClass('fa-times');
      $('#inputSearch').focus();
    } else {
      $('.search_icon').removeClass('fa-times');
      $('.search_icon').addClass('fa-search');
    }
  })

  function serialSearch() {
    var search = $("#inputSearch").val();
    if (search.length >= 3) {
      $.post("/search", {
        search: search
      }).done(function(e) {
        $('#serach_rows').empty();
        var head = '<thead class="thead-dark"> <tr><th class="text-left">Serial Number</th><th>Project</th><th>Type</th><th>Action</th></tr></thead>';
        if (e != '') {
          $('#serach_rows').append(head);
          $('#serach_rows').append(e);
        } else {
          var html = "<h2 class='text-white'>Not found</h2>";
          $('#serach_rows').append(html);
        }
      });
    } else {
      $('#serach_rows').empty();
      $('#serach_rows').append("<h2 class='text-white'>Search must be munimum 3 simbols</h2>")
    }
  }

  $(window).keyup(function(event) {
    if (event.which == 13 && $('#inputSearch').is(':focus')) { //enter
      event.preventDefault();
      setTimeout(serialSearch(), 1000);
    }
    if ($('#inputSearch').is(':focus')) { //enter
      setTimeout(serialSearch(), 1000);
    }
  });
</script>