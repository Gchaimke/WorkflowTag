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
  <ul class="navbar-nav mr-auto">
  <li class="nav-item mx-1 mt-1 mt-lg-0 "><a class="nav-link btn btn-sm btn-outline-success p-1" href="/">New Checklist</a></li>
    <?php
    if (isset($project)) {
      echo '<li class="nav-item mx-1 mt-1 mt-lg-0"><a class="nav-link btn btn-sm btn-outline-warning p-1" href="/production/checklists/' . $project . '">' . $project . '</a></li>';
    }
    ?>
  </ul>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarDefault" aria-controls="navbarDefault" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarDefault">
    <ul class="navbar-nav mr-auto">
    
    </ul>
    <ul class="navbar-nav  pull-right">
      <li class="nav-item dropdown mx-1 mt-3 mt-lg-0">
        <a class="nav-link dropdown-toggle btn btn-sm btn-outline-secondary" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Hi <?php echo $user_view_name; ?>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="/users/edit/<?php echo $id ?>">Edit <?php echo $username?> profile</a>
          <a class="dropdown-item" href="/users/logout">Logout</a>
        </div>
      </li>
      <?php if ($role == 'Admin') {?>
        <li class="nav-item nav-item mx-1 mt-3 mt-lg-0 "><a class="nav-link btn btn-sm btn-outline-success" href="/users">Users</a></li>
        <li class="nav-item nav-item mx-1 mt-3 mt-lg-0 "><a class="nav-link btn btn-sm btn-outline-info" href="/clients">Clients</a></li>
        <li class="nav-item nav-item mx-1 mt-3 mt-lg-0 "><a class="nav-link  btn btn-sm btn-outline-primary" href="/templates">Templates</a></li>
        <li class="nav-item dropdown nav-item mx-1 mt-3 mt-lg-0">
        <a class="nav-link dropdown-toggle btn btn-sm btn-outline-danger" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Admin
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
        <a class="dropdown-item" href="/admin/mange_uploads">Uploads</a>
        <a class="dropdown-item" href="/admin/manage_trash">Trash</a>
        <a class="dropdown-item" href="/admin/view_log">System Log</a>
        <a class="dropdown-item" href="/admin/settings">Settings</a>
        </div>
      </li>
      <?php }?>
    </ul>
  </div>
</nav>