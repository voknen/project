<?php 
session_set_cookie_params(0);
session_start();

unset($_SESSION['isLogged']);
unset($_COOKIE['remember']);
setcookie("remember", '', time() - 3600);

session_destroy();

echo "<meta charset='UTF-8' http-equiv='refresh' content='2;URL=login.php'/>Вие излязохте!";
