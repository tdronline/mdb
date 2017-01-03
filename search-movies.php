<?php include("header.php"); ?>
<?php
$imdb = new IMDb(true);
$video_ext = array('mp4', 'mpeg', 'avi', 'mpg', 'mov','mkv');
$Q = "SELECT title, rel_year FROM movielist";
$res = $db->query($Q);

//Fetch movies in Database
while ($mv = $res->fetch_assoc()) {
    $mvName = $mv['title'] . ' [' . $mv['rel_year'] . ']';
    $mvArray[] = $mvName;
}

foreach(LANGUAGES as $lang) {
    echo $lang;
    if (empty($lang)) { $lang = 'english'; }

    $dir = MV_PATH . DIRECTORY_SEPARATOR . $lang;
    $files = scandir($dir);

    foreach ($files as $file) {
        if (in_array($file, array('..', '.'))) {
            continue;
        }
        if (!in_array($file, $mvArray)) {
            if (!is_file($dir . DIRECTORY_SEPARATOR . $file)) {
                $mTitiles[] = $lang.'/'.$file;
            }
        }
    }
}
?>

<div class="container">
<?php
// Start traversing
if(is_array($mTitiles)) {
    // Limit Movies Per Cycle
    $mTitiles = array_slice($mTitiles, 0, 10);

    foreach ($mTitiles as $mfolder) {
        $fp = explode('/',$mfolder);
        $mfname = $fp[1];
        $mtitle = preg_replace("/[<>:\"\/\\|?*]/", " ", $mfname);
        $n = preg_split('/\d{4}/', $mfname);
        $mTitle = $n[0];

        //Get IMDB Info
        $mid = getIMDBinfo($mTitle);
        $mInfo = $_SESSION["movie"][$mid];

        $mvTitle = $mInfo['title'] . " [" . $mInfo['year']. "]";
        $mvGenre = $mInfo['genre'];
        $mvRating = $mInfo['rating'];
        $mvURL = "http://www.imdb.com/title/tt$mid";
        $_SESSION["movie"][$mid]['folder'] = $mfolder;

        // Video File Name
        $apth = MV_PATH . DIRECTORY_SEPARATOR . $mfolder . DIRECTORY_SEPARATOR;
        $movieFiles = scandir($apth);
        foreach ($movieFiles as $mfile) {
            if (in_array(pathinfo($apth . $mfile, PATHINFO_EXTENSION), $video_ext)) {
                $size = round((filesize($apth . $mfile) / 1024) / 1024, 2);
                if ($size > 100) {
                    $mvFile = $mfile . " [" . $size . "MB]";
                }
            }
        }
		
		// Check already exists
		$folder_exist = '';
		if(in_array($mvTitle, $mvArray)) {
			$folder_exist = "Already There";
		}

        echo "<div class='panel panel-default' >
            <div class='panel-body' id='mv$mid'>
                <strong><input type='text' class='form-control input-sm imdb-id' value='$mid' alt='$mid' > <span class='glyphicon glyphicon-film' aria-hidden='true'></span>$folder_exist $mvTitle</strong>  
                <div class='info'> 
                    <span class='glyphicon glyphicon-star' aria-hidden='true'></span> $mvRating
                    <span class='glyphicon glyphicon-tags' aria-hidden='true' data-toggle='tooltip' data-placement='right' title='$mvGenre'></span>
                    <span class='glyphicon glyphicon-folder-open mv-folder' aria-hidden='true' data-toggle='tooltip' data-placement='right' title='$mfolder'></span>                 
                    <span class='glyphicon glyphicon-film mv-file' aria-hidden='true' data-toggle='tooltip' data-placement='right' title='$mvFile'></span>
                    <a href='$mvURL' target='_blank'><span class='glyphicon glyphicon-link' ></span></a>
                </div>
                <span class='controls'>
                <span class=' pull-right'>
                    <a href='' class='btn btn-danger btn-xs delete-mv' title='$mid'>Delete</a>
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
            </div>
          </div>";

    }
}else{
    echo "<div class=\"alert alert-warning\" role=\"alert\">No new movies to update copy to folder and refresh page</div>";
}
    ?>
</div>

<script type="text/javascript">
    //ajax Add movie to databse
    $('body').on("click", "a.add-mv", function () {
        var mid = $(this).attr('title');
        var pagerequest = $(this).attr('href');
        var rip = $('#mv' + mid + ' .rip-type').val();
        if (rip == '') {
            alert("Select RIP Type");
            return false;
        }
        var request = $.ajax({
            url: pagerequest + '&rip=' + rip,
            method: "POST",
            dataType: "html"
        });

        request.done(function (msg) {
            $('.mvupdate').html(msg);
            $('#mv' + mid + ' .controls').fadeOut('slow', function () {
                $('#mv' + mid + ' .controls').html(msg);
                $('#mv' + mid + ' .controls').fadeIn('slow');
            });
        });

        request.fail(function (jqXHR, textStatus) {
            alert("Request failed: " + textStatus);
        });
        return false;
    });

    //ajax Delete Movie
    $('body').on("click", "a.delete-mv", function () {
        var mid = $(this).attr('title');
        var mvfolder = $('#mv'+ mid + ' .mv-folder').attr('data-original-title');
        confirm('Do you really want to delete ' + mvfolder);
        $.post(
            'ajax-delete-movie.php',
            {folder: mvfolder, mid: mid},
            function(responseText){
                if(responseText == 1){
                    $("#mv"+ mid).fadeOut('fast');
                }else{
                    alert("Cant Delete! "+ responseText);
                }
            },
            "html"
        );
        return false;

    });

    //ajax Update IMDB details
    $('body').on("blur", "input.imdb-id", function () {
        var mid = $(this).val();
        var omid = $(this).attr('alt');
        var mvfolder = $('#mv'+ omid + ' .mv-folder').attr('data-original-title');
        var mvfile = $('#mv'+ omid + ' .mv-file').attr('data-original-title');
        if (mid == '') {
            alert("IMDB ID cannot be blank");
            return false;
        }
        alert(omid + mvfile +  mvfolder);
        $.post(
            'ajax-imdb-update.php',
            {mid: mid, folder: mvfolder, file: mvfile},
            function(responseText){
                $("#mv"+ omid).fadeOut('fast', function () {
                    $("#mv"+ omid).html(responseText);
                    $("#mv"+ omid).fadeIn();
                    $(this).attr("id","mv"+mid);
                });
            },
            "html"
        );
        return false;
    });

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });
</script>
<?php include("footer.php"); ?>