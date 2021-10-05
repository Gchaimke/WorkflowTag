<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="<?php echo base_url('assets/favicon.ico'); ?>">
    <title>WorkFlow Tag&copy; <?php echo date('Y'); ?> </title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url('assets/css/bootstrap/bootstrap.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/login.css?' . filemtime('assets/css/login.css')); ?>" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <style>
        #cover {
            /*background: #222 url('https://unsplash.it/1920/1080/?random') center center no-repeat;*/
            background: #222 url('<?php echo base_url('assets/img/backgrounds/').mt_rand(1,14).'.jpg'?>') center center no-repeat;
        }
    </style>
</head>

<body>
    <section id="cover" class="min-vh-100">
        <div id="cover-caption">
            <div class="container">
                <div class="row">
                    <div class="col-md-2 w-50 mx-auto text-center form py-3">
                        <img class="display-4 py-2 text-truncate" src="/assets/img/user.png" alt="" width="100" height="100">
                        <div class="px-2">
                        <h2>Updating system, please wait few minutes.</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>