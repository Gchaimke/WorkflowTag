<?php
if (isset($this->session->userdata['logged_in'])) {
  $id = ($this->session->userdata['logged_in']['id']);
  $username = ($this->session->userdata['logged_in']['name']);
  $user_view_name = ($this->session->userdata['logged_in']['view_name']);
  $role = ($this->session->userdata['logged_in']['role']);
}
?>
<nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-dark main-menu">
  <a class="navbar-brand" href="/">Workflow Tag</a>
  <div class="navbar-nav mr-auto">
    <a class="nav-item btn btn-outline-success p-1 mx-1 mt-1 mt-lg-0 text-white" href="/">Home</a>
  </div>
  <?php
    if (isset($project)) {
      echo '<a class="nav-item btn btn-outline-warning p-1 mx-1 mt-1 mt-lg-0" href="/production/checklists/' . $project . '">' . $project . '</a>';
    }
    ?>
    <a id="nav_main_category" class="nav-item btn btn-outline-warning p-1 mx-1 mt-1 mt-lg-0" href="/" hidden></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarDefault" aria-controls="navbarDefault" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarDefault">
    <div class="navbar-nav mr-auto">

    </div>
    <ul class="navbar-nav">
    <a class="nav-item btn btn-outline-warning p-1 mx-1 mt-3 mt-lg-0 text-white" href="/rma">RMA</a>
      <?php if ($role == 'Admin') { ?>
        <a class="nav-item nav-item btn btn-outline-info mx-1 mt-3 mt-lg-0 text-white" href="/clients">Clients</a>
        <a class="nav-item nav-item btn btn-outline-primary mx-1 mt-3 mt-lg-0 text-white" href="/templates">Templates</a>
        <a class="nav-item nav-item btn btn-outline-success mx-1 mt-3 mt-lg-0 text-white" href="/users">Users</a>
        <li class="nav-item dropdown nav-item mt-3 mt-lg-0">
          <a class="nav-item dropdown-toggle btn btn-outline-danger text-white" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Admin
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="/admin/mange_uploads">Uploads</a>
            <a class="dropdown-item" href="/admin/manage_trash">Trash</a>
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