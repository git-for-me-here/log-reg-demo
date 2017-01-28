<?php

class Route {
	private static $tmp;
	
	public function __toString() {}
	
	public function __construct() {
		self::$tmp = new TemplatesWorker();   
		self::dispatcher();   
		self::data(); 
	}
	
	/**
	* Роутирование данных
	* */
	public static function dispatcher() {
		if(isset($_GET['route']) && !empty($_GET['route'])){
			$route = explode("/", strip_tags($_GET['route']));
			$db = new DataBaseWorker();
			if(count($route) > 1){
				switch($route[0]){
					case "activate":
						$activateKey = trim($route[2]);
						$id = intval($route[1]);		

						if($db->activateAccount($id, $activateKey)) {	
							self::location(HTTP_PATH);
							$_SESSION['info'] = "success";
							exit();
						} else { 
							self::location(HTTP_PATH);
							$_SESSION['info'] = "error";
							exit();
						}
						break;
				}
			} else {
				switch($route[0]){
					case "signup":
						return "signup";
						break;
					case "login":
						return "login";
						break;
					case "recover":
						return "recover";
						break;				
					case "logout":
						self::location(HTTP_PATH);
						Helper::logout();
						break;
				}
			}
		} 
	}
	
	/**
	* Работа с данными POST 
	* */
	public static function data() {
		try {
			if(isset($_POST) && !empty($_POST)) {
				$db = new DataBaseWorker();
				if(isset($_POST['act']) && $_POST['act'] == "signup") {
					if($db->signup($_POST))				
						echo "На Ваш e-mail отправлено письмо с сылкой для активации аккаунта.";
					exit;					
				}
				if(isset($_POST['act']) && $_POST['act'] == "login") {
					if($db->login($_POST)) echo "ok";
					else echo "Вход на сайт не был произведён. Возможно, Вы ввели неверный e-mail или пароль.";
					exit;
				}
				if(isset($_POST['act']) && $_POST['act'] == "recover") {
					if($db->recover($_POST))
						echo "На ваш e-mail отправлен новый пароль для входа на сайт.";
					exit;
				}
			}
		} catch(Exception $e) {
			echo $e->getMessage();
		} 
	}
	
	/**
	* Редирект
	* */
	public static function location($url, $time = 0) {
		if($time == 0) header("Location:" . $url);
		else header("Refresh:". $time . ";url=". $url);	
	}
}


