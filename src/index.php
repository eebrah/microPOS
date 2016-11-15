<?php

include_once "Account.class.php";
include_once "Transaction.class.php";


$section = "home";

if(isset(REQUEST["section"])) 
    $section = $_REQUEST["section"];

switch($section) {
        
    case "home" :
    default : {
        
        
        
    }
    break;
        
    case "transactions" : {}
    break;
        
    case "transfers" : {}
    break;
        
    case "accounts" : {
        
        $action = "list";
        
        switch($action) {
                
            case "list" :
            default : {}
            break;
                
            case "add" : {
                
                $account = new Account();
                
            }
            break;
                
            case "edit" : {}
            break;
                
            case "view" : {}
            break;
                
            case "delete" : {}
            break;
                
        }
        
    }
    break;
        
}

?>