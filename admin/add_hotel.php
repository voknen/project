<?php
session_start();
$errors = array();

if (!isset($_SESSION['isLogged'])) {
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>  
 <html>  
     <head>  
        <meta charset = "UTF-8" />  
            <title>Добави</title>  
            <link rel="stylesheet" type="text/css" href="../css/forms.css">  
     </head>  
    <body background="">    
            <?php if ($_SERVER['REQUEST_METHOD'] == 'POST') : ?>

                <?php 
                    require 'admin.php';
                    $admin = new Admin();
                    $errors = $admin->isValidHotel($_POST);
                ?>

                    <?php if (count($errors) == 0) : ?>

                        <?php
                            echo "<strong>Хотелът беше добавен успешно!</strong>";
                        ?>

                    <?php endif; ?>

            <?php endif;?>

            <form name = "addHotel" method = "post">  
                <h1 class="title-text">Добави хотел</h1>    
                    <p>
                        <label for = "city">Град</label>
                        <?php
                            require '../classes/cities.php';
                            $cities = new Cities();
                            echo $cities->citySelector();
                        ?>
                    </p> 
                    <p>
                        <label for="category">Звезди</label>
                        <select name="stars">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </p> 
                    <p>
                        <label for="url">Линк към хотела</label>
                        <input id = "url" name = "url" type = "text" value = "<?= (isset($errors['session']['url']) ? $errors['session']['url'] : "" )?>"/>  
                        <?php if (isset($errors['url'])) : ?>
                            <p class="errormsg">
                                <?= $errors['url']; ?>
                            </p>
                        <?php endif; ?>
                    </p>      
                    <p>   
                        <input type = "submit" name = "addHotel" value = "Добави" />   
  	                </p>
  	                <p class="title-text">
  	                	<a href="index.php">Назад</a>
  	                </p>
            </form>  
        </div>      
    </body>  
</html> 