<?php
function mkFolder($oriFolder, $movieTitle, $lang = 'english')
{
    //Setup our new file path
    $folderPath = MV_PATH . DIRECTORY_SEPARATOR . $lang . DIRECTORY_SEPARATOR;
    // Create target dir
    if (is_dir($folderPath . $oriFolder)) {
        if (is_dir($folderPath . $movieTitle)) {
            echo "Already Exist Please check";
        }
        echo "YES";
        //if(rename($folderPath, $loc.DIRECTORY_SEPARATOR.$folderName)){
        //}
    }
}

function getResult($query)
{
    global $db;
    $res = $db->query($query);
    if ($res->num_rows == 1) {
        $data = $res->fetch_object();
    } else {
        $data = NULL;
    }
    return $data;
}

function movieTime($time)
{
    if ($time > 60) {
        $h = floor($time / 60);
        $m = $time % 60;
        $runtime = "$h Hr $m Min";
    } else {
        $runtime = $time . "Min";
    }
    return $runtime;
}

function displayThumb($mID)
{
    $Q = "SELECT * FROM `movielist` WHERE `imdb` LIKE '$mID'";
    $mv = getResult($Q);
	$rating = ''; $quality ='';

    if (strlen($mv->title) > 40) {
        $mv_name = substr($mv->title, 0, 40) . "...";
    } else {
        $mv_name = $mv->title;
    }
    $title = $mv_name . " [" . $mv->rel_year . "]";
    $rating = $mv->rating;
    $info = unserialize($mv->info);
    if (!empty($mv->rip_type)) {
        $quality = "<div class='quality'>{$mv->rip_type}</div>";
    }
    echo "<div class='col-md-2 col-sm-4 col-xs-12 movie'>
	<div class='thumb-container'>
    <img class='thumbnail' src='thumb.php?id=$mID' title='{$mv->title}' />
	</div>
    <h5>$title</h5>
    <ul class='mv-info-con'>
    <li class='rating'><span class=\"glyphicon glyphicon-star\" aria-hidden=\"true\"></span> $rating 	$quality</li>
    <li><span class=\"glyphicon glyphicon-tags\" aria-hidden=\"true\"></span> {$info['genre']}</li>
    <li><span class=\"glyphicon glyphicon-user\" aria-hidden=\"true\"></span> {$info['director']}</li> 
    </ul>
  
    <a href='movie.php?m=$mID' class='btn btn-sm btn-primary btn-block mv-btn'>View Info</a>
  </div>";
}

function pegination($page)
{
    echo "<div class=\"row text-center\">
  <nav>
  <ul class=\"pagination\">
    <li>
      <a href=\"?page=1\" aria-label=\"Previous\">
        <span aria-hidden=\"true\">&laquo;</span>
      </a>
    </li>";
    $total_records = movieCount();
    $total_pages = ceil($total_records / 48);
    for ($i = 1; $i <= $total_pages; $i++) {
        if ($i == $page) {
            $cls = "class='active'";
        } else {
            $cls = '';
        }
        echo "<li $cls><a href='?page=$i'>$i</a></li>";

    };
    echo "<li>
    <a href=\"?page=$total_pages\" aria-label=\"Next\">
        <span aria-hidden=\"true\">&raquo;</span>
    </a>
</li>
</ul>
</nav>
</div>";
}

function getIMDBinfo($mvTitle)
{
    $oIMDB = new IMDB($mvTitle);
    if ($oIMDB->isReady) {
        $folderName = $oIMDB->getTitle(false) . " [" . $oIMDB->getYear() . "]";
        $folderName = preg_replace('/[\:\"\<\>\*]/', ' - ', $folderName);
        $mvTitle = $oIMDB->getTitle() . " " . $oIMDB->getYear();
        $mvRating = $oIMDB->getRating();
        $mvGenre = $oIMDB->getGenre();
        $mvURL = $oIMDB->getUrl();
        $i = array_filter(explode('/', $mvURL));
        $mid = substr(end($i), 2);

        $mInfo = array(
            "title" => $oIMDB->getTitle(false),
            "runtime" => $oIMDB->getRuntime(),
            "poster" => $oIMDB->getPoster('big'),
            "year" => $oIMDB->getYear(),
            "rating" => $oIMDB->getRating(),
            "votes" => $oIMDB->getVotes(),
            "country" => $oIMDB->getCountry(),
            "genre" => str_replace(' /', ',', $oIMDB->getGenre()),
            "plot" => $oIMDB->getPlot(),
            "language" => $oIMDB->getLanguage(),
            "director" => $oIMDB->getDirector(),
            "music" => $oIMDB->getSoundMix(),
            "actors" => $oIMDB->getCast(),
            "relesed" => $oIMDB->getReleaseDate()
        );

        //GET movie ID
        $imdblink = trim($oIMDB->getUrl());
        $i = array_filter(explode('/', $imdblink));
        $mid = substr(end($i), 2);

        // Add info to array
        $_SESSION["movie"][$mid] = $mInfo;
        return $mid;
    } else {
        return "0";
    }
}

function delFiles($dir) 
{ 
   $files = array_diff(scandir($dir), array('.','..')); 
    foreach ($files as $file) { 
      (is_dir("$dir/$file")) ? delFiles("$dir/$file") : unlink("$dir/$file"); 
    }  
	if (rmdir($dir)) {
        return 1;
    } else {
        return 0;
    }
}

function movieCount()
{
    global $db;
    $ALL = "SELECT * FROM movielist";
    $res = $db->query($ALL);
    $total_records = $res->num_rows;
    return $total_records;
}

function dLink($location = 'english', $title)
{
	$extensions = array('mkv','mp4','mpeg','mpg','avi','mov');

	//Setup our new file path
    $folderPath = MV_PATH . DIRECTORY_SEPARATOR . $location . DIRECTORY_SEPARATOR;
	$moviefolder = $folderPath . $title;

	//echo $moviefolder;
	if(is_dir($moviefolder)) {
		$files = scandir($moviefolder);
		foreach ($files as $file) {
			@$f_ext = end(explode('.', $file));
			if(in_array($f_ext, $extensions)) {
				$movie_path = $location.'/'.$title.'/'.$file;
				$file_link = WEB_URL . "/movies/".$movie_path;
				echo "<a href=\"$file_link\" class='btn btn-primary' >$file</a>";
                if($file) {
                    $mvpath = base64_encode($movie_path);
                    echo " <a href='video.php?mv=$mvpath' class='btn btn-success mv-btn watch' >Watch Movie</a>";
                }
			}
		}
	}
}

function search($quality,$genre,$rating,$lang,$order){
    global $db;
    if (!empty($quality)){$q_quality = "AND `rip_type` = '$quality'";} else{$q_quality ='';}
    if (!empty($genre)){$q_genre = "AND `genre` LIKE '%$genre%'";} else{$q_genre ='';}
    if (!empty($rating)){$q_rating = "AND `rating` >= '$rating'";} else{$q_rating ='';}
    if (!empty($lang)){$q_lang = "AND `location` = '$lang'";} else{$q_lang = '';}
    if (!empty($order)){$q_order = $order;} else{$q_order = 'rel_year';}
    $Q = "SELECT * FROM `movielist` WHERE 1 $q_quality $q_genre $q_rating $q_lang ORDER BY $q_order";
    $res = $db->query($Q);
    if ($res->num_rows > 0) {
        return $res;
    }
}

function uniqueValues($colomn){
    $Q = "SELECT DISTINCT($colomn) FROM `movielist`";
    global $db;
    $res = $db->query($Q);
    return $res;
}