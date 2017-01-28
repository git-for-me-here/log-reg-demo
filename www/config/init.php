<?php

class Initional {
	/**
	 * Инициализация скрипта
	 * */
	public static function init(){
		//Подключаем конфигурацию
		require dirname(__FILE__).'/config.php';
		new Route();
	  
		//Выводим шаблон
		try {
			$tmp->show_display('main'); 
		} catch (Exception $e) {
			echo $e;
		}
	}
}
