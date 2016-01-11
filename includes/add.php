<?php

session_start();

if (!isset($_SESSION['isLogged']) && !isset($_COOKIE['remember'])) {
	header("Location: ../login.php");
	exit();
}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Добави</title>
        <meta charset = "UTF-8" />  
        <link rel="stylesheet" type="text/css" href="../css/forms.css">   
	</head>
	<body background="../images/background/back_add_landmark.jpg">
		<?php if ($_SERVER['REQUEST_METHOD'] == 'POST') : ?>
            <?php
                require '../classes/landmarkDB.php';
                $landmark = new LandmarkDB();
                $errors = $landmark->validLandmark($_POST);
             ?>
            <?php if (count($errors) == 0) : ?>
                <?php echo "<strong>Вашите данни са добавени успешно!</strong>" ?>
            <?php endif; ?>
        <?php endif; ?>
        <form action="" id="addLandmark" name = "addLandmark" method = "post" enctype = "multipart/form-data">
			<h1 class="title-text">Добави забележителност</h1>
				<p>   
                    <label for = "name">Име на забележителността</label>  
                    <input id = "name" name = "name" type = "text" value = "<?= (isset($errors['session']['name']) ? $errors['session']['name'] : "" )?>"/>
                    <?php if (isset($errors['name'])) : ?>
                        <p class="errormsg">
                            <?= $errors['name']; ?>
                        </p>
                    <?php endif; ?>  
                </p>                
                <p>   
                    <label for = "review">Кратко описание</label>  
                    <textarea id = "review" name = "review"><?= (isset($errors['session']['review']) ? $errors['session']['review'] : "" )?></textarea>
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
                <p>   
                    <input type = "submit" name = "addLandmark" value = "Добави"/>   
                </p>
				<p class="title-text">
                    <a href = "my_landmarks.php">Назад към моите забележителности</a>
                </p>
        </form>  
	</body>
</html>