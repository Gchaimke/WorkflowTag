<?php
if (isset($this->session->userdata['logged_in'])) {
  $id = ($this->session->userdata['logged_in']['id']);
  $username = ($this->session->userdata['logged_in']['name']);
  $user_view_name = ($this->session->userdata['logged_in']['view_name']);
  $role = ($this->session->userdata['logged_in']['role']);
} else {
  exit();
}

if (isset($client) && !is_string($client)) {
  $client = $client['name'];
}
?>
<nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-dark main-menu">
  <a class="navbar-brand" href="/"><img src="/assets/img/workflow_tag_logo.png" width="40px"></a>
  <div class="navbar-nav mr-auto">
    <a class="nav-item btn btn-outline-success p-1 mx-1 mt-1 mt-lg-0 text-white" href="/">Projects</a>
  </div>
  <?php
  if (isset($project) && $project != '') {
    echo '<a class="nav-item btn btn-outline-warning p-1 mx-1 mt-1 mt-lg-0" href="/production/checklists/' . $project . '">' . $client . ' ' . $project . '</a>';
  } ?>
  <button class="search nav-item btn btn-outline-success p-1 mx-1 px-3 mt-1 mt-lg-0 text-white fa fa-search"></button>

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
            QC
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <?php if (isset($project) && $project != '') { ?>
              <b class="ml-2"><?= $project ?></b><br>
              <a class="nav-item btn btn-outline-warning p-1 mx-md-2 px-2 mt-3 mt-lg-0 text-black" href="/rma/view_project_rma/<?= $client . "/" . $project ?>">RMA</a>
              <a class="nav-item btn btn-outline-danger p-1 mx-md-2 px-3 mt-3 mt-lg-0 text-black" href="/qc/view_project_qc/<?= $client . "/" . $project ?>">QC</a>
            <?php } ?>
            <hr>
            <b class="ml-2">All</b><br>
            <a class="dropdown-item" href="/rma/view_project_rma">RMA</a>
            <a class="dropdown-item" href="/qc/view_project_qc">QC</a>
            <a class="dropdown-item" href="/production/notes">Notes</a>
          </div>
        </li>
      <?php } ?>
      <?php if ($role == 'Admin') { ?>
        <li class="nav-item dropdown nav-item mt-3 ml-md-3 mt-lg-0">
          <a class="nav-item dropdown-toggle btn btn-outline-info text-white" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Admin
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <b class="ml-2">System</b>
            <a class="dropdown-item" href="/clients">Clients</a>
            <a class="dropdown-item" href="/templates">Templates</a>
            <a class="dropdown-item" href="/users">Users</a>
            <hr>
            <b class="ml-2">Manage</b>
            <a class="dropdown-item" href="/admin/mange_uploads">Uploads</a>
            <a class="dropdown-item" href="/admin/manage_trash?kind=checklist">Trash</a>
            <a class="dropdown-item" href="/admin/view_log">System Log</a>
            <a class="dropdown-item" href="/admin/settings">Settings</a>
          </div>
        </li>
      <?php } ?>

      <li class="nav-item dropdown mx-1 mx-lg-5 mt-3 mt-lg-0">
        <a class="nav-item dropdown-toggle btn btn-outline-secondary text-white" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Hi <?php echo $user_view_name; ?>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="/users/edit/<?php echo $id ?>">Edit <?php echo $username ?> profile</a>
          <a class="dropdown-item" href="/users/logout">Logout</a>
        </div>
      </li>
    </ul>
  </div>
</nav>

<div class="search_form bg-dark" style="display: none; ">
  <form id="form">
    <div class="input-group mb-3 col-md-4 m-auto">
      <input id='inputSearch' type="text" class="form-control" placeholder="Search for serial number" aria-label="Search for serial number" aria-describedby="basic-addon2" autofocus>
      <div class="input-group-append">
        <button class="btn btn-secondary" type="button" onclick="serialSearch()">Search</button>
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
    if ($('.search').hasClass('fa-search')) {
      $('.search').removeClass('fa-search btn-outline-success');
      $('.search').addClass('fa-times btn-outline-danger');
      $('#inputSearch').focus();
    } else {
      $('.search').removeClass('fa-times btn-outline-danger');
      $('.search').addClass('fa-search btn-outline-success');
    }
  })

  function serialSearch() {
    var search = $("#inputSearch").val();
    if (search.length >= 3) {
      $.post("/search", {
        search: search
      }).done(function(e) {
        $('#serach_rows').empty();
        var head = '<thead class="thead-dark"> <tr><th>Serial Number</th><th>Project</th><th>Action</th></tr></thead>';
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
      setTimeout(serialSearch(),1000);
    }
    if ($('#inputSearch').is(':focus')) { //enter
      setTimeout(serialSearch(),1000);
    }
  });
</script>