<?php

require '../classes/landmarkDB.php';

$landmark = new LandmarkDB();
if (is_numeric($_GET['landmark_id']) && is_numeric($_GET['image'])) {
	$landmark->deleteImage($_GET['image'], $_GET['landmark_id']);

	echo "<meta charset='UTF-8' http-equiv='refresh' content='1;URL=images.php?landmark_id=" . $_GET["landmark_id"] . "'/>Избраната снимка беше изтрита!";
} else {
	echo '<!DOCTYPE html>
    <html>
        <head>
            <title>Промени</title>
            <meta charset = "UTF-8" />  
            <link rel="stylesheet" type="text/css" href="../css/forms.css">   
        </head>
        <strong>Линка, който сте избрали е грешен!</strong>
    </html>';
}