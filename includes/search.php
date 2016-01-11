<?php
session_start();

if (!isset($_SESSION['isLogged']) && !isset($_COOKIE['remember'])) {
	header("Location: ../login.php");
	exit();
}

?>

<!DOCTYPE html>  
 <html>  
     <head>  
        <meta charset = "UTF-8" />  
            <title>Търсене</title>  
            <link rel="stylesheet" type="text/css" href="../css/forms.css">  
     </head>  
    <body background="">    
            <?php if ($_SERVER['REQUEST_METHOD'] == 'POST') : ?>
            	<?php
            		require '../classes/landmarkDB.php';
            		$landmark = new LandmarkDB();
            		$searchResult = $landmark->searchLandmarkByCity($_POST['city']);
            	?>
            <?php endif;?>

            <form name = "search" method = "post">  
                <h1 class="title-text">Намери забележителност</h1>   
                    <p>   
                        <label for = "city">Име на град</label>  
                        <?php
                            require '../classes/cities.php';
                            $cities = new Cities();
                            echo $cities->citySelector();
                        ?>                          
                    </p>            
                    <p>   
                        <input type = "submit" name = "search" value = "Намери" />   
  	                </p>
  	                <?php if (isset($searchResult) && !empty($searchResult)) : ?>
  	                	<?php foreach ($searchResult as $result) : ?>
  	                		<p>
                                <a href="view.php?landmark_id=<?php echo $result->id; ?>"><?php echo $result->name; ?></a>
  	                	    </p>
                        <?php endforeach;?>
  	                <?php elseif (isset($searchResult) && empty($searchResult)) : ?>
  	                	<p>Няма намерени резултати</p>
  	               	<?php endif; ?>
  	                <p class="title-text">
  	                	<a href="all_landmarks.php">Назад</a>
  	                </p>
            </form>  
        </div>      
    </body>  
</html>  