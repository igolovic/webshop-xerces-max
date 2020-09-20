<?php

class LCM_Database extends Zend_Db
{
	protected $database;
	
	public function __construct()
	{
		$config = Zend_Registry::get('config');
		$this->database = Zend_Db::factory($config->database);
			
		$stmt = new Zend_Db_Statement_Mysqli($this->database, "set names UTF8");
        $stmt->execute();
	}
	
	public function getDatabase()
	{
		return $this->database;
	}
}

?>