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

class Transaction extends Base 
{
    use Timestamped;
    use Named;
    use Described;
    
	const TYPE_SALE = 0;
	const TYPE_PURCHASE = 1;
    const TYPE_ALL = -1;
    
    const STATUS_PENDING = 0;
    const STATUS_COMPLETE = 1;
    const STATUS_DISPUTED = 2;
    const STATUS_ALL = -1;
	
	private $type;			// Sale? or Purchase
	private $transfers = [];	
    
	public function setType($type = self::TYPE_SALE)
	{
		$this -> type = $type;
	
	}
	
	public function getType()
	{
		return $this -> type;
	
	}
    
    public function addTransfer(Transfer $transfer)
    {
        $this -> transfers[] = $transfer -> getUnique_ID();

    }
	
    public function getTransfers()
    {
        return $this -> transfers;
        
    }
    
    public function save($dbh, $returnType = RETURN_BOOLEAN) 
    {
        $sql = "
INSERT INTO " . self::DB_TABLE . " (
      unique_ID
    , type
    , name
    , description
)
VALUES (
      :unique_ID
    , :type
    , :name
    , :description
)";
        
        switch($returnType) {
                
            case RETURN_BOOLEAN :
            case RETURN_STATEMENT :
            default : {
                
                try {
                    
                    $statement = $dbh -> prepare($sql);
                        $statement -> bindValue(":unique_ID", $this -> getUnique_ID());
                        $statement -> bindValue(":type", $this -> getType());
                        $statement -> bindValue(":name", $this -> getName());
                        $statement -> bindValue(":description", $this -> getDescription());
                
                    if($returnType == RETURN_STATEMENT)
                        return $statement;

                    $statement -> execute;
                    
                }
                catch(PDOException $e) {
                    
                    throw new Exception($e -> getMessage());
                    
                }
                
                return true;
                
            }
            break;

            case RETURN_QUERY : {
                
                return $sql;
                
            }
            break;
                
        }
        
        return $false;
        
    }
    
    public function load($dbh, $returnType = RETURN_BOOLEAN)
    {
        $sql = "
SELECT *
FROM " . self::DB_TABLE . "
WHERE unique_ID = :unique_ID";
        
        $sql_fetchTransfers = "
SELECT unique_ID
FROM transfers
WHERE transaction = :transaction";
        
        switch($returnType) {
                
            case RETURN_BOOLEAN :
            case RETURN_STATEMENT :
            default : {
                
                try {
                    
                    $statement = $dbh -> prepare($sql);
                        $statement -> bindValue(":unique_ID", $this -> getUnique_ID());
                    $statement -> execute;
                    
                    $row = $statement -> fetch();
                    
                    $this -> setType($row["type"]);
                    $this -> setName($row["name"]);
                    $this -> setDescription($row["description"]);
                    $this -> setTimestamp($row["timestamp"]);
                    
                    // Fetch any associated transfers
                    
                    $statement = $dbh -> prepare($sql_fetchTransfers);
                        $statement -> bindValue(":transaction", $this -> getUnique_ID());
                    $statement -> execute();
                    
                    $foreach($results as $row) {
                        
                        $this -> addTransfer($row["unique_ID"]);
                        
                    }
                    
                }
                catch(PDOException $e) {
                    
                    throw new Exception($e -> getMessage());
                    
                }
                
                return true;
                
            }
            break;

            case RETURN_QUERY : {
                
                return $sql;
                
            }
            break;
                
        }
        
        return $false;
        
    }
    
    public function update($dbh, $returnType = RETURN_BOOLEAN)
    {
        $sql = "
UPDATE " . self::DB_TABLE . "
SET
      type = :type
    , name = :name
    , description = :description
WHERE unique_ID = :unique_ID";
        
        switch($returnType) {
                
            case RETURN_BOOLEAN :
            case RETURN_STATEMENT :
            default : {
                
                try {
                    
                    $statement = $dbh -> prepare($sql);
                        $statement -> bindValue(":unique_ID", $this -> getUnique_ID());
                        $statement -> bindValue(":type", $this -> getType());
                        $statement -> bindValue(":name", $this -> getName());
                        $statement -> bindValue(":description", $this -> getDescription());
                
                    if($returnType == RETURN_STATEMENT)
                        return $statement;

                    $statement -> execute;
                    
                }
                catch(PDOException $e) {
                    
                    throw new Exception($e -> getMessage());
                    
                }
                
                return true;
                
            }
            break;

            case RETURN_QUERY : {
                
                return $sql;
                
            }
            break;
                
        }
        
        return $false;
        
    }
    
    public function delete($dbh, $returnType = RETURN_BOOLEAN)
    {
        $sql = "
DELETE FROM " . self::DB_TABLE . "
WHERE unique_ID = :unique_ID";
        
        switch($returnType) {
                
            case RETURN_BOOLEAN :
            default : {
                
                try {
                    
                    $statement = $dbh -> prepare($sql);
                        $statement -> bindValue(":unique_ID", $this -> getUnique_ID());
                
                    $statement -> execute;
                    
                }
                catch(PDOException $e) {
                    
                    throw new Exception($e -> getMessage());
                    
                }
                
                return true;
                
            }
            break;

            case RETURN_QUERY : {
                
                return $sql;
                
            }
            break;
                
        }
        
        return $false;
        
    }
    
    public static function fetch($dbh, 
                                 $returnType = RETURN_DATA, 
                                 $type = self::TYPE_ALL,
                                 $status = self::STATUS_ALL)
    {
        $sql = "
SELECT unique_ID FROM " . self::DB_TABLE . "
WHERE ";
        
        if($type == self::TYPE_ALL) {
                
            $sql .= "1"; 

        }
        else {

            $sql .= "type = $type";

        }
        
        if($status != self::STATUS_ALL) {
                
            $sql .= " 
AND status = $status"
                
        }
        
        switch($returnType) {
                
            case RETURN_DATA :
            default : {
                
                $returnData = [];
                
                try {
                    
                    $statement = $dbh -> prepare($sql);         
                    $statement -> execute;
                    
                    $results = $statement -> fetch();
                    
                    foreach($results as $row) {
                        
                        $returnData[] = $row["unique_ID"];
                        
                    }
                    
                }
                catch($e) {
                    
                    throw new Exception($e -> getMessage());
                    
                }
                
                return $returnData;
                
            }
            break;

            case RETURN_QUERY : {
                
                return $sql;
                
            }
            break;
                
        }
        
        return $false;
        
    }
    
    public function __construct($unique_ID = Base::DEFAULT_UNIQUE_ID,
	                            $type = self::TYPE_SALE,
                                $name = "",
                                $description = "") {
			
		parent::__construct($unique_ID);
		
		if($unique_ID == Base::DEFAULT_UNIQUE_ID) {
			
            $this -> setType($type);
            $this -> setName($name);
            $this -> setDescription($description);
            
			try {
				
				$this -> save();
			
			}
			catch(PDOException $e) {
				
				throw new Exception($e -> getMessage());
			
			}		
		
		}
		else {
			
			try {
				
				$this -> load();
		
			}
			catch(PDOException $e) {
				
				throw new Exception($e -> getMessage());
			
			}
		
		}
	
	}

}

class Transfer extends Base 
{
    
    const DB_TABLE = "transfers";
    
    private $transaction;
    private $amount;
    private $from;
    private $to;
    
    public function setTransaction(Transaction $transaction) 
    {
        $this -> transaction = $transaction -> getUnique_ID();
        
    }
    
    public function getTransaction() 
    {
        return $this -> transaction;    
        
    }
    
    public function setAmount($amount) 
    {
        $this -> amount = $amount;
        
    }
    
    public function getAmount()
    {
        return $this -> amount;
        
    }
    
    public function setFrom(Account $account) 
    {
        $this -> from = $account;
        
    }
    
    public function getFrom() 
    {
        return $this -> from;
        
    }
    
    public function setTo(Account $to)
    {
        $this -> to = $to;
        
    }
    
    public function getTo()
    {
        return $this -> to;
        
    }
    
    public function save($dbh, $returnType = RETURN_BOOLEAN)
    {
        
        $sql = "
INSERT INTO " . self::DB_TABLE . " (
      unique_ID
    , transaction
    , amount
    , from
    , to
)
VALUES (
      :unique_ID
    , :transaction
    , :amount
    , :from
    , :to
)";
        
        switch($returnType) {
                
            case RETURN_BOOLEAN :
            case RETURN_STATEMENT :
            default : {
                
                try {
                    
                    $statement = $dbh -> prepare($sql);
                        $statement -> bind(":unique_ID", $this -> getUnique_ID());
                        $statement -> bind(":transaction", $this -> getTransaction());
                        $statement -> bind(":amount", $this -> getAmount());
                        $statement -> bind(":from", $this -> getFrom());
                        $statement -> bind(":to", $this -> getTo());
                    
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
                
                return $sql;
                
            }
                
        }
        
        return false;
        
    }
    
    public function load($dbh, $returnType = RETURN_BOOLEAN)
    {
        
        $sql = "
SELECT *
FROM " . self::DB_TABLE . "
WHERE unique_ID = :unique_ID";
        
        switch($returnType) {
                
            case RETURN_BOOLEAN :
            case RETURN_STATEMENT :
            default : {
                
                try {
                    
                    $statement = $dbh -> prepare($sql);
                        $statement -> bind(":unique_ID", $this -> getUnique_ID());
                    
                    if($returnType == RETURN_STATEMENT) 
                        return $statement;
                    
                    $statement -> execute();
                    
                    $row = $statement -> fetch();
                    
                    $this -> setTransaction($row["transaction"]);
                    $this -> setAmount($row["amount"]);
                    $this -> setFrom($row["from"]);
                    $this -> setTo($row["to"]);
                    $this -> setTimestamp($row["timestamp"]);
                    
                }
                catch(PDOException $e) {
                    
                    throw new Exception($e -> getMessage());
                    
                }
                
                return true;
                
            }
            break;
            
            case RETURN_QUERY : {
                
                return $sql;
                
            }
                
        }
        
        return false;
        
    }
    
    public function update($dbh, $returnType = RETURN_BOOLEAN)
    {
        
        $sql = "
UPDATE " . self::DB_TABLE . "
SET
      transaction = :transaction
    , amount = :amount
    , from = :from
    , to = :to
WHERE unique_ID = :unique_ID";
        
        switch($returnType) {
                
            case RETURN_BOOLEAN :
            case RETURN_STATEMENT :
            default : {
                
                try {
                    
                    $statement = $dbh -> prepare($sql);
                        $statement -> bind(":unique_ID", $this -> getUnique_ID());
                        $statement -> bind(":transaction", $this -> getTransaction());
                        $statement -> bind(":amount", $this -> getAmount());
                        $statement -> bind(":from", $this -> getFrom());
                        $statement -> bind(":to", $this -> getTo());
                    
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
                
                return $sql;
                
            }
                
        }
        
        return false;
        
    }
    
    public function fetch($dbh, $returnType = RETURN_DATA)
    {
        
        $sql = "
SELECT unique_ID
FROM " . self::DB_TABLE . "
WHERE 1";
        
        switch($returnType) {
                
            case RETURN_DATA :
            default : {
                
                $returnData = [];
                
                try {
                    
                    $statement = $dbh -> prepare($sql);
                    $statement -> execute();
                    
                    $results = statement -> fetchAll();
                    
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
                
                return $sql;
                
            }
                
        }
        
        return false;
        
    }
	    
    public function delete($dbh, $returnType = RETURN_BOOLEAN)
    {
        
        $sql = "
DELETE FROM " . self::DB_TABLE . "
WHERE unique_ID = :unique_ID";
        
        switch($returnType) {
                
            case RETURN_BOOLEAN :
            case RETURN_STATEMENT :
            default : {
                
                try {
                    
                    $statement = $dbh -> prepare($sql);
                        $statement -> bind(":unique_ID", $this -> getUnique_ID());
                    
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
                
                return $sql;
                
            }
                
        }
        
        return false;
        
    }
	
	public function __construct($unique_ID = Base::DEFAULT_UNIQUE_ID,
                                $transaction = Base:;DEFAULT_UNIQUE_ID,
	                            $amount = self::DEFAULT_AMOUNT,
                                $from = Base::DEFAULT_UNIQUE_ID,
                                $to = Base::DEFAULT_UNIQUE_ID) {
			
		parent::__construct($unique_ID);
		
		if($unique_ID == Base::DEFAULT_UNIQUE_ID) {
			
            if($transaction == Base::DEFAULT_UNIQUE_ID)
                throw new Exception("The transaction within which this transfer occured must be specified")
            
            $this -> setTransaction($transaction);
            
            if($amount < 0)
                throw new Exception("The amount cannot be lower than zero");
            
            $this -> setAmount($amount);
            
            if($from == Base::DEFAULT_UNIQUE_ID)
                throw new Exception("The account to be debited must be specified");
            
            $this -> setFrom($from);
            
            if($to == Base::DEFAULT_UNIQUE_ID)
                throw new Exception("The account to be credited must be specified");
            
            $this -> setTo($to);
            
			try {
				
				$this -> save();
			
			}
			catch(PDOException $e) {
				
				throw new Exception($e -> getMessage());
			
			}		
		
		}
		else {
			
			try {
				
				$this -> load();
		
			}
			catch(PDOException $e) {
				
				throw new Exception($e -> getMessage());
			
			}
		
		}
	
	}
    
}

class Account extends Base 
{
    use Named;
    use Described;
    use Timestamped;
    
    public function save($dbh,
                         $returnType = RETURN_BOOLEAN)
    {
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
    
    public function load($dbh,
                         $returnType = RETURN_BOOLEAN)
    {
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
    
    public function update($dbh,
                           $returnType = RETURN_BOOLEAN)
    {
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
    
    public function delete($dbh,
                           $returnType = RETURN_BOOLEAN)
    {
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