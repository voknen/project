<?php

/**
* Main Admin model
*/
class Admin 
{	
	/**
	* Checks if the admin data for login is right
	*
	* @param array $post The data from the post method
	*
	* @return array The errors if there is 
	*/
	public function isValidAdmin($post)
	{

		$errors = array();

		$userName = trim($post['userName']);
		$password = trim($post['password']);

		//check if the field for user name is empty
		if (empty($userName)) {
			$errors['userName'] = "Въведените данни за админа не са валидни!";			
		}

		//check if the field for the password is empty
		if (empty($password)) {
			$errors['password'] = "Въведените данни за админа не са валидни!";			
		}
		
		//check if the entered data is the right for the admin
  		if ($userName != "superadmin" && password_verify($password,'$2y$10$4/gdPJ9VhEYeCWLEFojseOClU8n2g6wHBtcigrOZw.2haq..HUzQO')) {
  			$errors['userName'] = "Въведените данни за админа не са валидни!";
  		} else {
  			$_SESSION['isLogged'] = true;
  		}

 		//check if there is errors
  		if (count($errors) > 0) {
			return $errors;
		} else {
			return $errors = array();
		}
	}


	/**
	* Checks if the city name is right
	*
	* @param array $post The data from the post method
	*
	* @return array The errors if there is
	*/
	public function isValidCity($post)
	{
		$city = trim($post['city']);

		$session_data['city'] = $city;

		$errors['session'] = $session_data;

		//checks if the city is empty
		if (empty($city)) {
			$errors['city'] = "Моля, въведете име на град!";
		}

		//checks if the city name exists in the database
		if ($this->isExistCity($city)) {
			$errors['city'] = "Този град е бил въведен!";
		}

		//checks if the city name is only cyrrilic letters
		if (preg_match('/^[a-zA-Z]+$/', $city)) {
			$errors['city'] = "Градовете трябва да са на Кирилица!";
		}

		//checks if there is errors
		if (count($errors) > 1) {
			return $errors;
		} else {
			$this->addCity($post);
			return $errors = array();
		}
	}


	/**
	* Adds city in the database
	*
	* @param array $data The name of the city
	*/
	public function addCity($data)
	{
		$city = $data['city'];

		require '../database_connection/database_connect.php';
		$databaseConnect = new DatabaseConnect();
		$connection = $databaseConnect->connect();
		$stmt = $connection->query("SET NAMES 'utf8';");
		$stmt = $connection->prepare("INSERT INTO cities(name) VALUES(:city);");
		$stmt->bindParam(':city', $city, PDO::PARAM_STR);
		$stmt->execute();
	}


	/**
	* Checks if city is already added
	*
	* @param string $data The name of the city
	*
	* @return bool/object If the city exists in the database
	*/
	public function isExistCity($data)
	{
		$city = $data;

		require '../database_connection/database_connect.php';
		$databaseConnect = new DatabaseConnect();
		$connection = $databaseConnect->connect();
		$stmt = $connection->query("SET NAMES 'utf8';");
		$stmt = $connection->prepare("SELECT name FROM cities WHERE name = :city;");
		$stmt->bindParam(':city', $city, PDO::PARAM_STR);
		$stmt->execute();

		$result = $stmt->fetch();

		return $result;
	}

	/**
	* Checks if the hotel is right
	*
	* @param array $post The data from the post method
	*
	* @return array The errors if there is
	*/
	public function isValidHotel($post)
	{	
		$url = trim($post['url']);

		$session_data['url'] = $url;

		$errors['session'] = $session_data;

		//checks if the url is empty
		if (strlen($url) == 0) {
			$errors['url'] = "Моля, въведете линк към хотела!";
		}

		//checks if the url is valid
		if (!preg_match('/^http(s)?:\/\/[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(\/.*)?$/i', $url)) {
			$errors['url'] = "Моля, въведете валиден линк към хотела!";
		}

		//checks if there is errors
		if (count($errors) > 1) {
			return $errors;
		} else {
			$this->addHotel($post);
			return $errors = array();
		}
	}

	/**
	* Adds hotel in the database
	*
	* @param array $data The data for the hotel
	*/
	public function addHotel($data)
	{
		$cityId = (int)($data['city']);
		$category = (int)($data['stars']);
		$url = trim($data['url']);

		require '../database_connection/database_connect.php';
		$databaseConnect = new DatabaseConnect();
		$connection = $databaseConnect->connect();
		$stmt = $connection->query("SET NAMES 'utf8';");
		$stmt = $connection->prepare("INSERT INTO landmark_hotels(category, review, city_id) VALUES(:category, :url, :cityId);");
		$stmt->bindParam(':category', $category, PDO::PARAM_INT);
		$stmt->bindParam(':url', $url, PDO::PARAM_STR);
		$stmt->bindParam(':cityId', $cityId, PDO::PARAM_INT);
		$stmt->execute();
	}

	/**
	* Checks if the place is right
	*
	* @param array $post The data from the post method
	*
	* @return array The errors if there is
	*/
	public function isValidBar($post)
	{	
		$url = trim($post['url']);

		$session_data['url'] = $url;

		$errors['session'] = $session_data;

		//checks if the url is empty
		if (strlen($url) == 0) {
			$errors['url'] = "Моля, въведете линк към хотела!";
		}

		//checks if the url is valid
		if (!preg_match('/^http(s)?:\/\/[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(\/.*)?$/i', $url)) {
			$errors['url'] = "Моля, въведете валиден линк към хотела!";
		}

		//checks if there is errors
		if (count($errors) > 1) {
			return $errors;
		} else {
			$this->addBar($post);
			return $errors = array();
		}
	}

	/**
	* Adds the place in the database
	*
	* @param array $data The data for the place
	*/
	public function addBar($data)
	{
		$cityId = (int)($data['city']);
		$type = $data['types'];
		
		$url = trim($data['url']);

		require '../database_connection/database_connect.php';
		$databaseConnect = new DatabaseConnect();
		$connection = $databaseConnect->connect();
		$stmt = $connection->query("SET NAMES 'utf8';");
		$stmt = $connection->prepare("INSERT INTO landmark_restaurants(city_id, type, review) VALUES(:cityId, :type, :url);");
		$stmt->bindParam(':type', $type, PDO::PARAM_STR);
		$stmt->bindParam(':url', $url, PDO::PARAM_STR);
		$stmt->bindParam(':cityId', $cityId, PDO::PARAM_INT);
		$stmt->execute();
	}
}