<?php
session_start();
$errors = array();

if (isset($_SESSION['isLogged']) || isset($_COOKIE['remember'])) {
    header("Location: index.php");
    exit();
}

?>

<!DOCTYPE html>  
 <html>  
     <head>  
        <meta charset = "UTF-8" />  
            <title>Вход</title>  
            <link rel="stylesheet" type="text/css" href="css/forms.css">  
     </head>  
    <body background="images/background/back_log.jpg">    
            <?php if ($_SERVER['REQUEST_METHOD'] == 'POST') : ?>

                <?php 
                    require 'classes/usersDB.php';
                    $user = new UserDB();
                    $errors = $user->login($_POST);
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
                        <label for = "username">Потребителско име</label>  
                        <input id = "username" name = "userName" type = "text" value = "<?= (isset($errors['session']['userName']) ? $errors['session']['userName'] : "" )?>"/>  
                        <?php if (isset($errors['userName'])) : ?>
                            <p class="errormsg">
                                <?= $errors['userName']; ?>
                            </p>
                        <?php endif; ?> 
                    </p>  
                                    
                    <p>   
                        <label for = "password">Парола</label>  
                        <input id = "password" name = "password" type = "password" value = "<?= (isset($errors['session']['password']) ? $errors['session']['password'] : "" )?>"/>
                        <?php if (isset($errors['password'])) : ?>
                            <p class="errormsg">
                                <?= $errors['password']; ?>
                            </p>
                        <?php endif; ?>   
                    </p>  

                    <p>   
                        <label for = "remember">Запомни ме</label> 
                        <input id = "remember" name = "remember" type = "checkbox" value = "yes" />   
                    </p>  
                                    
                    <p>   
                        <input type = "submit" name = "login" value = "Влез" />   
                    </p>  
                                    
                    <p class="title-text">  
                        Все още нямате акаунт ?<br><br>  
                        <a href = "register.php">Регистрация</a>  
                    </p>  
            </form>  
        </div>      
    </body>  
</html>  