<?php session_start(); ?>
<?php require_once("includes/config.php"); ?>
<?php
$mID = trim($_REQUEST['mid']);
$ripType = trim($_REQUEST['rip']);
//print_r($_SESSION["movie"][$mID]);

if(isset($_SESSION["movie"])) {
    $mv = $_SESSION["movie"][$mID];

    //Copy Poster to folder
    if(!empty($mv['poster'])){
        $filename = "covers/".$mID.".jpg";
        if(!is_file($filename)){
            $cover = copy($mv['poster'], $filename);
        }
    }

    //Set Movie Folder Path
    if (empty($lang)) { $lang = 'english'; }
    $dir = MV_PATH . DIRECTORY_SEPARATOR . $lang;
    $mvDIR = $mv['title'] . " [" . $mv['year'] . "]";
    $mvDIR = preg_replace("/[<>:\"\/\\|?*]/", ' - ', $mvDIR);
    $folderPath = $dir . DIRECTORY_SEPARATOR . $mv['folder'];

    // Create target dir
    if (file_exists($folderPath)) {
       if (rename($folderPath, $dir . DIRECTORY_SEPARATOR . $mvDIR)) {
            $renameOK = TRUE;
            echo "<div class='info-wrap'>
                    <span class='glyphicon glyphicon-folder-open' aria-hidden='true'></span>
                    <span class='glyphicon glyphicon-ok-circle' aria-hidden='true'></span>
                    </div>";
        } else {
            echo "Could not Rename the movie.";
        }
    } else {
       echo "Folder exists!";
    }
}

// Update Database
if($renameOK == TRUE) {
//Save to Database
    $title = preg_replace("/[<>:\"\/\\|?*]/", ' - ', $mv['title']);
    $title = addslashes($title);
    $year = $mv['year'];
    $rating = $mv['rating'];
    $genre = $mv['genre'];
    $lang = $mv['language'];
    $location = $mv['year'];
    $rip = addslashes($ripType);
    $info = serialize($mv);
    $info = addslashes($info);
    $user = 'tdr';

    $CHK = "SELECT * FROM `movie-db`.`movielist` WHERE `imdb` = '$mID'";
    $res = $db->query($CHK);
    if($res->num_rows == 0){
        $ADD = "INSERT INTO `movie-db`.`movielist` (`imdb`, `title`, `rel_year`, `rating`, `genre`, `lang`, `imdblink`, `info`, `location`, `rip_type`, `user`, `uploaded`)
VALUES ('$mID', '$title', '$year','$rating' , '$genre', '$lang', NULL, '$info', '$location', '$rip', '$user', CURRENT_DATE());";

        // Run query
        if($db->query($ADD)){
            echo "<div class='info-wrap'>
            <span class='glyphicon glyphicon-floppy-disk' aria-hidden='true'></span>
            <span class='glyphicon glyphicon-ok-circle' aria-hidden='true'></span>
            </div>";
        }else{
            echo "<div class='info-wrap'>
            <span class='glyphicon glyphicon-floppy-disk' aria-hidden='true'></span>
            <span class='glyphicon glyphicon-ban-circle' aria-hidden='true'></span>
            </div>";
            echo $ADD;
            //echo $db->error();

        }
    }else{
		$UPDATE = "UPDATE `movie-db`.`movielist` SET `title` = '$title' WHERE `movielist`.`imdb` = '$mID';";
		if($db->query($UPDATE)){
			echo "<div class='info-wrap'>
            <span class='glyphicon glyphicon-floppy-disk' aria-hidden='true'></span>
            <span class='glyphicon glyphicon-pencil' aria-hidden='true'></span>
            </div>";
		}
	}

    //Reset Movie Details
    $_SESSION["movie"][$mID] = array();
}
?>