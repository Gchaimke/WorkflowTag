<!doctype html>
<?php
$user_language = $this->config->item('language');
if (isset($this->session->userdata['logged_in'])) {
  $user = ($this->session->userdata['logged_in']);
  $user_language = $user['language'] != '' ? $user['language'] : $this->config->item('language');
} else {
  header("location: /users/login");
}
$dir = $user_language == 'hebrew' ? 'rtl' : 'ltr';
?>
<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="Content-Language" content="en">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Checklist online">
  <meta name="author" content="Chaim Gorbov">
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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <!-- Bootstrap core CSS -->
  <link href="<?php echo base_url('assets/css/bootstrap.css'); ?>" rel="stylesheet">
  <link href="<?php echo base_url('assets/css/all.css?' . filemtime('assets/css/all.css')); ?>" rel="stylesheet">
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
  if ($dir == 'rtl') {
    echo '<link href="' . base_url('assets/css/rtl.css?' . filemtime('assets/css/rtl.css')) . '" rel="stylesheet">';
  }
  ?>
</head>

<body class="<?= $dir ?>">