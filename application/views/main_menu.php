<?php
if (isset($this->session->userdata['logged_in'])) {
  $id = ($this->session->userdata['logged_in']['id']);
  $username = ($this->session->userdata['logged_in']['name']);
  $role = ($this->session->userdata['logged_in']['role']);
}
?>
<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark main-menu">
  <a class="navbar-brand" href="/">Workflow Tag</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarDefault" aria-controls="navbarDefault" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarDefault">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="/production/">Production</a>
      </li>
      <?php
      if (isset($project)) {
        echo '<li class="nav-item"><a class="nav-link" href="/production/checklists/' . $project . '">' . $project . '</a></li>';
      }
      ?>
    </ul>
    <ul class="navbar-nav  pull-right">
      <?php
      if ($role == 'Admin') {
        echo '<li class="nav-item"><a class="nav-link" href="/production/manage_clients">Clients</a></li>';
        echo '<li class="nav-item"><a class="nav-link" href="/production/manage_templates">Templates</a></li>';
        echo '<li class="nav-item"><a class="nav-link" href="/users">Users</a></li>';
        echo '<li class="nav-item"><a class="nav-link" href="/admin/settings">Settings</a></li>';
      } ?>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Hello <?php echo $username; ?>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="/users/edit/<?php echo $id ?>">Password</a>
          <a class="dropdown-item" href="/users/logout">Logout</a>
        </div>
      </li>
    </ul>
  </div>
</nav>