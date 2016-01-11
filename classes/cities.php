<?php

/**
* Main Cities model
*
* @property int 		$id
* @property string 		$city for name of the city
*
*/
class Cities
{
	private $id;
	private $city;

	/**
	* Destruct the created object
	*/
	public function __destruct() 
	{  
              
    }

	/**
	* Set the id of the city
	*
	* @param int $id 
	*/
	public function setId($id)
	{
		$this->id = $id;
	}

	/**
	* Set the name for the city
	*
	* @param string $city 
	*/
	public function setCity($city)
	{
		$this->city = $city;
	}

	/**
	* Get the id of the city
	*
	* @return int 
	*/
	public function getId()
	{
		return $this->id;
	}

	/**
	* Get the name of the city 
	* 
	* @return string
	*/
	public function getCity()
	{
		return $this->city;
	}

	/**
	* Get the ids and the names of all cities
	*
	* @return array with objects   
	*/
	public function getAllCities()
	{
		require '../database_connection/database_connect.php';
		$databaseConnection = new DatabaseConnect();
		$connection = $databaseConnection->connect();
		$stmt = $connection->query("SET NAMES 'utf8';");
		$stmt = $connection->prepare("SELECT id, name FROM cities ORDER BY name;");
		$stmt->execute();
		
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	public function citySelector()
	{
	    try{
	       	
	    	$cities = $this->getAllCities();
	    	
	        $html = '';
	        $html .= '<select name="city">' . "\n";
	        foreach($cities as $city){
                $html .= "<option value=\"$city->id\"";
                $html .= isset($_SESSION['city_id']) && $_SESSION['city_id'] == $city->id ? 'selected' : '';
                $html .= ">$city->name</option>\n";
	        }
	    } catch(PDOException $e) {
	            echo 'ERROR:' . $e->getMessage();
	    }
	    $html .= "</select>";

	    return $html;
	}
}
