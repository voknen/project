<?php

session_start();

if (!isset($_SESSION['isLogged']) && !isset($_COOKIE['remember'])) {
	header("Location: ../login.php");
	exit();
}

require '../classes/landmarkDB.php';
$landmark = new LandmarkDB();
$landmarkAllImages = $landmark->selectAllLandmarkItemsImages((int)($_GET['landmark_id']));
?>
<?php if (is_numeric($_GET['landmark_id']) && $landmark->isLandmarkToUser((int)($_GET['landmark_id']))) : ?>
	<!DOCTYPE html>
	<html>
		<head>
			<title>Промени</title>
	        <meta charset = "UTF-8" />  
	        <link rel="stylesheet" type="text/css" href="../css/forms.css">   
		</head>
		<body>
		<p>Вашите снимки (Натиснете върху дадена снимка, ако искате да я изтриете)</p>
		<?php foreach ($landmarkAllImages as $image) : ?>
			<a href="delete_image.php?landmark_id=<?php echo $_GET['landmark_id'];?>&image=<?php echo $image->id; ?>" onclick = "return confirm('Искате ли да изтриете тази снимка?')">
				<img src="../classes/images/<?php echo $image->image; ?>" width="200" height="200"/>
			</a>
		<?php endforeach; ?>
		<p>
			<a href="update.php?landmark_id=<?php echo $_GET['landmark_id']; ?>">Назад</a>
		</p>
		</body>
	</html>
<?php else : ?>
    <!DOCTYPE html>
    <html>
        <head>
            <title>Промени</title>
            <meta charset = "UTF-8" />  
            <link rel="stylesheet" type="text/css" href="../css/forms.css">   
        </head>
        <?php echo "<strong>Линка, който сте избрали е грешен!</strong>"; ?>
    </html>
<?php endif; ?>