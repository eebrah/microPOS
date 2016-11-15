<?php

trait Named 
{
    
    private $name;
    
    public function setName($name)
    {
        $this -> name = $name;
        
    }
    
    public function getName()
    {
        return $this -> name;
        
    }
    
}
