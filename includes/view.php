<?php
session_start();
 
if (!isset($_SESSION['isLogged']) && !isset($_COOKIE['remember'])) {
  header("Location: ../login.php");
  exit();
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="description" content="Project Description" />
    <meta name="keywords" content="Project Keywords" />
    <title>Забележителностите на България</title>	
    <link href="../css/style.css" rel="stylesheet" type="text/css" />	
	<link href="../css/nivo-slider.css" rel="stylesheet" type="text/css" />
	<link href="../css/forms.css" rel="stylesheet" type="text/css" />
    <!--[if IE]><link href="css/style-ie.css" rel="stylesheet" type="text/css" /><![endif]-->
	<script type="text/javascript" src="../js/jquery-1.7.1.min.js"></script>
	<script type="text/javascript" src="../js/jquery.nivo.slider.js"></script>
	<script type="text/javascript">
		$(window).load(function() {
			$('#slideshow').nivoSlider({
				directionNav: false
			});
		});
	</script>
</head>
	<body>
		<div id="header">
			<div class="container">
				<ul class="menu fr">
					<li><a href="../index.php" title="Home">Начало</a></li>
					<li><a href="../update_profile.php" title="About">Профил</a></li>
					<li><a href = "add.php">Добавете забележителност</a></li>
					<li><a href="all_landmarks.php" title="all">Всички</a></li>
					<li><a href="../contact_form.php" title="Contact Us">Контакт</a></li>
					<li><a href="../logout.php" title="logout">Изход</a></li>
				</ul>
			</div>
		</div>
		<?php
	       	require '../classes/landmarkDB.php';
	       	$landmark = new LandmarkDB();
	       	$id = (int)($_GET['landmark_id']);
	       	$bigImages = $landmark->selectLandmarkAllImages($id, 'big');
	       	$smallImages = $landmark->selectLandmarkAllImages($id, 'small');
	       	$landmarkInfo = $landmark->getLandmarkInfo($id);
	       	$hotels = $landmark->getAllHotels((int)($landmarkInfo->city_id));
	       	$bar_restaurants = $landmark->getAllBarRestaurants((int)($landmarkInfo->city_id));
	    ?>
	    <div id="main">
			<div class="container">
				<div id="slideshow">
					<?php foreach ($bigImages as $image) : ?>
						<?php if (isset($image) && $image !='') : ?>
							<img src="../classes/images/<?php echo $image->image; ?>" width="940" height="380"/>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
				<div class="intro">
					<p><?php echo $landmarkInfo->name; ?></p>
					<p><?php echo $landmarkInfo->city; ?></p>
				</div>
				<table id="view-images">
					<tr>
					<?php foreach ($smallImages as $key => $image) : ?>
						<?php if (isset($image) && $image !='') : ?>
							<td>
								<img src="../classes/images/<?php echo $image->image; ?>" width="200" height="120"/>
							</td>
						<?php endif; ?>
					<?php if (($key + 1) % 4 == 0) : ?>
						</tr><tr>
					<?php endif; ?>
					<?php endforeach; ?>
				</table>
				<p class="text-center title-text">Кратко описание</p>
				<p class="text-center"><?php echo $landmarkInfo->review; ?></p>
				<p class="text-center title-text">Дай своя глас</p>
				<?php 
					require '../classes/ratings.php';
					$isRated = (new Ratings())->isRated((int)$_GET['landmark_id'], $_SESSION['id']);
				?>
				<?php if ($_SERVER['REQUEST_METHOD'] == 'POST') : ?>  
			        <?php $rating = new Ratings(); ?>
			        	<?php if ($isRated == false) : ?>
			            	<?php 
			            		$isRated = $rating->setRatingLandmark($_POST['rateSelector'], $_GET['landmark_id'], $_SESSION['id']); 
			            	?>
			          	<?php endif; ?>
			          	<?php if ($isRated == true) : ?>
			           		<?php 
			           			$rating->updateRatingLandmark($_POST['rateSelector'], (int)($_GET['landmark_id']), (int)($_SESSION['id'])); 
			           		?>
			        	<?php endif; ?>
		        <?php endif; ?>
				<form name = "rate" method = "post" class="form-center">
					<p>
			            <select id = "rateSelector" name = "rateSelector">
				            <option>1</option>
				            <option>2</option>
				            <option>3</option>
				            <option>4</option>
				            <option>5</option>
			            </select>
			        </p>
			        <?php if ($isRated == false) : ?>
				        <p>
			             	<input type = "submit" name = "rate" value = "Гласувай"/>
			            </p>
			        <?php else : ?>
			            <p>
			              <input type = "submit" name = "updateRate" value = "Обнови"/>
			            </p>
			        <?php endif; ?>
				</form>
				<p class="text-center title-text">Среден рейтинг</p>
		        <?php if ((new Ratings())->getAverageRating($_GET['landmark_id']) != 0) : ?>
		            <p class="text-center"><?php echo (new Ratings())->getAverageRating($_GET['landmark_id']) . "/5.0";?></p>
		        <?php else : ?>
		            <p class="text-center"><?php echo "0.0/5.0"; ?></p>
		        <?php endif; ?>
				<p class="text-center title-text">Преглед на места за отдих и забавление</p>
				<p class="title-text">Барове / Ресторанти / Дискотеки</p>
				<?php if (!empty($bar_restaurants)) : ?>
					<?php foreach ($bar_restaurants as $place) : ?>
						<p>
							<a href="<?php echo $place->review; ?>" target="_blank">Виж</a>
							<?php if ($place->type == "RESTAURANT") : ?>
								Ресторант
							<?php elseif ($place->type == "BAR") : ?>
								Бар
							<?php elseif ($place->type == "DISCO") : ?>
								Дискотека
							<?php endif; ?>
						</p>
					<?php endforeach; ?>
				<?php endif; ?>
				<p class="title-text">Хотели</p>
				<?php if (!empty($hotels)) : ?>
					<?php foreach ($hotels as $hotel) : ?>
						<p>
							<a href="<?php echo $hotel->review;?>" target="_blank">Виж хотел</a><?php echo " " . $hotel->category;?>*
						</p>
					<?php endforeach; ?>
					<?php else : ?>
						<p>Няма добавени хотели за това селище. За повече информация се обърнете към администратора.</p>
				<?php endif;?>
			</div>
		</div>
	</body>
</html>