<?php

session_start();
	
// Константа полного пути до корня
define("ROOT_DIR", dirname(__FILE__));
	 
// Путь до папки с классами (classes)
define("CLS", ROOT_DIR.'/classes');
 
//Путь до шаблонов (templates)
define("TMP", ROOT_DIR.'/templates');
	  
//Инициализируем скрипт
require_once ROOT_DIR.'/config/init.php';
Initional::init();
