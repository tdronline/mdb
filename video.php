<?php include("includes/config.php"); ?>
<?php
$movie = base64_decode(trim($_REQUEST['mv']));
$mvp = explode("/",$movie);
$filePath = WEB_URL .'/movies/'.$movie;
?>
<head>
    <link href="css/video-js.css" rel="stylesheet">
    <!-- If you'd like to support IE8 -->
    <script src="js/videojs-ie8.min.js"></script>
    <title>mDB - VOD</title>
</head>

<body>
<video id="my-video" class="video-js" controls preload="auto" width="100%" height="264"
       poster="img/mdb.png" data-setup="{}">
    <source src="<?php echo $filePath; ?>" type='video/mp4'>
    <source src="<?php echo $filePath; ?>" type='video/webm'>
    <p class="vjs-no-js">
        To view this video please enable JavaScript, and consider upgrading to a web browser that
        <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
    </p>
</video>
<script src="js/video.js"></script>
</body>