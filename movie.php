<?php include("includes/config.php"); ?>
<?php
if (isset($_REQUEST['m']) && !empty($_REQUEST['m'])) {
    $mID = $_REQUEST['m'];
} else {
    $msg = "<div class='alert alert-error'>Invalied Input!</div>";
}
?>
<?php echo @ $msg; ?>
<?php
if (is_numeric($mID)) {
    $Q = "SELECT * FROM movielist WHERE imdb = '$mID'";
    $f = $mv = getResult($Q);
    $mvName = $f->title;
    $location = $f->location;
    $lang = $f->lang;
    if (!empty($f->info)) {
        $info = $f->info;
        $in = unserialize($info);
        $runtime = movieTime($in['runtime']);
    }
    if (!file_exists("covers/" . $mID . ".jpg")) {
        $cover = 'covers/default.png';
    } else {
        $cover = "covers/" . $mID . ".jpg";
    }
    echo "<div class='row'>
	<div class='col-lg-4'>
	  <img class='img-rounded'  style='width: 100%;' src='$cover'>
	</div><div class='col-lg-8 popup-info'>";
    $title = $f->title . " [{$f->rel_year}]";
    echo "<h2>$title</h2>
<ul class='mv-info-con'>
	<li class='rating'><strong>Rating:</strong> {$in['rating']}</li>
	<li><span class=\"glyphicon glyphicon-time\" aria-hidden=\"true\"></span> $runtime</li>
	<li><span class=\"glyphicon glyphicon-thumbs-up\" aria-hidden=\"true\"></span> {$in['votes']}</li>
	<li><span class=\"glyphicon glyphicon-tags\" aria-hidden=\"true\"></span> {$in['genre']}</li>
	<li><strong>Director:</strong> {$in['director']}</li>
	<li><strong>Actors:</strong> </li>
	<li><strong>Relese Date:</strong> {$in['relesed']}</li>
</ul><hr>";
    //if(isset($_SESSION['user'])){
	echo "<span class='save-movie-info'>To save movie Right click and choose save file as.</span>";
    dLink($location, $title, $lang);
    //}
    echo "</div></div>";
} else {
    echo "<h1>No Movie available!</h1>";
}
?>