<?php session_start(); ?>
<?php require_once("includes/config.php"); ?>
<?php
$quality = trim($_REQUEST['quality']);
$genre = trim($_REQUEST['genre']);
$rating = trim($_REQUEST['rating']);
$lang = trim($_REQUEST['lang']);
$order = trim($_REQUEST['order']);

$results = search($quality,$genre,$rating,$lang,$order);
if (@$results->num_rows >= 1) {
    while ($m = $results->fetch_object()) {
        displayThumb($m->imdb);
    }
}else {
    echo "<h2>No Results Found!</h2>";
}
