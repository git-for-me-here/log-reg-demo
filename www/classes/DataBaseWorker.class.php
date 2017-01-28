<?php

class DataBaseWorker {
	private $db;
	
 	public function __construct() {
 		$this->db = Connect::_self()->mysql();
    }
	
	/**
	 * Регистрация пользователей
	 * */
	public function signup($data) {
		// серверные проверочки для начала
		if (empty($data['username']) || empty($data['phonenumber']) ||
			empty($data['email'])) {
			throw new Exception("Произошла ошибка в момент регистрации.<v>
				Все поля должны быть заполнены!");	
		}
		if (strlen($data['password']) < 8 ||
			strlen($data['password_confirm']) < 8) {
			throw new Exception("Произошла ошибка в момент регистрации.<br>
				Длина пароля должна быть не менее 8 символов!");	
		}
		if ($data['password'] != $data['password_confirm'])
			throw new Exception("Произошла ошибка в момент регистрации.<br>
				Пароли не совпадают!");
		// проверка капчи
		$secret = SECRET_KEY;
		$response = $_POST['captcha'];
		$remoteip = $_SERVER['REMOTE_ADDR']; 
		$url = @file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response&remoteip=$remoteip");
		$result = json_decode($url, TRUE);
		if ($result['success'] != 1) 
			throw new Exception("Произошла ошибка в момент регистрации.<br>
				Для регистрации необходимо пройти проверку!");
		// проверка мейла
		$email = Helper::clear($data['email']);
		switch ($this->checkEmail($email)) {
			case 1:
				throw new Exception("Произошла ошибка в момент регистрации.<br>
					На указанный e-mail уже выслано письмо с ссылкой для активации");
			case 2:
				throw new Exception("Произошла ошибка в момент регистрации.<br>
					Данный е-mail уже зарегистрирован!");
		}
		//проверка IP
		try {
			Caretaker::checkIp();
		} catch(Exception $e) {
			throw new Exception($e->getMessage());
		}
		
		// подготовка данных для записи в базу
		$username = Helper::clear($data['username']);
		$phonenumber = Helper::clear($data['phonenumber']);
		$hashes = Helper::passwordHash($data['password']);
		$password = $hashes['hash'];
		$salt = $hashes['salt'];

	    // Регистрируем пользователя
	    $query = $this->db->prepare("INSERT INTO users (username, mail, phone, password, salt) 
	                                 VALUES(:username, :email, :phone, :password, :salt)");
		$query->bindParam(':username', $username, PDO::PARAM_STR, 255);
		$query->bindParam(':email', $email, PDO::PARAM_STR, 255);
		$query->bindParam(':phone', $phonenumber, PDO::PARAM_STR, 20);
		$query->bindParam(':password', $password, PDO::PARAM_STR, 255);	
		$query->bindParam(':salt', $salt, PDO::PARAM_STR, 100);		
		
		if($query->execute()) {
			// отправляем письмо активации
			$id = $this->db->lastInsertId();
			$key_hash = Helper::hashInit($email."::".$password);
			$link_activate = 'http://'.$_SERVER['SERVER_NAME']."/demo/activate/".$id."/".$key_hash;
			$send = Helper::sendMail($email, "Активация аккаунта!", "Здравствуйте, вы зарегистрировались  на сайте " . SITE_NAME . 
				"\n\nДля подтверждения регистрации, кликните по ссылке активации: " . $link_activate); 
			
			if ($send)
				$this->db->exec("UPDATE users SET active = 1 WHERE id = $id");
			
			return true;
		}
	} 
	
	/**
	 * Активация аккаунта
	 * */
	public function activateAccount($id, $activateKey) {
		$query = $this->db->query("SELECT mail, password FROM users WHERE id = $id");
		$result = $query->fetch();
		if($activateKey === Helper::hashInit($result->mail."::".$result->password)){
			if($this->db->exec("UPDATE users SET active = 2 WHERE id = $id"))
				return true;
		}	

		return false;
	}
	
	/***
	 * Авторизация пользователей
	 * */
	public function login($data) {
		// проверочка полей
		if(empty($data['login']) || empty($data['password']))
			throw new Exception("E-mail и пароль не могут быть пустыми!"); 
		
		// подготовка полей
		$login = Helper::clear($data['login']);
		$query = $this->db->prepare("SELECT username, mail, password, salt, active FROM users WHERE mail = :mail");
		$query->bindParam(":mail", $login, PDO::PARAM_STR);
		$query->execute();
		$result = $query->fetch();
		
		// поиск аккаунта
		if(!empty($result->mail)) {	
			// проверка активации аккаунта 
			if($result->active < 2)
				throw new Exception("Ваш аккаунт не активирован!");
			
			// проверка пароля и установка печеньки по желанию
			$password = Helper::passwordHash($data['password'], $result->salt);
			$password = $password['hash'];
			if($result->mail === $login && $password === $result->password) {
				// все верно
				if($data['loginkeeping'] == "true")
					setCookie("username", $result->username, time() + 3600 * 24 * 30);

				$_SESSION['username'] = $result->username;

				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * Востановление пароля
	 **/
	public function recover($data){
		$email = Helper::clear($data['email']);
		
		switch($this->checkEmail($email)) {
			case 0:
				throw new Exception('Введеный email в базе не зарегистрирован!');
				break;
			case 1:
				throw new Exception('Указан email от еще не активированого аккаунта!');
				break;
		}
			
		//генерируем новый пароль
		$email = $this->db->quote($email);
		$new_password = uniqid(rand(),1);
		$hashes = Helper::passwordHash($new_password);
		$password = $hashes['hash'];
		$password = $this->db->quote($password);
		$salt = $hashes['salt'];
		$salt = $this->db->quote($salt);
		
		//перезаписываем пароль пользователю
		$query = $this->db->query("UPDATE users SET password = $password, salt = $salt WHERE mail = $email");
		if(!$query) return false;
		
		//отправляем письмо пользователю с новым паролем
		Helper::sendMail($data['email'], "Новый пароль", "Ваш новый пароль, для доступа к аккаунту на сайте " . SITE_NAME . "\n" . $new_password);

		return true;
	}
	
	private function checkEmail($email) {
		$query = $this->db->prepare("SELECT id, active FROM users WHERE mail = :email");
		$query->bindParam(":email", $email, PDO::PARAM_STR);
		$query->execute();
		$result = $query->fetch();
		if (empty($result->id)) return 0; 
		else {
			switch ($result->active) {
				case "1":
					return 1;
				case "2":
					return 2;
			}
		}
	}
}