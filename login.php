<?php session_start(); ?>
<?php if (@isset($_SESSION['user'])) {
    header("Location: .");
}
require_once("includes/config.php"); ?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>mDB - The Movie Database</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/default.css">
    <script src="js/jquery-1.11.3.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</head>
<body>
<nav class="navbar navbar-default">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href=".">mDB</a>
            <div class="navbar-text mv-count"><?php echo movieCount(); ?> +</div>
        </div>
    </div><!-- /.container-fluid -->
</nav>
<div class="container">
    <div id="msg"></div>
    <form class="form-signin">
        <h2 class="form-signin-heading">mDB login</h2>
        <label for="inputEmail" class="sr-only">User ID</label>
        <input type="text" id="inputUser" class="form-control" placeholder="User ID" required="" autofocus="">
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" id="inputPassword" class="form-control" placeholder="Password" required="">
        <div id="reg-info">
            <label class="sr-only">Email address</label>
            <input type="text" id="inputIP" class="form-control" placeholder="Your Local IP" required="" autofocus="" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>">
            <hr>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="button" id="submit" alt="login">Sign in</button>
        <div class="info">
            If you don't have a login please <span id="request-login">request</span> .
        </div>
    </form>
</div>
<script type="text/javascript">
    //login
    $("#submit").click(function () {
        $.ajaxSetup({cache: false});
        var loadUrl = "ajax-login.php";
        var action = $(".btn").attr('alt');
        if(action == 'register') {
            var  usrIP = $("#inputIP").val();
        }
        $.post(
            loadUrl,
            {user: $("#inputUser").val(), pass: $("#inputPassword").val(), ip: usrIP, act: action},
            function (responseText) {
                $("#msg").html(responseText);
                if(responseText == ''){
                    window.location.replace(".")
                }
                //$("#mv-content").html(responseText);
            },
            "html"
        );
    });

    //Request
    $("#request-login").click(function() {
        $("#reg-info").fadeIn();
        $("#submit").html("Register");
        $("#submit").attr("alt","register");
    });
</script>
<?php include("footer.php"); ?>