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
	<link href="../css/forms.css" rel="stylesheet" type="text/css"/> 
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
					<li><a href = "my_landmarks.php">Моите забележителности</a></li>
					<li><a href="search.php" title="search">Търсене</a></li>
					<li><a href="../contact_form.php" title="contact">Контакт</a></li>
					<li><a href="../logout.php" title="logout">Изход</a></li>
				</ul>
			</div>
		</div><!-- // end #header -->
        <?php
	       	require '../classes/landmarkDB.php';
	       	require '../classes/pagination.php';
	       	require '../home.php';
	       	$landmarks = new LandmarkDB();
	       	$limit = 4;
	       	$pagination = new Pagination();
	       	$offset = $pagination->getOffset($limit);
	       	$allLandmarks = $landmarks->getAllLandmarks($limit, $offset);
	       	$ids = array();
			foreach ($allLandmarks as $value) {
				$ids[] = (int)($value->id);
			}
	       	$allLandmarkImages = $landmarks->selectLandmarkItemsImages($ids, 'small');
	       	$bigImages = $landmarks->selectLandmarkItemsImages($ids, 'big');
       	?>
       	<?php if (count($allLandmarks) > 0) : ?>
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
					<p>Всички забележителности</p>
				</div>
		       	<div id="about" class="clearfix">
			       	<?php foreach ($allLandmarks as $a => $landmark) : ?>
			       		<?php if (($a+1) % 4 !=0 ) : ?>
							<div class="column">
						<?php else : ?>
							<div class="column last">
						<?php endif; ?>
								<h2>
									<a href = "view.php?landmark_id=<?php echo $landmark->id;?>" target="_blank">
										<?php echo $landmark->name; ?>
									</a>
								</h2>
								<div class="image">
									<?php foreach ($allLandmarkImages as $key => $image) : ?>
										<?php if ($key == $landmark->id) : ?>
											<?php if (isset($image) && $image !='') : ?>
												<a href = "view.php?landmark_id=<?php echo $landmark->id;?>" target="_blank">
													<img src="../classes/images/<?php echo $image->image; ?>" width="200" height="120"/>
												</a>
											<?php endif; ?>
										<?php endif; ?>
									<?php endforeach; ?>
								</div>
								<p><?php echo (new Home())->softTrim($landmark->review); ?></p>
							</div>
			   		<?php endforeach;?>
			   	</div>
			</div>
		
			<div class="pagination">
				<ul>
				<?php
					$numberOfLandmarks = $landmarks->numberOfAllLandmarks();
					$pagination->pagingLink($numberOfLandmarks, $limit);
				?>
				</ul>
			</div>
		</div>
		<?php else : ?>
			    <div id="main">
					<div class="container">
						<div class="intro">
							<p>Няма забележителности</p>
						</div>
					</div>
				</div>		
		<?php endif; ?>
			
	</body>
</html>