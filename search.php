<?php
include("includes/config.php");
$output = '';
//collect
if(isset($_POST['s'])) {
	$searchq = $_POST['s'];	
	$query = $db->query("SELECT * FROM movielist WHERE title LIKE '%$searchq%'") or die("could not search!");
	$count = $query->num_rows;
	if($count == 0){
	$output = 'there was no search result!';
	}else{
	while($row=$query->fetch_array()) {
	$titl = $row['title'];
	$year = $row['rel_year'];
	$imdb = $row['imdb'];
	
	$output .= "<div><a href='movie.php?m=$imdb' class='mv-btn' >$titl [$year]</a></div>";
	
	}
}
}
echo $output; ?>