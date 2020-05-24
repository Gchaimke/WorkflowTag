<!doctype html>
<?php
if (isset($this->session->userdata['logged_in'])) {
  $username = ($this->session->userdata['logged_in']['name']);
  $role = ($this->session->userdata['logged_in']['role']);
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
  <title>
    <?php
    if (isset($_GET['sn'])) {
      echo $_GET['sn'];
    } else {
      echo "WorkflowTag - " . pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
    }
    ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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