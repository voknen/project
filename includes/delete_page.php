<?php

require '../classes/landmarkDB.php';

$landmark = new LandmarkDB();
$landmark->delete($_GET['landmark_id']);

echo "<meta charset='UTF-8' http-equiv='refresh' content='1;URL=my_landmarks.php'/>Избраната забележителност беше изтрита!";