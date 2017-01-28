<?php

class Connect {
	//Connection Varibles
	private $db_user;
	private $db_password;
	private $db_name;
	private $db_host;

	private $_db;
	public static $_self = null;
   
	/**
	* Используем паттерн проектирования Singleton, для того чтобы нельзя было создать более 1 объекта
	* */
	private function __construct() {}
	
	public static function _self() {
		if(self::$_self == null){
			self::$_self = new self();
		}
	   
		return self::$_self;
	}
	
	/**
	* Метод для работы с СУБД MySQL
	* */
	public function mysql() {
		try {
		$this->_db = new PDO("mysql:dbname={$this->db_name};host={$this->db_host}", $this->db_user, $this->db_password,
			array(
				PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES UTF8",  //кодировка
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,   //По умолчанию работать с данными в виде объекта
				PDO::ATTR_ERRMODE => true  
			));
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
		
		return $this->_db;
	}
	
	public function setConnect($user, $pass, $dbname, $host = 'localhost') {
		$this->db_user = $user;
		$this->db_password = $pass;
		$this->db_name = $dbname;
		$this->db_host = $host;
	}
}
