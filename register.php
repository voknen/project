<?php
session_start();
require 'classes/usersDB.php';

if (isset($_SESSION['isLogged'])) {
    header("Location: index.php");
    exit();
}

$errors = array();

?>

<!DOCTYPE html>  
 <html>  
    <head>  
        <meta charset = "UTF-8" />  
        <title>Регистрация</title>   
        <link rel="stylesheet" type="text/css" href="css/forms.css">   
    </head>  
    <body background="images/background/back_reg.jpg">  

        <?php if ($_SERVER['REQUEST_METHOD'] == 'POST') : ?>

            <?php
                $user = new UserDB();
                $errors = $user->register($_POST);
            ?>

                <?php if (count($errors) == 0) : ?>

                    <?php echo "<strong>Вашата регистрация е успешна!</strong>"; ?>
                
                <?php endif; ?>
        <?php endif; ?>

        <div>  
            <form name = "register" method = "post">  
                <h1 class="title-text">Регистрация</h1>   
                    <p>   
                        <label for = "firstName">Име</label>  
                        <input id = "firstname" name = "firstName" type = "text" value = "<?= (isset($errors['session']['firstName']) ? $errors['session']['firstName'] : "" )?>"/>
                        <?php if (isset($errors['firstName'])) : ?>
                            <p class="errormsg">
                                <?= $errors['firstName']; ?>
                            </p>
                        <?php endif; ?>  
                    </p>  
                                
                    <p>   
                        <label for = "lastName">Фамилия</label>  
                        <input id = "lastname" name = "lastName" type = "text" value = "<?= (isset($errors['session']['lastName']) ? $errors['session']['lastName'] : "" )?>"/>
                        <?php if (isset($errors['lastName'])) : ?>
                            <p class="errormsg">
                                <?= $errors['lastName']; ?>
                            </p>
                        <?php endif; ?>   
                    </p>

                    <p>   
                        <label for = "userName">Потребителско име</label>  
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
                        <label for = "confirmedPassword">Потвърди парола</label>  
                        <input id = "confirmedPassword" name = "confirmedPassword" type = "password" value = "<?= (isset($errors['session']['confirmedPassword']) ? $errors['session']['confirmedPassword'] : "" )?>"/>
                        <?php if (isset($errors['confirmedPassword'])) : ?>
                            <p class="errormsg">
                                <?= $errors['confirmedPassword']; ?>
                            </p>
                        <?php endif; ?>  
                    </p>

                    <p>   
                        <label for = "email">Имейл адрес</label>  
                        <input id = "email" name = "email" type = "text" value = "<?= (isset($errors['session']['email']) ? $errors['session']['email'] : "" )?>"/>
                        <?php if (isset($errors['email'])) : ?>
                            <p class="errormsg">
                                <?= $errors['email']; ?>
                            </p>
                        <?php endif; ?>  
                    </p>

                    <p>   
                        <input type = "submit" name = "register" value = "Регистрирай"/>   
                    </p> 

                    <p class="title-text">  
                       Вече сте регистриран ?<br><br>  
                        <a href = "login.php">Вход</a>  
                    </p>
            </form>  
        </div>    
    </body>  
</html>  