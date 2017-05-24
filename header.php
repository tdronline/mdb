<?php session_start(); ?>
<?php if (!isset($_SESSION['user'])) {
    header("Location: login.php");
}else {
    $us = @$_GET['logout'];
    if($us == 'true') {
        session_destroy();
        header("Location: .");
    }
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
        <form class="navbar-form navbar-text search-form">
            <input type="search" class="form-control search " id="search" placeholder="Quick Search Movies..."/>
            <span class="glyphicon glyphicon-chevron-down filter"></span>
            <div class="suggest" id="suggest"></div>
        </form>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                <?php if($_SESSION['user']['type'] == 'admin'){?>
                <li><a href="search-movies.php">Add Movies</a></li>
                <?php } ?>
                <?php if($_SESSION['user']){?>
                <li><a href="?logout=true">Logout</a></li>
                <?php } ?>
            </ul>
        </div><!-- /.navbar-collapse -->
        <div class="filter-container">
            <div class="container">
                <div class="row">
                    <form class="">
                        <div class="form-group col-md-2">
                            <select class="form-control" id="quality">
                                <option value=""> Quality</option>
                                <?php
                                $quality = uniqueValues('rip_type');
                                while ($rip = $quality->fetch_object()) {
                                    $rip_label = $rip->rip_type;
                                    echo "<option value=\"$rip_label\">" . strtoupper($rip_label) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <select class="form-control" id="genre">
                                <option value=""> Genre</option>
                                <?php
                                $gen = filterGenre();
                                foreach ($gen as $genre) {
                                    echo "<option value=\"$genre\">$genre</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <select class="form-control" id="rating">
                                <option value=""> Rating</option>
                                <option value="9"> 9+</option>
                                <option value="8"> 8+</option>
                                <option value="7"> 7+</option>
                                <option value="6"> 6+</option>
                                <option value="5"> 5+</option>
                                <option value="4"> 4+</option>
                                <option value="3"> 3+</option>
                                <option value="2"> 2+</option>
                                <option value="1"> 1+</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <select class="form-control" id="order">
                                <option value=""> Order</option>
                                <option value="rel_year DESC">Year Latest</option>
                                <option value="rel_year ASC">Year Oldest</option>
                                <option value="rating DESC">Highest Rating</option>
                                <option value="rating ASC">Lowest Rating</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <select class="form-control" id="lang">
                                <option value=""> Language</option>
                                <?php
                                $lang = uniqueValues('location');
                                while ($lg = $lang->fetch_object()) {
                                    $lang_label = $lg->location;
                                    echo "<option value=\"$lang_label\">" . ucfirst($lang_label) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-success" type="button" id="filter-movies">Search</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</nav>