<?php

trait Described 
{
    
    private $description;
    
    public function setDescription($description)
    {
        $this -> description = $description;
        
    }
    
    public function getDescription()
    {
        return $this -> name;
        
    }
    
}
