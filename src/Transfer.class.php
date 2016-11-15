<?php

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
