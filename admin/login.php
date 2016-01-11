<?php
session_start();
$errors = array();

if (isset($_SESSION['isLogged'])) {
    header("Location: index.php");
    exit();
}

?>

<!DOCTYPE html>  
 <html>  
     <head>  
        <meta charset = "UTF-8" />  
            <title>Вход</title>  
            <link rel="stylesheet" type="text/css" href="../css/forms.css">  
     </head>  
    <body background="../images/background/back_log.jpg">    
            <?php if ($_SERVER['REQUEST_METHOD'] == 'POST') : ?>

                <?php 
                    require 'admin.php';
                    $admin = new Admin();
                    $errors = $admin->isValidAdmin($_POST);
                ?>

                    <?php if (count($errors) == 0) : ?>

                        <?php
                            header("Location: index.php");
                            exit();
                        ?>

                    <?php endif; ?>

            <?php endif;?>

            <form name = "login" method = "post">  
                <h1 class="title-text">Вход</h1>   
                    <p>   
                        <label for = "username">Админ</label>  
                        <input id = "username" name = "userName" type = "text" value = ""/>  
                        <?php if (isset($errors['userName'])) : ?>
                            <p class="errormsg">
                                <?= $errors['userName']; ?>
                            </p>
                        <?php endif; ?> 
                    </p>  
                                    
                    <p>   
                        <label for = "password">Парола</label>  
                        <input id = "password" name = "password" type = "password" value = ""/>
                        <?php if (isset($errors['password'])) : ?>
                            <p class="errormsg">
                                <?= $errors['password']; ?>
                            </p>
                        <?php endif; ?>   
                    </p>            
                    <p>   
                        <input type = "submit" name = "login" value = "Влез" />   
                    </p>  
            </form>  
        </div>      
    </body>  
</html>  