<!doctype html>
<?php
if (isset($this->session->userdata['logged_in'])) {
    header("location: /users/user_login_process");
}
?>
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
                    <div class="col-md-2 w-25 mx-auto text-center form py-3">
                        <?php
                        if (isset($response) && $response != "") {
                            echo '<div class="alert alert-success" role="alert">';
                            echo $response . ' </div>';
                        }

                        if (isset($logout_message)) {
                            echo "<div class='message'>";
                            echo $logout_message;
                            echo "</div>";
                        }

                        if (isset($message_display)) {
                            echo "<div class='message'>";
                            echo $message_display;
                            echo "</div>";
                        }

                        if (isset($error_message)) {
                            echo "<div class='error_msg'>$error_message" . validation_errors() . "</div>";
                        }
                        ?>
                        <img class="display-4 py-2 text-truncate" src="/assets/img/user.png" alt="" width="100" height="100">
                        <div class="px-2 rtl">
                            <?php echo form_open('users/user_login_process', 'class=justify-content-center'); ?>
                            <div class="form-floating mb-3">
                                <input type="text" name="name" id="name" class="form-control center" placeholder="Username" required autofocus>
                                <label for="inputUser" class="sr-only">username</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="password" name="password" id="password" class="form-control center" placeholder="Password" required>
                                <label for="inputPassword" class="sr-only">password</label>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg">Login</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>