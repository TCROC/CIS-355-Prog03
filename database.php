<?php
class Database 
{


	// localhost
	private static $dbName = 'projects' ;
	private static $dbHost = '127.0.0.1' ;
	private static $dbUsername = 'root';
	private static $dbUserPassword = '';


	/*
	// csis.svsu.edu
	private static $dbName = 'tclange355wi19' ;
	private static $dbHost = '10.8.30.49' ;
	private static $dbUsername = 'tclange355wi19';
	private static $dbUserPassword = 'SvsuPassword';
	*/
	
	private static $cont  = null;
	
	public function __construct() {
		exit('Init function is not allowed');
	}
	
	public static function connect()
	{
	   // One connection through whole application
       if ( null == self::$cont )
       {      
        try 
        {
          self::$cont =  new PDO( "mysql:host=".self::$dbHost.";"."dbname=".self::$dbName, self::$dbUsername, self::$dbUserPassword);  
        }
        catch(PDOException $e) 
        {
          die($e->getMessage());
        }
       } 
       return self::$cont;
	}
	
	public static function disconnect()
	{
		self::$cont = null;
	}
}
?>
