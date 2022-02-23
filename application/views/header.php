<!doctype html>
<?php
if(MAINTAINCE){
  include_once("application/views/miantaince.php");
  die;
}
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
  <title>WorkflowTag</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <!-- Bootstrap core CSS -->
  <link href="<?php echo base_url('assets/css/bootstrap/bootstrap.css'); ?>" rel="stylesheet">
  <!-- Custom styles for this template -->
  <link href="<?php echo base_url('assets/css/all.css?' . filemtime('assets/css/all.css')); ?>" rel="stylesheet">
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