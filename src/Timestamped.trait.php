<?php

trait Timestamped 
{
    
    private $timestamp;		// When did it happen	
	
	public function setTimestamp($timestamp = date("Y-m-d H:i:s")) 
	{
		$this -> timestamp = $timestamp;
	
	}
	
	public function getTimesamp()
	{
		return $this -> timestamp;
		
	}
    
}
