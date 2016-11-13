<?php

date_default_timezone_set("Africa/Nairobi");

require_once("DBConfig.php");
	
try {
	$dbh = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS, array( PDO::ATTR_PERSISTENT => true));
	$dbh -> setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
   
} 
catch(PDOException $e) {
	throw new exception($e -> getMessage());   
	die();
   
}	

/**
 * @author Ibrahim Ngeno <ibrahim.ngeno@gmail.com>
 */
Class Base {
	
	const DEFAULT_UNIQUE_ID = "00000";
	const DEFAULT_DATETIME = "1900-00-00";

	private $uniqueID;
	
	/**
	 * Sets the object variable $unique_ID to the value passed in
	 * @param string $uniqueID
	 * @return string 
	 */  
	function setUniqueID($uniqueID) 
	{ 	
		$this -> uniqueID = $uniqueID; 
	
	}	
	
	/**
	 * Returns the current value of the objects $uniqueID variable
	 * @return string 
	 */  
	function getUniqueID() 
	{ 	
		return $this -> uniqueID; 
	
	}
	
	/**
	 * Generates a unique ID of the length specified, based on the seed string provided
	 * @param int $length
	 * @param string $seed
	 * @return string
	 */  
	public static function genUniqueID($length = 5, $seed = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ') 
	{
		
		$returnValue = DEFAULT_UNIQUE_ID;

		for($i = 0; $i < $length; $i++) { 
			$returnValue[ $i ] = $seed[ rand( 0, strlen( $seed ) - 1 ) ]; 
			
		}
		
		return $returnValue;
			
	}
	
	/**
	 * Class contructor, if a unique ID is passed in, sets it as $uniqueID, else generates one and sets it
	 * @param int $length
	 */
	function __construct( $uniqueID = DEFAULT_UNIQUE_ID ) {
		
		if($uniqueID == DEFAULT_UNIQUE_ID) { 	// No uniqueID passed, this is a new record
			$this -> setUniqueID( self::genUniqueID() );
		
		}
		else { 	// A unique ID was passed, set it as the value
			$this -> setUniqueID($uniqueID);
		
		}
	
	}

}

?>
