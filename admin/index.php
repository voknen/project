<?php
session_start();

if (!isset($_SESSION['isLogged'])) {
    header("Location: login.php");
    exit();
}

?>

<html>
<head>
	<title>Админ</title>
	<meta charset = "UTF-8" />  
	<link rel="stylesheet" type="text/css" href="../css/forms.css">  
</head>
<body>
	<a class="title-text" href="add_hotel.php">Добави хотел</a><br/><br/>
	<a class="title-text" href="add_city.php">Добави град</a><br/><br/>
	<a class="title-text" href="add_bar.php">Добави бар/ресторант/диско</a><br/><br/>
	<a class="title-text" href="logout.php">Изход</a>
</body>
</html>