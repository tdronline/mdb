<?php session_start(); ?>
<?php require_once("includes/config.php"); ?>
<?php
$mID = trim($_REQUEST['mid']);
$dir = MV_PATH . DIRECTORY_SEPARATOR . 'english';
$folder = trim($_REQUEST['folder']);
$apth = $dir . DIRECTORY_SEPARATOR . $folder ;

if(is_dir($apth)) {
	$del = delFiles($apth);
    if ($del == 1) {
        //Reset Movie Details
        $_SESSION["movie"][$mID] = array(); 
    }
	echo $del;
}
?>