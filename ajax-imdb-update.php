<?php session_start(); ?>
<?php require_once("includes/config.php"); ?>
<?php
$mID = "tt".trim($_REQUEST['mid']);
$mvFile = trim($_REQUEST['file']);
$mFolder = trim($_REQUEST['folder']);


$mid = getIMDBinfo($mID);
$mInfo = $_SESSION["movie"][$mid];
$mvTitle = $mInfo['title']." ".$mInfo['year'];
$mvGenre = $mInfo['genre'];
$mvRating = $mInfo['rating'];
$_SESSION["movie"][$mid]['folder'] = $mFolder;

echo "
<strong><input type='text' class='form-control input-sm imdb-id' value='$mid' ><span class='glyphicon glyphicon-film' aria-hidden='true'></span> $mvTitle</strong>  
<div class='info'> 
    <span class='glyphicon glyphicon-star' aria-hidden='true'></span> $mvRating
    <span class='glyphicon glyphicon-tags' aria-hidden='true' data-toggle='tooltip' data-placement='right' title='$mvGenre'></span>
    <span class='glyphicon glyphicon-folder-open' aria-hidden='true' data-toggle='tooltip' data-placement='right' title='$mFolder'></span>                 
    <span class='glyphicon glyphicon-film' aria-hidden='true' data-toggle='tooltip' data-placement='right' title='$mvFile'></span>
</div>
<span class='controls'>
<span class=' pull-right'>
    <a href='' class='btn btn-danger btn-xs'>Delete</a>
    <a title='$mid' href='ajax-add-movie.php?mid=$mid' class='btn btn-success btn-xs add-mv'>Add Movie</a>
</span>
<select name=\"rip\" class=\"form-control input-sm rip-type\" required>
    <option value=\"\">-- Select --</option>
    <option value=\"br-1080\">BluRay 1080p</option>
    <option value=\"br-720\">BluRay 720p</option>
    <option value=\"hd-1080\">HDVD 1080p</option>
    <option value=\"hd-720\">HDVD 720p</option>
    <option value=\"dvd\">DVD 720p</option>
</select> 
</span>                            				
";