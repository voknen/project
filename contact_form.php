<?php

error_reporting(-1);
ini_set('display_errors', 'On');
set_error_handler("var_dump");

session_start();

if (!isset($_SESSION['isLogged']) && !isset($_COOKIE['remember'])) {
	header("Location: login.php");
	exit();
}

?>

<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<title>Контакти</title>
		<link rel="stylesheet" type="text/css" href="css/forms.css">
	</head>
		<body background="images/background/back.jpg">
			<p class="title-text info">За контакти</p>
			<table class="contact-table">
				<tbody>
					<tr>
						<td>Име</td>
						<td>Иван</td>
					</tr>
					<tr>
						<td>Фамилия</td>
						<td>Ненков</td>
					</tr>
					<tr>
						<td>Имейл</td>
						<td><a href="mailto:voknen@abv.bg">voknen@abv.bg</a></td>
					</tr>
				</tbody>
			</table>
            <p class="info">
            	<a class="title-text" href="index.php">Назад към началната страница</a>
            </p>
		</body>
</html>