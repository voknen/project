<?php
session_start();

if (!isset($_SESSION['isLogged']) && !isset($_COOKIE['remember'])) {
	header("Location: login.php");
	exit();
}

require 'classes/usersDB.php';

$userInfo = (new UserDB())->selectUserData((int)$_SESSION['id']);

?>

<!DOCTYPE html>  
 <html>  
    <head>  
        <meta charset = "UTF-8" />  
        <title>Профил</title>   
        <link rel="stylesheet" type="text/css" href="css/forms.css">   
    </head>  
    <body background="images/background/back_update.jpg">  
        <?php if ($_SERVER['REQUEST_METHOD'] == 'POST') : ?>

            <?php
                $user = new UserDB();
                $errors = $user->isValidUpdateUser($_POST);
                $userInfo = (new UserDB())->selectUserData((int)$_SESSION['id']);
            ?>

                <?php if (count($errors) == 0) : ?>

                    <?php echo "<strong>Успешно променихте профила си!</strong>"; ?>
                    
                <?php else : ?>
                    <?= isset($errors['same_data']) ? "<strong>" . $errors['same_data'] . "</strong>" : ""; ?>
                <?php endif; ?>
        <?php endif; ?>

        <div>  
            <form name = "register" method = "post">  
                <h1 class="title-text">Профил</h1>   
                    <p>   
                        <label for = "firstName">Име</label>  
                        <input id = "firstname" name = "firstName" type = "text" value = "<?= $userInfo->first_name; ?>"/>
                        <?php if (isset($errors['firstName'])) : ?>
                            <p class="errormsg">
                                <?= $errors['firstName']; ?>
                            </p>
                        <?php endif; ?>  
                    </p>  
                                
                    <p>   
                        <label for = "lastName">Фамилия</label>  
                        <input id = "lastname" name = "lastName" type = "text" value = "<?= $userInfo->last_name; ?>"/>
                        <?php if (isset($errors['lastName'])) : ?>
                            <p class="errormsg">
                                <?= $errors['lastName']; ?>
                            </p>
                        <?php endif; ?>   
                    </p>

                    <p>   
                        <label for = "userName">Потребителско име</label>  
                        <input class="disabled-input" id = "username" name = "userName" type = "text" value = "<?= $userInfo->username;?>" disabled/>
                        <?php if (isset($errors['userName'])) : ?>
                            <p class="errormsg">
                                <?= $errors['userName']; ?>
                            </p>
                        <?php endif; ?>  
                    </p>

                    <p>   
                        <label for = "password">Нова Парола</label>  
                        <input id = "password" name = "password" type = "password" value = ""/>
                        <?php if (isset($errors['password'])) : ?>
                            <p class="errormsg">
                                <?= $errors['password']; ?>
                            </p>
                        <?php endif; ?>  
                    </p>

                    <p>   
                        <label for = "email">Имейл адрес</label>  
                        <input class="disabled-input" id = "email" name = "email" type = "text" value = "<?= $userInfo->email; ?>" disabled/>
                        <?php if (isset($errors['email'])) : ?>
                            <p class="errormsg">
                                <?= $errors['email']; ?>
                            </p>
                        <?php endif; ?>  
                    </p>

                    <p>   
                        <input type = "submit" name = "register" value = "Промени"/>   
                    </p>

                    <p>
                    	<a class="title-text" href="index.php">Назад към началната страница</a>
                    </p>
            </form>  
        </div>    
    </body>  
</html>  