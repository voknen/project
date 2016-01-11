<?php

session_start();

if (!isset($_SESSION['isLogged']) && !isset($_COOKIE['remember'])) {
	header("Location: ../login.php");
	exit();
}

require '../classes/landmarkDB.php';
$landmark = new LandmarkDB();
$landmarkInfo = new Landmarks();
$checkedGenres = array();
?>

<!DOCTYPE html>
<html>
	<head>
		<title></title>
        <meta charset = "UTF-8" />  
        <link rel="stylesheet" type="text/css" href="css/forms.css">   
	</head>
	<body>
		<?php if ($_SERVER['REQUEST_METHOD'] == 'POST') : ?>

            <?php if ($_GET['option'] == "add") : ?>

                <?php 
                    $errorsMovie = $movie->validMovie($_POST);
                    $movieInfo = $errorsMovie[1];
                    $checkedGenres = $errorsMovie[2];     
                ?>

                <?php if (count($errorsMovie[0]) == 0) : ?>

                    <?php echo "<strong>Данните са добавени успешно!</strong>!"; ?>

                <?php endif; ?>

            <?php endif; ?>

            <?php if ($_GET['option'] == "edit") : ?>

                <?php 
                    $errorsMovie = $movie->validMovie($_POST);
                    $movieInfo = $errorsMovie[1];
                    $checkedGenres = $errorsMovie[2];
                ?>

                 <?php if (count($errorsMovie[0]) == 0) : ?>

                    <?php echo "Your movie has been successfully updated !"; ?>

                <?php endif; ?>

            <?php endif; ?>

        <?php endif; ?>

		<div>  

            <form action="" name = "addMovie" method = "post" enctype = "multipart/form-data">

                <?php if ($_GET['option'] == "add") :?>
                    
                    <h1>Add movie</h1>
               
                <?php elseif ($_GET['option'] == "edit") :?>
 
                    <?php if (is_numeric($_GET['movie_id'])):?>

                        <?php $movieInfo = $movie->select((int)$_GET['movie_id']); ?>
                        <?php $movieGenres = $movie->getMovieGenreId($_GET['movie_id']);?>
                        <?php foreach ($movieGenres as $genre) :?>
                            <?php foreach ($genre as $value):?>
                            <?php $checkedGenres[] = $value; ?>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <h1>Update movie</h1>
                
                <?php else :?>
                
                    <?php echo "Ne se opitvai da hakvash eiii";die; ?>
                
                <?php endif; ?>   
                 
                    <p>   
                        <label for = "name">Movie name</label>  
                        <input id = "name" name = "name" type = "text" value = "<?php if ($_GET['option'] == "edit") echo $movieInfo->name;else echo $movieInfo->getName();?>"/>
                        <?php if (isset($errorsMovie[0]['nameOfMovie'])) : ?>
                            <p>
                                <?= $errorsMovie[0]['nameOfMovie']; ?>
                            </p>
                        <?php endif; ?>  
                    </p>  


                                
                    <p>   
                        <label for = "director">Director</label>  
                        <input id = "director" name = "director" type = "text" value = "<?php if ($_GET['option'] == "edit") echo $movieInfo->director;else echo $movieInfo->getDirector();?>"/>
                        <?php if (isset($errorsMovie[0]['director'])) : ?>
                            <p>
                                <?= $errorsMovie[0]['director']; ?>
                            </p>
                        <?php endif; ?>   
                    </p>

                    <p>   
                        <label for = "cast">Cast</label>  
                        <textarea id = "cast" name = "cast">
                        <?php if ($_GET['option'] == "edit") echo $movieInfo->cast;else echo $movieInfo->getCast()?>
                        </textarea>
                        <?php if (isset($errorsMovie[0]['cast'])) : ?>
                            <p>
                                <?= $errorsMovie[0]['cast']; ?>
                            </p>
                        <?php endif; ?>  
                    </p>

                    <p>   
                        <label for = "year">Year of release</label>  
                        <input id = "year" name = "year" value = "<?php if ($_GET['option'] == "edit") echo $movieInfo->year;else echo $movieInfo->getYear();?>"/>
                        <?php if (isset($errorsMovie[0]['year'])) : ?>
                            <p>
                                <?= $errorsMovie[0]['year']; ?>
                            </p>
                        <?php endif; ?>  
                    </p>

                    <p>   
                        <label for = "review">Review</label>  
                        <textarea id = "review" name = "review">
                        <?php if ($_GET['option'] == "edit") echo $movieInfo->review;else echo $movieInfo->getReview();?>
                        </textarea>
                        <?php if (isset($errorsMovie[0]['review'])) : ?>
                            <p>
                                <?= $errorsMovie[0]['review']; ?>
                            </p>
                        <?php endif; ?>  
                    </p>

                    <p>   
                        <label for = "trailerUrl">Trailer</label>  
                        <input id = "trailerUrl" name = "trailerUrl" value = "<?php if ($_GET['option'] == "edit") echo $movieInfo->trailer_url;else echo $movieInfo->getTrailerUrl();?>"/>
                        <?php if (isset($errorsMovie[0]['trailerUrl'])) : ?>
                            <p>
                                <?= $errorsMovie[0]['trailerUrl']; ?>
                            </p>
                        <?php endif; ?>  
                    </p>

                    <p>
                    	<label for = "genre">Genre</label>
                    	<?php
                    		require '../classes/genres.php';
                    		$genres = new Genres();
                    		$allGenresArray = $genres->getAllGenres();
                    	?>
                        <?php foreach ($allGenresArray as $genreArray) : ?>
                                 <?php 
                                    $checked = in_array($genreArray->id, $checkedGenres) ? 'checked="checked"' : '';
                                ?>               
                                <input type="checkbox" <?php echo $checked;?> name="genre[<?php echo $genreArray->id; ?>]" value="<?php echo $genreArray->id; ?>" /><?php echo $genreArray->genre; ?>
                        <?php endforeach; ?>
                    	<?php if (isset($errorsMovie[0]['genre'])) : ?>
						 	<p>
						 		<?= $errorsMovie[0]['genre']; ?>
						 	</p>
						<?php endif; ?>
                    </p>

                    <p>

                        <label for="imageToUpload">Avatar: </label>
                         <input type="file" name="imageToUpload" id="imageToUpload" >
                         <?php if (isset($errorsMovie[0]['imageToUpload'])) : ?>
                            <p>
                                <?= $errorsMovie[0]['imageToUpload']; ?>
                            </p>
                        <?php endif; ?>

                    </p>

                    <?php if ($_GET['option'] == "add") :?> 

                    <p>   
                        <input type = "submit" name = "addMovie" value = "Add"/>   
                    </p>

                    <?php elseif ($_GET['option'] == "edit") :?> 

                    <p>   
                        <input type = "submit" name = "editMovie" value = "Update"/>   
                    </p>

                    <?php endif; ?>

                    <p>
                        <a href = "my_movies.php">Back</a>
                    </p>
            </form>  
        </div>
	</body>
</html>