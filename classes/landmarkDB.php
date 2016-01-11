<?php
require '../database_connection/config.php';
require BASE_PATH . '\interfaces\idatabase_procedures.php';
require BASE_PATH . '\database_connection\database_connect.php';
require 'landmarks.php';

/**
* Main LandmarkDB model 
*/
class LandmarkDB extends Landmarks implements CommonInterface
{	
	/**
	* Add to the database the landmark object which is sent
	*
	* @param array $query The data for the landmark 
	*
	* @return int Returns the id of the last inserted landmark
	*/
	public function add($query)
	{

		$userId = $_SESSION['id'];//the id of the logged in user

		$name = trim($query['name']);
		$review = trim($query['review']);
		$cityId = (int)($query['city']);
		$status = 1;

		$databaseConnect = new DatabaseConnect();
		$connection = $databaseConnect->connect();
		$stmt = $connection->query("SET NAMES 'utf8';");
		$stmt = $connection->prepare("INSERT INTO landmarks (user_id, city_id, name, review, status) VALUES (:userId, :cityId, :name, :review,:status);");
		
		$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
		$stmt->bindParam(':name', $name, PDO::PARAM_STR);
		$stmt->bindParam(':cityId', $cityId, PDO::PARAM_INT);
		$stmt->bindParam(':review', $review, PDO::PARAM_STR);
		$stmt->bindParam(':status', $status, PDO::PARAM_INT);

		$stmt->execute();

		return $connection->lastInsertId();
	}

	/**
	* Select the movie with the given movie_id from the database
	*
	* @param int $query is the id of the movie
	*
	* @return array with object 
	*/
	public function select($query)
	{
		require '../database_connection/database_connect.php';
		$databaseConnection = new DatabaseConnect();
	    $connection = $databaseConnection->connect();
	    $userId = $_SESSION['id'];// the id of the logged in user
	    $stmt = $connection->query("SET NAMES 'utf8';");
	    $stmt = $connection->prepare("SELECT city_id, name, review FROM landmarks WHERE status = true AND id = :landmarkId AND user_id = :userId;");
	    $stmt->bindParam(':landmarkId', $query, PDO::PARAM_INT);
	    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
	    $stmt->execute();

	    return $stmt->fetch(PDO::FETCH_OBJ);
	}

	/**
	* Update the landmark with the data which is sent from the $_POST method
	*
	* @param array $query is the data from the $_POST method
	*/
	public function update($query)
	{
		$landmark = new Landmarks();
		$landmark->exchangeLandmark($query);

		$userId = (int)($_SESSION['id']);// the id of the user who is logged in 
		$landmarkId = (int)($_GET['landmark_id']);// the id of the landmark which we are updating
		$name = trim($landmark->getName());		
		$review = trim($landmark->getReview());
		$cityId = (int)($query['city']);

		$databaseConnect = new DatabaseConnect();
		$connection = $databaseConnect->connect();
		$stmt = $connection->query("SET NAMES 'utf8';");
		$stmt = $connection->prepare("UPDATE landmarks SET name = :name, review = :review, city_id = :cityId WHERE id = :landmarkId AND user_id = :userId AND status = true;");
		$stmt->bindParam(':name', $name, PDO::PARAM_STR);
		$stmt->bindParam(':review', $review, PDO::PARAM_STR);
		$stmt->bindParam(':landmarkId', $landmarkId, PDO::PARAM_INT);
		$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
		$stmt->bindParam(':cityId', $cityId, PDO::PARAM_INT);

		$stmt->execute();
	}

	/**
	* Set the status of the landmark to false 
	*
	* @param int $query is the id of the landmark which will be updated 
	*/
	public function delete($query)
	{
		$id = (int)($query);
		require '../database_connection/database_connect.php';
		$databaseConnection = new DatabaseConnect();
	    $connection = $databaseConnection->connect();

	    $stmt = $connection->prepare("UPDATE landmarks SET status = false WHERE id = :id;");
	    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	    $stmt->execute();
	}

	/**
	* Check if the data for the landmark which is sent from the $_POST method is valid
	*
	* @param array $query is the data which is sent from the $_POST method 
	*
	* @return array The errors if there are 
	*/
	public function validLandmark($query)
	{
		$errors = array();//array for the errors
		$landmark = new Landmarks();
		$landmark->exchangeLandmark($query);

		$name = trim($landmark->getName());
		$review = trim($landmark->getReview());
		$cityId = $query['city'];
		$bigImage = $_FILES['big']['name'];//the name of the big image
		$bigImageName = time() . "_" . $bigImage; //make the name of the big image unique
		$smallImage = $_FILES['small']['name'];//the name of the small image
		$smallImageName = time() . "_" . $smallImage; //make the name of the small image unique
		
		$dataImages['big'] = $bigImageName;
		$dataImages['small'] = $smallImageName;

		$session_data ['name'] = $name;
		$session_data['review'] = $review;
		$errors['session'] = $session_data;

		//check if the name field of the landmark is empty
		if (empty($name)) {
			$errors['name'] = "Моля, въведете име на забележителността!";
		}

		//check if the review field is empty
		if (empty($review)) {
			$errors['review'] = "Моля, въведете кратко описание!";
		}

		if (empty($_FILES['big']['name'])) {
			$errors['big'] = "Моля, прикачете голяма снимка!";
		}	

		if (empty($_FILES['small']['name'])) {
			$errors['small'] = "Моля, прикачете малка снимка!";
		}	

		//check if the field for the big image uploading is empty
		if (!empty($_FILES['small']['name'])) {
			//check if the selected directory is created 	
			if (!file_exists(dirname(__FILE__) . '/images/')) {
	            mkdir(dirname(__FILE__) . '/images/', 0755, true);
	        }

			$target_dir = dirname(__FILE__) . '/images/';
			$target_file = $target_dir . basename($smallImageName);
			$fileType = pathinfo($target_file, PATHINFO_EXTENSION);//get the file extension

			//check if have no errors for the image
			if (!$_FILES['small']['error']) {	
				$valid_file = true;
				//check if the selected image is the correct file extension
				if ($fileType != "png" && $fileType != "jpg" && $fileType != "jpeg") {
	    			$errors['small'] = "Единствено файлове с разширение .png, .jpg , .jpeg е позволено да качвате!";	
	    			$valid_file = false;
	    		}
	    		//check if the image size is correct
				if ($_FILES['small']['size'] > (3072000)) {
					$valid_file = false;
					$errors['small'] = 'Вашият файл е твърде голям!';
				}
				//check if the image is correct 
				if ($valid_file) {
					move_uploaded_file($_FILES['small']['tmp_name'], $target_file);//move the uploaded image to the correct directory
				}
			} else {
				$errors['small'] = 'Съжаляваме, но възникна грешка' . $_FILES['small']['error'];
			}
		}
		//check if the field for the big image uploading is empty
		if (!empty($_FILES['big']['name'])) {
			//check if the selected directory is created 	
			if (!file_exists(dirname(__FILE__) . '/images/')) {
	            mkdir(dirname(__FILE__) . '/images/', 0755, true);
	        }

			$target_dir = dirname(__FILE__) . '/images/';
			$target_file = $target_dir . basename($bigImageName);
			$fileType = pathinfo($target_file, PATHINFO_EXTENSION);//get the file extension

			//check if have no errors for the image
			if (!$_FILES['big']['error']) {	
				$valid_file = true;
				//check if the selected image is the correct file extension
				if ($fileType != "png" && $fileType != "jpg" && $fileType != "jpeg") {
	    			$errors['big'] = "Единствено файлове с разширение .png, .jpg , .jpeg е позволено да качвате!";	
	    			$valid_file = false;
	    		}
	    		//check if the image size is correct
				if ($_FILES['big']['size'] > (3072000)) {
					$valid_file = false;
					$errors['big'] = 'Вашият файл е твърде голям!';
				}
				//check if the image is correct 
				if ($valid_file) {
					move_uploaded_file($_FILES['big']['tmp_name'], $target_file);//move the uploaded image to the correct directory
				}
			} else {
				$errors['big'] = 'Съжаляваме, но възникна грешка' . $_FILES['big']['error'];
			}
		}

   	 	//check if the array of the errors is empty
		if (count($errors) > 1) {
			return $errors; 
		} else {
			$landmarkId = $this->add($query);
			$this->addImages($landmarkId, $dataImages);
			return $errors = array();
		}	
	}

	/**
	 * Adds the image 
	 *
	 * @param string $id The id of the landmark
	 * @param array  $data The image names 
	 */
	public function addImages($id, $data)
	{
		$landmarkId = (int)($id);
		$databaseConnect = new DatabaseConnect();
		$connection = $databaseConnect->connect();
		$stmt = $connection->query("SET NAMES 'utf8';");
		foreach ($data as $key => $image) {
			$stmt = $connection->prepare("INSERT INTO landmark_images(image, place_id, type) VALUES (:image, :landmarkId, :type);");
			
			$stmt->bindParam(':image', $image, PDO::PARAM_STR);
			$stmt->bindParam(':landmarkId', $landmarkId, PDO::PARAM_INT);
			$stmt->bindParam(':type', $key, PDO::PARAM_STR);
			$stmt->execute();
		}
	}

	/**
	* Check if the data for the edited landmark which is sent from the $_POST method is valid
	*
	* @param array $query is the data which is sent from the $_POST method 
	*
	* @return array The errors if there are 
	*/
	public function isValidEditLandmark($query)
	{
		$errors = array();//array for the errors
		$landmark = new Landmarks();
		$landmark->exchangeLandmark($query);

		$name = trim($landmark->getName());
		$review = trim($landmark->getReview());
		$landmarkId = $_GET['landmark_id'];

		$bigImage = $_FILES['big']['name'];//the name of the big image
		$bigImageName = time() . "_" . $bigImage; //make the name of the big image unique
		$smallImage = $_FILES['small']['name'];//the name of the small image
		$smallImageName = time() . "_" . $smallImage; //make the name of the small image unique
		$dataImages = array();
		//check if the name field of the landmark is empty
		if (empty($name)) {
			$errors['name'] = "Моля, въведете име на забележителността!";
		}

		//check if the review field is empty
		if (empty($review)) {
			$errors['review'] = "Моля, въведете кратко описание!";
		}
		//var_dump("" == "");die;
		//Checks if the data is the same
		if ($this->isSameData($query) && empty($bigImage) && empty($smallImage)) {
			$errors['same_data'] = "Няма направени промени!";
		}

		//check if the field for the big image uploading is empty
		if (!empty($_FILES['small']['name'])) {
			//check if the selected directory is created 	
			if (!file_exists(dirname(__FILE__) . '/images/')) {
	            mkdir(dirname(__FILE__) . '/images/', 0755, true);
	        }

			$target_dir = dirname(__FILE__) . '/images/';
			$target_file = $target_dir . basename($smallImageName);
			$fileType = pathinfo($target_file, PATHINFO_EXTENSION);//get the file extension

			//check if have no errors for the image
			if (!$_FILES['small']['error']) {	
				$valid_file = true;
				//check if the selected image is the correct file extension
				if ($fileType != "png" && $fileType != "jpg" && $fileType != "jpeg") {
	    			$errors['small'] = "Единствено файлове с разширение .png, .jpg , .jpeg е позволено да качвате!";	
	    			$valid_file = false;
	    		}
	    		//check if the image size is correct
				if ($_FILES['small']['size'] > (3072000)) {
					$valid_file = false;
					$errors['small'] = 'Вашият файл е твърде голям!';
				}
				//check if the image is correct 
				if ($valid_file) {
					move_uploaded_file($_FILES['small']['tmp_name'], $target_file);//move the uploaded image to the correct directory
					$dataImages['small'] = $smallImageName;
				}
			} else {
				$errors['small'] = 'Съжаляваме, но възникна грешка' . $_FILES['small']['error'];
			}
		}

		//check if the field for the big image uploading is empty
		if (!empty($_FILES['big']['name'])) {
			//check if the selected directory is created 	
			if (!file_exists(dirname(__FILE__) . '/images/')) {
	            mkdir(dirname(__FILE__) . '/images/', 0755, true);
	        }

			$target_dir = dirname(__FILE__) . '/images/';
			$target_file = $target_dir . basename($bigImageName);
			$fileType = pathinfo($target_file, PATHINFO_EXTENSION);//get the file extension

			//check if have no errors for the image
			if (!$_FILES['big']['error']) {	
				$valid_file = true;
				//check if the selected image is the correct file extension
				if ($fileType != "png" && $fileType != "jpg" && $fileType != "jpeg") {
	    			$errors['big'] = "Единствено файлове с разширение .png, .jpg , .jpeg е позволено да качвате!";	
	    			$valid_file = false;
	    		}
	    		//check if the image size is correct
				if ($_FILES['big']['size'] > (3072000)) {
					$valid_file = false;
					$errors['big'] = 'Вашият файл е твърде голям!';
				}
				//check if the image is correct 
				if ($valid_file) {
					move_uploaded_file($_FILES['big']['tmp_name'], $target_file);//move the uploaded image to the correct directory
					$dataImages['big'] = $bigImageName;
				}
			} else {
				$errors['big'] = 'Съжаляваме, но възникна грешка' . $_FILES['big']['error'];
			}
		}

   	 	//check if the array of the errors is empty
		if (count($errors) > 0) {
			return $errors; 
		} else {
			$this->update($query);
			$_SESSION['city_id'] = $query['city'];
			if (count($dataImages) > 0) {
				$this->addImages($landmarkId, $dataImages);
			}
			return $errors = array();
		}	
	}
	
	/**
	 * Selects all images for the user who is logged in
	 *
	 * @return array Array with arrays of objects
	 */ 
	public function selectAllLandmarkItemsImages($id)
	{	
		$landmarkId = $id;
		require '../database_connection/database_connect.php';
		$databaseConnect = new DatabaseConnect();
		$connection = $databaseConnect->connect();
		$stmt = $connection->query("SET NAMES 'utf8';");

		$stmt = $connection->prepare("SELECT id, image FROM landmark_images WHERE place_id = :id AND type != 'deleted';");
		$stmt->bindParam(':id', $landmarkId, PDO::PARAM_INT);
		$stmt->execute();
		$images = $stmt->fetchAll(PDO::FETCH_OBJ);
		return $images;
	}

	/**
	 * Checks the id of the landmark is added by the logged in user
	 *
	 * @param int $id The id of the landmark
	 *
	 * @return bool 
	 */
	public function isLandmarkToUser($id)
	{	
		$userId = $_SESSION['id'];
		require '../database_connection/database_connect.php';
		$databaseConnection = new DatabaseConnect();
	    $connection = $databaseConnection->connect();
	    $stmt = $connection->query("SET NAMES 'utf8';");
	    $stmt = $connection->prepare("SELECT id FROM landmarks WHERE status = true AND id = :landmarkId AND user_id = :userId;");
	    $stmt->bindParam(':landmarkId', $id, PDO::PARAM_INT);
	    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
	    $stmt->execute();

	    return $stmt->fetch();
	}

	/**
	 * Checks if the data for the updated landmark is the same
	 *
	 * @param array $query The data from the post
	 *
	 * @return bool
	 */
	public function isSameData($query)
	{
		//var_dump($query);die;
		$cityId = (int)($query['city']);
		$landmarkId = (int)($_GET['landmark_id']);
		$userId = (int)($_SESSION['id']);
		require '../database_connection/database_connect.php';
		$databaseConnection = new DatabaseConnect();
	    $connection = $databaseConnection->connect();
	    $stmt = $connection->query("SET NAMES 'utf8';");
	    $stmt = $connection->prepare("SELECT id FROM landmarks WHERE status = true AND id = :landmarkId AND user_id = :userId AND name = :name AND review = :review AND city_id = :cityId;");
	    $stmt->bindParam(':landmarkId', $landmarkId, PDO::PARAM_INT);
	    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
	    $stmt->bindParam(':name', $query['name'], PDO::PARAM_STR);
	    $stmt->bindParam(':review', $query['review'], PDO::PARAM_STR);
	    $stmt->bindParam(':cityId', $cityId, PDO::PARAM_INT);
	    $stmt->execute();

	    return $stmt->fetch();
	}

	/**
	* Get all landmarks for a given user with status = true
	*
	* @param int $limit is the limit of the landmarks for one page
	* @param int $offset is the offset of the landmarks  
	*
	* @return array 
	*/
	public function getYourLandmarks($limit, $offset)
	{
		require '../database_connection/database_connect.php';
        $databaseConnection = new DatabaseConnect();
        $connection = $databaseConnection->connect();
        $userId = $_SESSION['id'];//the id of the logged in user
        $stmt = $connection->query("SET NAMES 'utf8';");
        $stmt = $connection->prepare("SELECT id, name, review FROM landmarks 
        							  WHERE user_id = :userId AND status = true ORDER BY id DESC LIMIT :limitLandmark OFFSET :offset;");
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':limitLandmark', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
    	return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	/**
	 * Selects the images for the user who is logged in
	 *
	 * @return array Array with arrays of objects
	 */ 
	public function selectLandmarkItemsImages($ids, $type)
	{	
		require '../database_connection/database_connect.php';
		$databaseConnect = new DatabaseConnect();
		$connection = $databaseConnect->connect();
		$stmt = $connection->query("SET NAMES 'utf8';");
		$images = array();
		foreach ($ids as $id) {
			$stmt = $connection->prepare("SELECT image FROM landmark_images WHERE place_id = :id AND type = :type LIMIT 1;");
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->bindParam(':type', $type, PDO::PARAM_STR);
			$stmt->execute();
			$images["$id"] = $stmt->fetch(PDO::FETCH_OBJ);
		}
		return $images;
	}

	/**
	 * Selects the images for the landmark
	 *
	 * @param int $id The id of the landmark
	 * @param string $type The type of the image
	 * @return array Array of objects
	 */ 
	public function selectLandmarkAllImages($id, $type)
	{	
		require '../database_connection/database_connect.php';
		$databaseConnect = new DatabaseConnect();
		$connection = $databaseConnect->connect();
		$stmt = $connection->query("SET NAMES 'utf8';");

		$stmt = $connection->prepare("SELECT image FROM landmark_images WHERE place_id = :id AND type = :type;");
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->bindParam(':type', $type, PDO::PARAM_STR);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	/**
	* Get the number of all landmarks for given user with status = true
	*
	* @return int 
	*/
	public function numberOfMyLandmarks()
	{
		require '../database_connection/database_connect.php';
		$databaseConnection = new DatabaseConnect();
		$connection = $databaseConnection->connect();
		$userId = $_SESSION['id'];//the id of the logged in user

		$stmt = $connection->prepare("SELECT id FROM landmarks WHERE user_id = :userId AND status = true;");
		$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
		$stmt->execute();

		return $stmt->rowCount();
	}

	/**
	* Get the number of all landmarks in the database with status = true
	*
	* @return int  
	*/
	public function numberOfAllLandmarks()
	{
		require '../database_connection/database_connect.php';
		$databaseConnection = new DatabaseConnect();
		$connection = $databaseConnection->connect();
		
		$stmt = $connection->prepare("SELECT id FROM landmarks WHERE status = true;");
		$stmt->execute();

		return $stmt->rowCount();
	}

	/**
	* Get the landmark information
	*
	* @param int $landmarkId
	* 
	* @return array 
	*/
	public function getLandmarkInfo($landmarkId)
	{
		require '../database_connection/database_connect.php';
        $databaseConnection = new DatabaseConnect();
        $connection = $databaseConnection->connect();

        $stmt = $connection->query("SET NAMES 'utf8';");
        $stmt = $connection->prepare("SELECT landmarks.name AS name, landmarks.review, cities.name AS city, landmarks.city_id 
        	FROM landmarks, cities WHERE landmarks.city_id = cities.id AND landmarks.id = :landmarkId AND status = true;");
        $stmt->bindParam(':landmarkId', $landmarkId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ);
	}

	/**
	* Get all landmarks from the database with status = true
	*
	* @param int $limit 
	* @param int $offset
	*
	* @return array
	*/
	public function getAllLandmarks($limit, $offset)
	{
		require '../database_connection/database_connect.php';
        $databaseConnection = new DatabaseConnect();
        $connection = $databaseConnection->connect();
        $stmt = $connection->query("SET NAMES 'utf8';");
        $stmt = $connection->prepare("SELECT id, name, review FROM landmarks 
        							  WHERE status = true ORDER BY id DESC LIMIT :limitLandmark OFFSET :offset;");
        $stmt->bindParam(':limitLandmark', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
    	return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	/**
	* Update the image name for a landmark 
	*
	* @param int $lanmarkId
	* @param int $id
	*/
	public function deleteImage($id, $landmarkId)
	{
		require '../database_connection/database_connect.php';
		$databaseConnect = new DatabaseConnect();
		$connection = $databaseConnect->connect();

		$stmt = $connection->prepare("UPDATE landmark_images SET type = 'deleted' WHERE id = :id AND place_id = :landmarkId");
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->bindParam(':landmarkId', $landmarkId, PDO::PARAM_INT);
		$stmt->execute();
	}

	/**
	* Gets all hotel links by given id of city 
	*
	* @param int $id
	*
	* @return array 
	*/
	public function getAllHotels($id)
	{
		require '../database_connection/database_connect.php';
		$databaseConnect = new DatabaseConnect();
		$connection = $databaseConnect->connect();

		$stmt = $connection->query("SET NAMES 'utf8';");
		$stmt = $connection->prepare("SELECT category, review FROM landmark_hotels WHERE city_id = :id ORDER BY category DESC;");
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	/**
	* Gets all bar/restaurant/disco links by given id of city 
	*
	* @param int $id
	*
	* @return array 
	*/
	public function getAllBarRestaurants($id)
	{
		require '../database_connection/database_connect.php';
		$databaseConnect = new DatabaseConnect();
		$connection = $databaseConnect->connect();

		$stmt = $connection->query("SET NAMES 'utf8';");
		$stmt = $connection->prepare("SELECT type, review FROM landmark_restaurants WHERE city_id = :id;");
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	/**
	* Gets the landmarks from given city 
	*
	* @param int $id
	*
	* @return array 
	*/
	public function searchLandmarkByCity($id)
	{
		require '../database_connection/database_connect.php';
		$databaseConnect = new DatabaseConnect();
		$connection = $databaseConnect->connect();

		$stmt = $connection->query("SET NAMES 'utf8';");
		$stmt = $connection->prepare("SELECT id, name FROM landmarks WHERE city_id = :id;");
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}
}
