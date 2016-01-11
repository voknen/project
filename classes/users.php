<?php

/**
* Main Users model
*
* @property int    $id
* @property string $firstName is the first name of the user
* @property string $lastName is the last name of the user
* @property string $userName is the user name
* @property string $password is the password of the user
* @property string $confirmedPassword is the repeated password
* @property string $email is the email address of the user
* @property date   $dateOfRegistration is the date of the registration of the user
*/
class Users
{

	private $id;
	private $firstName;
	private $lastName;
	private $userName;
	private $password;
	private $confirmedPassword;
	private $email;
	private $dateOfRegistration;

	/**
	* Destruct the created object
	*/
	public function __destruct() 
	{  
              
    }

    /**
	*  Assign the data which is sent from the $_POST method to the properties of the class
	*
	* @param array $data
	*/
    public function exchangeArray($data)
    {
		$this->firstName = (isset($data['firstName'])) ? $data['firstName'] : '';
		$this->lastName = (isset($data['lastName'])) ? $data['lastName'] : '';
		$this->userName = (isset($data['userName'])) ? $data['userName'] : '';
		$this->password = (isset($data['password'])) ? $data['password'] : '';
		$this->confirmedPassword = (isset($data['confirmedPassword'])) ? $data['confirmedPassword'] : '';
		$this->email = (isset($data['email'])) ? $data['email'] : '';
    }

    /**
	* Set the id of the user
	*
	* @param int $id
	*/
	public function setId($id)
	{
		$this->id = $id;
	}

	/**
	* Set the first name of the user
	*
	* @param string $firstName
	*/
	public function setFirstName($firstName)
	{
		$this->firstName = $firstName;
	}

	/**
	* Set the last name of the user
	*
	* @param string $lastName
	*/
	public function setLastName($lastName)
	{
		$this->lastName = $lastName;
	}

	/**
	* Set the user name
	*
	* @param string $userName
	*/
	public function setUserName($userName)
	{
		$this->userName = $userName;
	}

	/**
	* Set the password of the user
	*
	* @param string $password
	*/
	public function setPassword($password)
	{
		$this->password = $password;
	}

	/**
	* Set the repeated password
	*
	* @param string $confirmedPassword
	*/
	public function setConfirmedPassword($confirmedPassword)
	{
		$this->confirmedPassword = $confirmedPassword;
	}

	/**
	* Set email of the user
	*
	* @param string $email
	*/
	public function setEmail($email)
	{
		$this->email = $email;
	}

	/**
	* Set the date of the registration of the user
	*
	* @param date $dateOfRegistration
	*/
	public function setDateOfRegistration($dateOfRegistration)
	{
		$this->dateOfRegistration = $dateOfRegistration;
	}

	/**
	* Get the id of the user
	*
	* @return int
	*/
	public function getId()
	{
		return $this->id;
	}

	/**
	* Get the first name of the user
	*
	* @return string
	*/
	public function getFirstName()
	{
		return $this->firstName;
	}

	/**
	* Get the last name of the user
	*
	* @return string
	*/
	public function getLastName()
	{
		return $this->lastName;
	}

	/**
	* Get the user name
	*
	* @return string
	*/
	public function getUserName()
	{
		return $this->userName;
	}

	/**
	* Get the password of the user
	*
	* @return string
	*/
	public function getPassword()
	{
		return $this->password;
	}

	/**
	* Get the repeated password of the user
	*
	* @return string
	*/
	public function getConfirmedPassword()
	{
		return $this->confirmedPassword;
	}

	/**
	* Get the email of the user
	*
	* @return string
	*/
	public function getEmail()
	{
		return $this->email;
	}

	/**
	* Get the date of the registration of the user
	*
	* @return date
	*/
	public function getDateOfRegistration()
	{
		return $this->dateOfRegistration;
	}
		
}
