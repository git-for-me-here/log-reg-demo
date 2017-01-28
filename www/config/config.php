<?php

//Ошибки (выводить на экран) - В реальном проекте лучше поменять на false
ini_set('display_errors', 1); /* ini_set('display_errors', 0); */
//Отображать все ошибки - В реальном проекте лучше поставить E_ERROR или 0
error_reporting(E_ALL); /* error_reporting(0); */
 
//подключение классов
/* spl_autoload_register(function($className){
	if(file_exists(CLS.'/'.$className.'.class.php')) 
		require_once CLS.'/'.$className.'.class.php'; 
}); */
function __autoload ($className) {
    if(file_exists(CLS.'/'.$className.'.class.php')) 
		require_once CLS.'/'.$className.'.class.php';
}
 
//http путь до корня
$root_url = explode("/", filter_input(INPUT_SERVER, "PHP_SELF"));
$dirname = empty($root_url[1]) ? '/' : '/'.$root_url[1];

//текущий каталог, если скрипт в каталоге
if($root_url[1] != 'index.php') define("DIR", $root_url[1]);
else define("DIR", "");
 
define("HTTP_PATH", 'http://'.filter_input(INPUT_SERVER, "HTTP_HOST") .$dirname);

//ваш сайт
$siteName = "Log-Reg-Demo";
define("SITE_NAME", $siteName);
 
/**
* Ключи reCAPTCHA
* */ 
//Добавьте этот ключ в HTML-код сайта.
define("PUBLIC_KEY", "");
//Этот ключ нужен для связи между вашим сайтом и Google. Никому его не сообщайте.
define("SECRET_KEY", "");
 
/**
* Блокировка IP при регистрации:
* true - постоянная
* false - временная
* */
define("BLOCK_ALWAYS", false);
//время блокировки IP в секундах
$blockTime = 120;
define("BLOCK_TIME", $blockTime);
 
/**
* Подключение к БД с параметрами:
* - Имя пользователя MySQL
* - Пароль пользователя MySQL
* - Имя Базы Данных
* - Сервер базы данных (localhost, если не задан)
* */
Connect::_self()->setConnect("", "", "");
 
//Экземпляр класса шаблонизатора
$tmp = new TemplatesWorker();