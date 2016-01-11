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
                    $errors = $admin->isValidCity($_POST);
                ?>

                    <?php if (count($errors) == 0) : ?>

                        <?php
                            echo "<strong>Градът беше добавен успешно!</strong>";
                        ?>

                    <?php endif; ?>

            <?php endif;?>

            <form name = "addCity" method = "post">  
                <h1 class="title-text">Добави град</h1>   
                    <p>   
                        <label for = "city">Име на град</label>  
                        <input id = "city" name = "city" type = "text" value = "<?= (isset($errors['session']['city']) ? $errors['session']['city'] : "" )?>"/>  
                        <?php if (isset($errors['city'])) : ?>
                            <p class="errormsg">
                                <?= $errors['city']; ?>
                            </p>
                        <?php endif; ?> 
                    </p>            
                    <p>   
                        <input type = "submit" name = "addCity" value = "Добави" />   
  	                </p>
  	                <p class="title-text">
  	                	<a href="index.php">Назад</a>
  	                </p>
            </form>  
        </div>      
    </body>  
</html>  