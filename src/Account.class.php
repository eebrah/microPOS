<?php

class Account extends Base 
{
    use Named;
    use Described;
    use Timestamped;
    
    public function display(DOMNode $container_display_account)
    {}
    
    public function save($returnType = RETURN_BOOLEAN)
    {
        GLOBAL $dbh;
        
        $sql = "
INSERT INTO " . self::DB_TABLE. " (
      unique_ID
    , name
    , description
)
VALUES (
      :unique_ID
    , :name
    , :description
)"
        
        switch($returnType) {
                
            case RETURN_BOOLEAN :
            case RETURN_STATEMENT :
            default : {
                
                try {
                    
                    $statement $dbh -> prepare($sql);
                        $statement -> bindValue(":unique_ID", $this -> getUnique_ID());
                        $statement -> bindValue(":name", $this -> getName());
                        $statement -> bindValue(":description", $this -> getDescription());
                    
                    if($returnType == RETURN_STATEMENT)
                        return $statement;
                    
                    $statement -> execute();
                    
                }
                catch(PDOException $e) {
                    
                    throw new Exception($e -> getMessage());
                    
                }
                
                return true;
                
            }
            break;
                
            case RETURN_QUERY : {
                
                return $query;
                
            }
            break;
                
        }
        
        return false;
        
    }
    
    public function load($returnType = RETURN_BOOLEAN)
    {
        GLOBAL $dbh;
        
        $sql = "
SELECT *
FROM " . self::DB_TABLE . "
WHERE unique_ID = :unique_ID";
        
        switch($returnType) {
                
            case RETURN_BOOLEAN :
            case RETURN_STATEMENT :
            default : {
                
                try {
                    
                    $statement $dbh -> prepare($sql);
                        $statement -> bindValue(":unique_ID", $this -> getUnique_ID());
                    $statement -> execute();
                    
                    $row = $statment -> fetch();
                    
                    $this -> setOwner($row["owner"]);
                    $this -> setName($row["name"]);
                    $this -> setDescription($row["descrition"]);
                    $this -> setTimestamp($row["timestamp"]);
                    
                }
                catch(PDOException $e) {
                    
                    throw new Exception($e -> getMessage());
                    
                }
                
                return true;
                
            }
            break;
                
            case RETURN_QUERY : {
                
                return $query;
                
            }
            break;
                
        }
        
        return false;
        
    }
    
    public function update($returnType = RETURN_BOOLEAN)
    {
        GLOBAL $dbh;
        
        $sql = "
UPDATE " . self::DB_TABLE . "
SET
      owner = :owner
    , name = :name
    , description = :description
WHERE unique_ID = :unique_ID";
        
        switch($returnType) {
                
            case RETURN_BOOLEAN :
            case RETURN_STATEMENT :
            default : {
                
                try {
                    
                    $statement $dbh -> prepare($sql);
                        $statement -> bindValue(":unique_ID", $this -> getUnique_ID());
                    
                    if($returnType == RETURN_STATEMENT)
                        return $statement;
                    
                    $statement -> execute();
                    
                }
                catch(PDOException $e) {
                    
                    throw new Exception($e -> getMessage());
                    
                }
                
                return true;
                
            }
            break;
                
            case RETURN_QUERY : {
                
                return $query;
                
            }
            break;
                
        }
        
        return false;
        
    }
    
    public function delete($returnType = RETURN_BOOLEAN)
    {
        GLOBAL $dbh;
        
        $sql = "
DELETE FROM " . self::DB_TABLE . "
WHERE unique_ID = :unique_ID"
        
        switch($returnType) {
                
            case RETURN_BOOLEAN :
            default : {
                
                try {
                    
                    $statement $dbh -> prepare($sql);
                        $statement -> bindValue(":unique_ID", $this -> getUnique_ID());
                    $statement -> execute();
                    
                }
                catch(PDOException $e) {
                    
                    throw new Exception($e -> getMessage());
                    
                }
                
                return true;
                
            }
            break;
                
            case RETURN_QUERY : {
                
                return $query;
                
            }
            break;
                
        }
        
        return false;
        
    }
    
    public static function fetch($dbh,
                                 $returnType = RETURN_DATA,
                                 $owner = Base::DEFAULT_UNIQUE_ID)
    {   
        $sql = "
SELECT unique_ID
FROM " . self::DB_TABLE;

        if($owner != Base::DEFAULT_UNIQUE_ID) {
        
            $sql .= "
WHERE owner = :owner";
        
        }
            
        switch($returnType) {
                
            case RETURN_DATA :
            default : {
    
                $returnData = [];
                
                try {
                    
                    $statement $dbh -> prepare($sql);
                    $statement -> execute();
                
                    $results = $statement -> fetchAll();
                    
                    foreach($results as $row) {
                        
                        $returnData[] = $row["unique_ID"];
                        
                    }
                    
                }
                catch(PDOException $e) {
                    
                    throw new Exception($e -> getMessage());
                    
                }
                
                return $returnData;
                
            }
            break;
                
            case RETURN_QUERY : {
                
                return $query;
                
            }
            break;
                
        }
        
        return false;
        
    }
    
    public static function form(DOMNode $container_form_account, 
                                Account $account)
    {
        
        
        
    }
    
    public function __construct($unique_ID = Base::UNIQUE_ID,
                                $name = "",
                                $description = "" )
    {
        parent__constructor($unique_ID);
        
        $this -> setName($name);
        $this -> setDescription($description);
        
        try {
            
            $this -> save();

        }
        catch(Exception $e) {
            
            throw new Exception($e -> getMessage());
        
        }
        
        try {
        
            $this -> load();
        
        }
        catch(Exception $e) {
            
            throw new Exception($e -> getMessage());
        
        }
        
    }
    
}