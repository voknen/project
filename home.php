<?php

class Home
{
	/**
	 * Selects the most rated 4 landmarks
	 *
	 * @return array Array with 4 objects
	 */ 
	public function selectLandmarkHomepageItems()
	{	
		require 'database_connection/database_connect.php';
		$databaseConnect = new DatabaseConnect();
		$connection = $databaseConnect->connect();
		$stmt = $connection->query("SET NAMES 'utf8';");
		$stmt = $connection->prepare("SELECT id, name, review FROM landmarks WHERE status = true ORDER BY id DESC LIMIT 4 ;");
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	/**
	 * Selects the images for the most rated 4 landmarks
	 *
	 * @return array Array with arrays of objects
	 */ 
	public function selectLandmarkHomepageItemsImages($type)
	{	
		$mostRated = $this->selectLandmarkHomepageItems();

		$ids = array();
		foreach ($mostRated as $value) {
			$ids[] = (int)($value->id);
		}

		require 'database_connection/database_connect.php';
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
	 * Wraps the text if the length is bigger than 200 characters
	 *
	 * @param $text 	The text that should be wrapped
	 * @param $wrapText The replacement symbols
	 *
	 * @return string The wrapped text
	 */
	public function softTrim($text, $wrapText='...')
	{
		if (strlen($text) > 200) {
	        preg_match('/^.{0,200}(?:.*?)\b/siu', $text, $matches);
	        $text = $matches[0];
	    } else {
	        $wrapText = '';
	    }
	    return $text . $wrapText;
	}
}