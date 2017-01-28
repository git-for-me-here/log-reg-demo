<?php

class Helper {
	public function __construct(){}
	
	// Проверка, авторизован ли юзер
	public static function UserStatus() {
		if(isset($_SESSION['username']) && !empty($_SESSION['username']) || isset($_COOKIE['username'])) {
			if (isset($_COOKIE['username']))
				$_SESSION['username'] = $_COOKIE['username'];
			
			return true;
		}
		
		return false;
	}
	
	// Очищаем от тегов строковые переменные
	public static function clear($field){
		$field = strip_tags($field);
		$field = htmlspecialchars($field);
		
		return $field;
	}
	
	// Шифруем пароль
	public static function passwordHash($password, $salt = null, $iterations = 10) {
        $salt || $salt = uniqid(rand(),1);
        $hash = md5(md5($password . md5(sha1($salt))));

        for ($i = 0; $i < $iterations; ++$i) {
            $hash = md5(md5(sha1($hash)));
        }

        return array('hash' => $hash, 'salt' => $salt);
    }
	
	// Хеширование данных
	public static function hashInit($data){
		return hash("sha256", $data);
	}
	
	// Отправка письма
	public static function sendMail($to, $subject, $message){
		$headers = "Content-type:text/plain; charset = utf-8" . "\r\n" .
			"X-Mailer: PHP/" . phpversion();

		if(mail($to, $subject, $message, $headers)) return true;

		return false;
	}
	
	// Выход пользователей
	public static function logout() {
		session_unset();
		session_destroy();
		setCookie("username", "" , time() - 3600);
	}
}