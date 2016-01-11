<?php

session_start();

if (!isset($_SESSION['isLogged']) && !isset($_COOKIE['remember'])) {
	header("Location: ../login.php");
	exit();
}

require '../classes/landmarkDB.php';
$landmark = new LandmarkDB();
?>

<?php if (is_numeric($_GET['landmark_id']) && $landmark->isLandmarkToUser((int)($_GET['landmark_id']))) : ?>
    <?php
       
        $landmarkInfo = $landmark->select((int)($_GET['landmark_id']));
        $_SESSION['city_id'] = (int)($landmarkInfo->city_id);
        $landmarkAllImages = $landmark->selectAllLandmarkItemsImages((int)($_GET['landmark_id']));
    ?>
    <!DOCTYPE html>
    <html>
    	<head>
    		<title>Промени</title>
            <meta charset = "UTF-8" />  
            <link rel="stylesheet" type="text/css" href="../css/forms.css">   
    	</head>
    	<body background="../images/background/back_edit.jpg">
    		<?php if ($_SERVER['REQUEST_METHOD'] == 'POST') : ?>
                <?php
                    $errors = $landmark->isValidEditLandmark($_POST);
                    $landmarkInfo = $landmark->select((int)($_GET['landmark_id']));
                 ?>
                <?php if (count($errors) == 0) : ?>
                    <?php echo "<strong>Вашите данни са променени успешно!</strong>"; ?>
                <?php else : ?>
                    <?= isset($errors['same_data']) ? "<strong>" . $errors['same_data'] . "</strong>" : ""; ?>
                <?php endif; ?>
            <?php endif; ?>
            <form action="" id="editLandmark" name = "editLandmark" method = "post" enctype = "multipart/form-data">
    			<h1 class="title-text">Промени забележителност</h1>
    				<p>   
                        <label for = "name">Име на забележителността</label>  
                        <input id = "name" name = "name" type = "text" value = "<?= $landmarkInfo->name;?>"/>
                        <?php if (isset($errors['name'])) : ?>
                            <p class="errormsg">
                                <?= $errors['name']; ?>
                            </p>
                        <?php endif; ?>  
                    </p>                
                    <p>   
                        <label for = "review">Кратко описание</label>  
                        <textarea id = "review" name = "review"><?= $landmarkInfo->review;?></textarea>
                        <?php if (isset($errors['review'])) : ?>
                            <p class="errormsg">
                                <?= $errors['review']; ?>
                            </p>
                        <?php endif; ?>  
                    </p>
                    <p>
                    	<label for = "city">Град</label>
                    	<?php
                    		require '../classes/cities.php';
                    		$cities = new Cities();
                    		echo $cities->citySelector();
                    	?>
                    </p>
                    <p>
                        <label for="big">Голяма снимка(940x380): </label>
                         <input type="file" name="big" id="big" >
                         <?php if (isset($errors['big'])) : ?>
                            <p class="errormsg">
                                <?= $errors['big']; ?>
                            </p>
                        <?php endif; ?>

                    </p>
                    <p>
                        <label for="small">Малкa снимкa(200x120): </label>
                         <input type="file" name="small" id="small" >
                         <?php if (isset($errors['small'])) : ?>
                            <p class="errormsg">
                                <?= $errors['small']; ?>
                            </p>
                        <?php endif; ?>
                    </p>
                    <p class="title-text">
                        <a href ="images.php?landmark_id=<?php echo $_GET['landmark_id']; ?>" target="_blank">Вижте вашите снимки</a>
                    </p>
                    <p>   
                        <input type = "submit" name = "editLandmark" value = "Промени"/>   
                    </p>
    				<p class="title-text">
                        <a href = "my_landmarks.php">Назад към моите забележителности</a>
                    </p>
            </form>  
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