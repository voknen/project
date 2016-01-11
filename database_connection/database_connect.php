<?php

if (!class_exists("DatabaseConnect")) {
	
	/**
	* Main DatabaseConnect model
	*/  
	class DatabaseConnect 
	    {  

	    	/**
	    	* Connects to the database
	    	*
	    	* @return object which is PDO
	    	*/
	        public function connect()
	        {
	            return new PDO('mysql:host=localhost;dbname=landmark', 'root', '');
	        } 
	    }  
}