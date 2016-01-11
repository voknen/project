<?php

if (!class_exists("Ratings")) {
	
	/**
	* Main Ratings model
	*
	* @property int $id 
	* @property int $rating
	* @property int $landmarkId
	* @property int $userId
	*/ 
	class Ratings
	{
		private $id;
		private $rating;
		private $landmarkId;
		private $userId;

		/**
		* Destruct the created object
		*/
		public function __destruct() 
		{  
	              
	    }

	    /**
		* Set the rating id
		*
		* @param int $id
		*/
		public function setId($id)
		{
			$this->id = $id;
		}

		/**
		* Set the rating of the landmark
		*
		* @param int $rating
		*/
		public function setRating($rating)
		{
			$this->rating = $rating;
		}

		/**
		* Set the id of the landmark
		*
		* @param int $landmarkId
		*/
		public function setLandmarkId($landmarkId)
		{
			$this->landmarkId = $landmarkId;
		}

		/**
		* Set the id of the user
		*
		* @param int $userId
		*/
		public function setUserId($userId)
		{
			$this->userId = $userId;
		}

		/**
		* Get the id of the rating
		*
		* @return int
		*/
		public function getId()
		{
			return $this->id;
		}

		/**
		* Get the rating of the landmark
		*
		* @return int
		*/
		public function getRating()
		{
			return $this->rating;
		}

		/**
		* Get the id of the landmark
		*
		* @return int
		*/
		public function getLandmarkId()
		{
			return $this->landmarkId;
		}

		/**
		* Get the id of the user
		*
		* @return int
		*/
		public function getUserId()
		{
			return $this->userId;
		}

		/**
		* Set the rating of the landmark 
		*
		* @param int $rating
		* @param int $landmarkId
		* @param int userId
		*
		* @return bool 
		*/
		public function setRatingLandmark($rating, $landmarkId, $userId)
		{
			require '../database_connection/database_connect.php';
			$databaseConnection = new DatabaseConnect();
			$connection = $databaseConnection->connect();

			$stmt = $connection->prepare("INSERT INTO landmark_ratings (rating, place_id, user_id) VALUES(:rating, :landmarkId, :userId);");
			$stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
			$stmt->bindParam(':landmarkId', $landmarkId, PDO::PARAM_INT);
			$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
			$stmt->execute();
			return $isRated = true;
		}

		/**
		* Update the rating for the landmark
		*
		* @param int $rating
		* @param int $landmarkId
		* @param int $userId 
		*/
		public function updateRatingLandmark($rating, $landmarkId, $userId)
		{
			require '../database_connection/database_connect.php';
			$databaseConnection = new DatabaseConnect();
			$connection = $databaseConnection->connect();

			$stmt = $connection->prepare("UPDATE landmark_ratings SET rating = :rating WHERE place_id = :landmarkId AND user_id = :userId;");
			$stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
			$stmt->bindParam(':landmarkId', $landmarkId, PDO::PARAM_INT);
			$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
			$stmt->execute();
		}

		/**
		* Check if landmark is rated from the user who is logged in
		*
		* @param int $landmarkId
		* @param int $userId
		*
		* @return bool
		*/
		public function isRated($landmarkId, $userId)
		{
			require '../database_connection/database_connect.php';
			$databaseConnection = new DatabaseConnect();
			$connection = $databaseConnection->connect();
			$stmt = $connection->prepare("SELECT id FROM landmark_ratings WHERE place_id = :landmarkId AND user_id = :userId;");
			$stmt->bindParam(':landmarkId', $landmarkId, PDO::PARAM_INT);
			$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
			$stmt->execute();
			if ($stmt->rowCount() == 0) {
				return false;
			} else {
				return true;
			}
		}

		/**
		* Get the average rating for the landmark
		*
		* @param int $landmarkId
		*
		* @return float
		*/
		public function getAverageRating($landmarkId)
		{
			require '../database_connection/database_connect.php';
			$databaseConnection = new DatabaseConnect();
			$connection = $databaseConnection->connect();
			$average='';
			$stmt = $connection->prepare("SELECT AVG(rating) FROM landmark_ratings WHERE place_id = :landmarkId;");
			$stmt->bindParam(':landmarkId', $landmarkId, PDO::PARAM_INT);
			$stmt->execute();
		
			$average .= implode(',', $stmt->fetch(PDO::FETCH_ASSOC));

			return number_format((float)$average, 1);
		}
	}
}