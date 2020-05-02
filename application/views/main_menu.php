<?php
if (isset($this->session->userdata['logged_in'])) {
  $username = ($this->session->userdata['logged_in']['username']);
  $role = ($this->session->userdata['logged_in']['userrole']);
}
?>
<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
  <a class="navbar-brand" href="/dashboard">Workflow Tag</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarsExampleDefault">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Dashboard
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="/dashboard">Manage</a>
          <?php
          if ($role == 'Admin') {
            echo '<div class="dropdown-divider"></div>
          <a class="dropdown-item" href="/admin/settings">Settings</a>';
          } ?>
        </div>
      </li>
      <?php
      if ($role == 'Admin') {
        echo '<li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Users
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="/users/">Manage</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="/users/create">Create</a>
        </div>
      </li>';
      } ?>

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Checklists
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="/checklists/manage_templates">Manage Templates</a>
          <a class="dropdown-item" href="/checklists/">Manage Checklists</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="/checklists/add_template">Add Template</a>
          <a class="dropdown-item" href="/checklists/add_checklist">Add Checklist</a>
        </div>
      </li>
    </ul>
    <ul class="navbar-nav  pull-right">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Hello <?php echo $username; ?>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="/users/logout">Logout</a>
        </div>
      </li>
    </ul>
  </div>
</nav>