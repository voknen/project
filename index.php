<?php
session_start();

if (!isset($_SESSION['isLogged']) && !isset($_COOKIE['remember'])) {
	header("Location: login.php");
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
    <link href="css/style.css" rel="stylesheet" type="text/css" />	
	<link href="css/nivo-slider.css" rel="stylesheet" type="text/css" />
    <!--[if IE]><link href="css/style-ie.css" rel="stylesheet" type="text/css" /><![endif]-->
	<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
	<script type="text/javascript" src="js/jquery.nivo.slider.js"></script>
	<script type="text/javascript">
		$(window).load(function() {
			$('#slideshow').nivoSlider({
				directionNav: false
			});
		});
	</script>
</head>

<body>

<?php
	require 'home.php';
	$mostRatedLandmarks = (new Home())->selectLandmarkHomepageItems();
	$mostRatedLandmarksBigImages = (new Home())->selectLandmarkHomepageItemsImages("big");
	$mostRatedLandmarksSmallImages = (new Home())->selectLandmarkHomepageItemsImages("small");
	//var_dump($mostRatedLandmarksSmallImages);die;
 ?>
<div id="header">
	<div class="container">
		<h1>
			<a href="index.php" title="landmarks">Забележителностите на България</a>
		</h1>
		<ul class="menu fr">
			<li class="active"><a href="index.php" title="Home">Начало</a></li>
			<li><a href="update_profile.php" title="About">Профил</a></li>
			<li><a href="includes/my_landmarks.php" title="my">Моите забележителности</a></li>
			<li><a href="includes/all_landmarks.php" title="all">Всички</a></li>
			<li><a href="contact_form.php" title="Contact Us">Контакт</a></li>
			<li><a href="logout.php" title="logout">Изход</a></li>
		</ul>
	</div>
</div><!-- // end #header -->

<div id="main">
	<div class="container">
		<div id="slideshow">
			<?php foreach ($mostRatedLandmarksBigImages as $image) : ?>
				<?php if (isset($image) && $image !='') : ?>
					<img src="classes/images/<?php echo $image->image; ?>" width="940" height="380"/>
				<?php endif ?>
			<?php endforeach; ?>
		</div><!-- // end #slideshow -->
		<div class="intro">
			<p>Най-нови</p>
		</div><!-- // end .intro -->
		<div id="about" class="clearfix">
			<?php foreach ($mostRatedLandmarks as $a => $landmark) : ?> 
				<?php if (($a+1) % 4 !=0 ) : ?>
					<div class="column">
				<?php else : ?>
					<div class="column last">
				<?php endif; ?>
						<h2>
							<a href = "includes/view.php?landmark_id=<?php echo $landmark->id;?>" target="_blank">
								<?php echo $landmark->name; ?>
							</a>		
						</h2>
						<div class="image">
							<?php foreach ($mostRatedLandmarksSmallImages as $key => $image) : ?>
								<?php if ($key == $landmark->id) : ?>
									<?php if (isset($image) && $image !='') : ?>
										<a href = "includes/view.php?landmark_id=<?php echo $landmark->id;?>" target="_blank">
											<img src="classes/images/<?php echo $image->image; ?>" width="200" height="120"/>
										</a>
									<?php endif ?>
								<?php endif; ?>
							<?php endforeach; ?>
						</div>
						<p><?php echo (new Home())->softTrim($landmark->review); ?></p>
					</div>
			<?php endforeach; ?>
		</div><!-- // end #articles -->
	</div>
</div><!-- // end #main -->

<div id="footer">
	<div class="container">
		<ul class="menu fl">
			<li class="active"><a href="index.php" title="Home">Начало</a></li>
			<li><a href="update_profile.php" title="About">Профил</a></li>
			<li><a href="includes/my_landmarks.php" title="my">Моите забележителности</a></li>
			<li><a href="includes/all_landmarks.php" title="all">Всички</a></li>
			<li><a href="contact_form.php" title="Contact Us">Контакт</a></li>
			<li><a href="logout.php" title="logout">Изход</a></li>
		</ul>
	</div>
</div><!-- // end #fppter -->
	
	
</body>
</html>