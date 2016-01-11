<?php 

require 'interfaces\idatabase_procedures.php';
require 'includes\password_compat-master\lib\password.php';
require 'users.php';

/**
* Main UserDB model 
*/
class UserDB extends Users implements CommonInterface
{	
	/**
	* Selects the user data by given id
	*
	* @param id
	*
	* @return object The data of the user as object
	*/
	public function selectUserData($id)
	{
		$user = new Users();
		require 'database_connection/database_connect.php';
		$databaseConnect = new DatabaseConnect();
		$connection = $databaseConnect->connect();
		$stmt = $connection->query("SET NAMES 'utf8';");
		$stmt = $connection->prepare("SELECT first_name, last_name, username, password, email FROM users WHERE id = :id;");
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_OBJ);
	}

	/**
	* Add to the database the data which is sent from the $_POST method 
	*
	* @param array $query is the data sent from the $_POST method
	*/
	public function add($query)
	{	
		$user = new Users();
		$user->exchangeArray($query);
		$firstName = $user->getFirstName();
		$lastName = $user->getLastName();
		$userName = $user->getUserName();
		$hashedPassword = password_hash($user->getPassword(), PASSWORD_DEFAULT);//hashes the password 
		$email = $user->getEmail();
		$dateOfRegistration = date("Y-m-d");//assign to the date of registration the current date
		require 'database_connection/database_connect.php';
		$databaseConnect = new DatabaseConnect();
		$connection = $databaseConnect->connect();
		$stmt = $connection->query("SET NAMES 'utf8';");
		$stmt = $connection->prepare("INSERT INTO users(first_name, last_name, username, password, email, date_of_registration) 
									  VALUES(:firstName, :lastName, :userName, :hashedPassword, :email, :date_of_registration);");
		$stmt->bindParam(':firstName', $firstName, PDO::PARAM_STR);
		$stmt->bindParam(':lastName', $lastName, PDO::PARAM_STR);
		$stmt->bindParam(':userName', $userName, PDO::PARAM_STR);
		$stmt->bindParam(':hashedPassword', $hashedPassword, PDO::PARAM_STR);
		$stmt->bindParam(':email', $email, PDO::PARAM_STR);
		$stmt->bindParam(':date_of_registration', $dateOfRegistration, PDO::PARAM_STR);
		$stmt->execute();
		$connection = null;
	}

	/**
	* Check the username and the password which are sent from the $_POST method
	*
	* @param array $query
	*
	* @return bool Returns true if the username and the password are in the database as pair
	*/
	public function select($query)
	{	
		$user = new Users();
		$user->exchangeArray($query);

		$loginPassword = false;

		$userName = $user->getUserName();
		$password = $user->getPassword();
		require 'database_connection/database_connect.php';
		$databaseConnect = new DatabaseConnect();
		$connection = $databaseConnect->connect();

		$stmt = $connection->prepare("SELECT password FROM users WHERE username = :userName");
		$stmt->bindParam('userName', $userName, PDO::PARAM_STR);
		$stmt->execute();
		
		while ($row = $stmt->fetch()) {
			if (password_verify($password, $row['password']) == true) {//check if the hashed password in the database is the same as the sent
				$loginPassword = true;
			}
		}
		return $loginPassword;
	}

	/**
	* Updates the user data
	*
	* @param array $query The data from the post
	*/
	public function update($query)
	{
		$user = new Users();
		$user->exchangeArray($query);

		$userId = $_SESSION['id'];// the id of the user who is logged in 
		$firstName = trim($user->getFirstName());
		$lastName = trim($user->getLastName());

		if (strlen($user->getPassword()) > 0)
		{
			$hashedPassword = password_hash($user->getPassword(), PASSWORD_DEFAULT);

			$databaseConnect = new DatabaseConnect();
			$connection = $databaseConnect->connect();
			$stmt = $connection->query("SET NAMES 'utf8';");
			$stmt = $connection->prepare("UPDATE users SET first_name = :firstName, last_name = :lastName, password = :hashedPassword WHERE id = :userId;");
			$stmt->bindParam(':firstName', $firstName, PDO::PARAM_STR);
			$stmt->bindParam(':lastName', $lastName, PDO::PARAM_STR);
			$stmt->bindParam(':hashedPassword', $hashedPassword, PDO::PARAM_STR);
			$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);

			$stmt->execute();
		} else {
			$databaseConnect = new DatabaseConnect();
			$connection = $databaseConnect->connect();
			$stmt = $connection->query("SET NAMES 'utf8';");
			$stmt = $connection->prepare("UPDATE users SET first_name = :firstName, last_name = :lastName WHERE id = :userId;");
			$stmt->bindParam(':firstName', $firstName, PDO::PARAM_STR);
			$stmt->bindParam(':lastName', $lastName, PDO::PARAM_STR);
			$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);

			$stmt->execute();
		}
	}

	public function delete($query)
	{

	}

	/**
	* Add the data for the registered user in the database which is sent from the $_POST method
	*
	* @param array
	* 
	* @return array The errors array
	*/
	public function register($post)
	{
		$errors = array();//array for the errors
		$user = new Users();
		$user->exchangeArray($post);

		$firstName = trim($user->getFirstName());
		$lastName = trim($user->getLastName());
		$userName = trim($user->getUserName());
		$password = trim($user->getPassword());
		$confirmedPassword = trim($user->getConfirmedPassword());
		$email = trim($user->getEmail());

		$session_data = array();

		$session_data['firstName'] = $firstName;
		$session_data['lastName'] = $lastName;
		$session_data['userName'] = $userName;
		$session_data['password'] = $password;
		$session_data['confirmedPassword'] = $confirmedPassword;
		$session_data['email'] = $email;

		$errors['session'] = $session_data;

	    //check if the field for the first name is empty
	    if (empty($firstName)) {
			$errors['firstName'] = "Моля въведете име!";
		}

		//check if the field for the last name is empty
		if (empty($lastName)) {
			$errors['lastName'] = "Моля въведете фамилия!";
		}

		//check if the field for the user name is empty
		if (empty($userName)) {
			$errors['userName'] = "Моля въведете потребителско име!";
		}

		//check if the username is not an email address
		if (filter_var($userName, FILTER_VALIDATE_EMAIL) == true) {
			$errors['userName'] = "Потребителското име не може да бъде имейл адрес!";
		}

		//check if the password is empty
		if (empty($password)) {
			$errors['password'] = "Моля въведете парола!";
		}

		//check if password is the same as the repeated password
		if ($password != $confirmedPassword) {
			$errors['confirmedPassword'] = "Моля повторете паролата правилно!";
		}

		//check if the field for the email is valid email address
		if (empty($email) || filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
			$errors['email'] = "Моля въведете валиден имейл адрес!";
		}

		//check if the user name exists in the database
		if ($this->exists($userName) == true) {
			$errors['userName'] = "Потребителското име е заето!";
		}

		//check if the email exists in the database
		if ($this->existsEmail($email) == true) {
			$errors['email'] = "Съществува потребител с този имейл адрес!";
		}
		
		//check if the array of errors empty
		if (count($errors) > 1) {
			return $errors;
		} else { 
		    $this->add($post);//create new user
		    return $errors = array();
		}	
	}

	/**
	* Check if the entered username is unique
	*
	* @param string $userName
	*
	* @return bool 
	*/
	public function exists($userName)
	{
		$exists = false;
		require 'database_connection/database_connect.php';
		$databaseConnect = new DatabaseConnect();
		$connection = $databaseConnect->connect();

		$stmt = $connection->prepare("SELECT username FROM users WHERE username = :userName");
		$stmt->bindParam(':userName', $userName, PDO::PARAM_STR);
		$stmt->execute();
		if ($stmt->fetch()) {
			$exists=true;
		}
		return $exists;
	}

	/**
	* Check if the entered email is unique
	*
	* @param string $email
	*
	* @return bool 
	*/
	public function existsEmail($email)
	{
		$exists = false;
		require 'database_connection/database_connect.php';
		$databaseConnect = new DatabaseConnect();
		$connection = $databaseConnect->connect();

		$stmt = $connection->prepare("SELECT email FROM users WHERE email = :email;");
		$stmt->bindParam(':email', $email, PDO::PARAM_STR);
		$stmt->execute();
		if ($stmt->fetch()) {
			$exists=true;
		}
		return $exists;
	}

	/**
	* Log in the user with the data which is sent from the $_POST method
	*
	* @param array
	* 
	* @return array The errors array
	*/
	public function login($post)
	{
		$errors = array();
		$user = new Users();
		$user->exchangeArray($post);

		$userName = trim($user->getUserName());
		$password = trim($user->getPassword());
		$remember =(isset($_POST['remember'])) ? $_POST['remember'] : '';//check if the remember checkbox is set

		$_SESSION['id'] = $this->getUserId($userName);//get the id of the logged in user
		$session_data['userName'] = $userName;
		$session_data['password'] = $password;

		$errors['session'] = $session_data; 

		//check if the field for user name is empty
		if (empty($userName)) {
			$errors['userName'] = "Потребителското име/парола не могат да бъдат празни!";			
		}

		//check if the field for the password is empty
		if (empty($password)) {
			$errors['password'] = "Потребителското име/парола не могат да бъдат празни!";			
		}
		
		//check if the entered data exists in the database	
  		if ($this->select($post) == false) {
  			$errors['userName'] = "Въведените потребителско име/парола не са валидни!";
  		} elseif ($this->select($post) == true) {
  			$_SESSION['isLogged'] = true;
  			
			if ($remember == "yes") {//check if the checkbox remember me is checked
				setcookie("remember", $user, time() + 3600);//create a cookie
					
			}
  		}

 		//check if there is errors
  		if (count($errors) > 0) {
			return $errors;
		} else {
			return $errors = array();
		}
	}
	
	/**
	* Get the id of the logged in user
	*
	* @param string $userName
	*
	* @return int 
	*/
	public function getUserId($userName)
	{	
		require 'database_connection/database_connect.php';
		$databaseConnect = new DatabaseConnect();
		$connection = $databaseConnect->connect();
		$stmt = $connection->prepare("SELECT id FROM users WHERE username = :userName");
		$stmt ->bindParam(':userName', $userName, PDO::PARAM_STR);
		$stmt->execute();

		while ($row = $stmt->fetch()) {
			return $row['id'];
		}
	}

	/**
	* Check if the user update data is valid - if it is saves the updates, else gives errors
	*
	* @param array $data
	*
	* @return array This is an array with errors 
	*/
	public function isValidUpdateUser($post)
	{
		$errors = array();//array for the errors
		$user = new Users();
		$user->exchangeArray($post);

		$firstName = trim($user->getFirstName());
		$lastName = trim($user->getLastName());

	    //check if the field for the first name is empty
	    if (empty($firstName)) {
			$errors['firstName'] = "Моля въведете име!";
		}

		//check if the field for the last name is empty
		if (empty($lastName)) {
			$errors['lastName'] = "Моля въведете фамилия!";
		}

		if ($this->isDataIsDifferent($post) == false) {
			$errors['same_data'] = "Не сте направили промяна!";
		}

		//check if the array of errors empty
		if (count($errors) > 0) {
			return $errors;
		} else { 
		    $this->update($post);//updates user's data
		    return $errors = array();
		}	
	}

	/**
	* Check if the new entered user's data is the same as the data in the database
	*
	* @param array $post
	*
	* @return bool  
	*/
	public function isDataIsDifferent($post)
	{
		$userId  = $_SESSION['id'];
		$userDatabase = $this->selectUserData($userId);
		$user = new Users();
		$user->exchangeArray($post);

		$userPostData['firstName'] = trim($user->getFirstName());
		$userPostData['lastName'] = trim($user->getLastName());
		$userPostData['password'] = trim($user->getPassword());

		if ($userDatabase->first_name == $userPostData['firstName'] && $userDatabase->last_name == $userPostData['lastName']) {
			if (strlen($userPostData['password']) == 0 || password_verify($userPostData['password'], $userDatabase->password)) {
				return false;
			} else {
				return true;
			}
		} else {
			return true;
		}
	}
}
