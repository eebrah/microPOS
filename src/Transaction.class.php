<?php

class Transaction extends Base {
	
	const TYPE_SALE = 0;
	const TYPE_PURCHASE = 1;
	const TYPE_TRANSFER = 2;
	
	private $timestamp;		// When did it happen
	private $amount;		// What amount was involved
	
	private $type;			// inflow or outflow? or acct to acct 
	private $account;		// Which account was impacted
	
	
	public function setTimestamp($timestamp = date("Y-m-d H:i:s")) 
	{
		$this -> timestamp = $timestamp;
	
	}
	
	public function getTimesamp()
	{
		return $this -> timestamp;
		
	}
	
	public function setAmount($amount = 0) 
	{
		$this -> amount = $amount;
	
	}
	
	public function getAmount()
	{
		return $this -> amount;
	
	}
	
	public function setType($type = self::TYPE_SALE)
	{
		$this -> type = $type;
	
	}
	
	public function getType()
	{
		
	
	}
	
	public function __construct($unique_ID = Base::DEFAULT_UNIQUE_ID,
	                            $amount = 0,
	                            $type = this::TYPE_SALE) {
			
		parent::__construct($unique_ID);
		
		if($unique_ID == Base::DEFAULT_UNIQUE_ID) {
			
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
