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
                    $errors = $admin->isValidBar($_POST);
                ?>

                    <?php if (count($errors) == 0) : ?>

                        <?php
                            echo "<strong>Данните са добавени успешно!</strong>";
                        ?>

                    <?php endif; ?>

            <?php endif;?>

            <form name = "addBar" method = "post">  
                <h1 class="title-text">Добави бар/ресторант/диско</h1>    
                    <p>
                        <label for = "city">Град</label>
                        <?php
                            require '../classes/cities.php';
                            $cities = new Cities();
                            echo $cities->citySelector();
                        ?>
                    </p> 
                    <p>
                        <label for="type">Вид</label>
                        <select name="types">
                            <option value="BAR">Бар</option>
                            <option value="RESTAURANT">Ресторант</option>
                            <option value="DISCO">Диско</option>
                        </select>
                    </p> 
                    <p>
                        <label for="url">Линк към заведението</label>
                        <input id = "url" name = "url" type = "text" value = "<?= (isset($errors['session']['url']) ? $errors['session']['url'] : "" )?>"/>  
                        <?php if (isset($errors['url'])) : ?>
                            <p class="errormsg">
                                <?= $errors['url']; ?>
                            </p>
                        <?php endif; ?>
                    </p>      
                    <p>   
                        <input type = "submit" name = "addBar" value = "Добави" />   
  	                </p>
  	                <p class="title-text">
  	                	<a href="index.php">Назад</a>
  	                </p>
            </form>  
        </div>      
    </body>  
</html> 