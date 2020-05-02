<!doctype html>
<?php
if (isset($this->session->userdata['logged_in'])) {
  $username = ($this->session->userdata['logged_in']['username']);
  $role = ($this->session->userdata['logged_in']['userrole']);
} else {
  header("location: /users/login");
}
?>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="<?php echo base_url('assets/favicon.ico'); ?>">
  <title><?php echo "WorkflowTag - " . pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME); ?></title>
  <!-- Bootstrap core CSS -->
  <link href="<?php echo base_url('assets/css/bootstrap.css'); ?>" rel="stylesheet">
  <link href="<?php echo base_url('assets/css/all.css'); ?>" rel="stylesheet">
  <!-- Custom styles for this template -->
  <?php
  if (isset($css_to_load)) {
    if (is_array($css_to_load)) {
      foreach ($css_to_load as $row) {
        echo  "<link href=" . base_url("assets/css/$row") . " rel='stylesheet'>";
      }
    } else {
      echo  "<link href=" . base_url("assets/css/$css_to_load") . " rel='stylesheet'>";
    }
  }
  ?>
</head>

<body>