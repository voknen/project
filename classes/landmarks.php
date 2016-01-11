<?php
if (!class_exists("Landmarks")) {

	/**
	* Main Landmarks model 
	*
	* @property int 	$id
	* @property int 	$userId is the id of the user who insert the landmark
	* @property int 	$cityId is the id of the city where is placed the landmark
	* @property string 	$name is the name of the landmark
	* @property string 	$review is the review of the landmark
	* @property bool 	$status is the status of the landmark (true - available; false - deleted)
	*/
	class Landmarks
	{
		private $id;
		private $userId;
		private $cityId;
		private $name;
		private $review;
		private $status;

		/**
		* Assign the data which is sent from the $_POST method to the properties of the class
		*
		* @param array $data
		*/
		public function exchangeLandmark($data)
		{
			$this->name = (isset($data['name'])) ? $data['name'] : '';
			$this->review = (isset($data['review'])) ? $data['review'] : '';
		}

		/**
		* Destruct the created object
		*/
		public function __destruct() 
		{  
	              
	    }


		/**
		* Set the id of the landmark
		*
		* @param int $id 
		*/
		public function setId($id)
		{
			$this->id = $id;
		}


		/**
		* Set the id of the user which create the landmark
		*
		* @param int $userId
		*/
		public function setUserId($userId)
		{
			$this->userId = $userId;
		}

		/**
		* Set the id of the city where is placed the landmark
		*
		* @param int $cityId
		*/
		public function setCityId($cityId)
		{
			$this->cityId = $cityId;
		}


		/**
		* Set the name of the landmark
		*
		* @param string $name
		*/
		public function setName($name)
		{
			$this->name = $name;
		}

		/**
		* Set the review of the landmark
		*
		* @param string $review 
		*/
		public function setReview($review)
		{
			$this->review = $review;
		}

		/**
		* Set the status of the landmark
		*
		* @param bool $status
		*/
		public function setStatus($status)
		{
			$this->status = $status;
		}

		/**
		* Get the id of the landmark
		*
		* @return int 
		*/
		public function getId()
		{
			return $this->id;
		}

		/**
		* Get the id of the user who create the landmark
		*
		* @return int
		*/
		public function getUserId()
		{
			return $this->userId;
		}

		/**
		* Get the id of the user who create the landmark
		*
		* @return int
		*/
		public function getCityId()
		{
			return $this->cityId;
		}

		/**
		* Get the name of the landmark
		*
		* @return string
		*/
		public function getName()
		{
			return $this->name;
		}

		/**
		* Get the review of the landmark
		*
		* @return string
		*/
		public function getReview()
		{
			return $this->review;
		}

		/**
		* Get the status of the landmark
		*
		* @return bool  
		*/
		public function getStatus()
		{
			return $this->status;
		}
	}
}