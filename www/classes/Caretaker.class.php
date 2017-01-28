<?php
/**
 * Класс проверки и блокировки ip-адреса.
 */
class Caretaker {
    /**
     * Метод проверки ip-адреса на блокировку.
     */
    public static function checkIp() {
        // Получение ip-адреса
        $ip_address = self::getIp();
		
		$path_to_file = "files/ip";
		$path_to_file = str_replace('\\' , '/', ROOT_DIR . '/' . $path_to_file . '/');
		
		// проверка директории
		if (!is_writable($path_to_file)) 
			throw new Exception('ошибка проверки ip<br>не могу найти дирректорию для работы ' .
									$path_to_file);
			
		
		// Проверка ip-адресов	
		$is_block = false;
		if ($dir = opendir($path_to_file)) {
			while (false !== ($filename = readdir($dir))) {
				// Выбирается ip + время блокировки этого ip
				if (preg_match('#^(\d{1,3}.\d{1,3}.\d{1,3}.\d{1,3})_(\d+)$#', $filename, $matches)) {
					if ($matches[1] == $ip_address) {
						if (BLOCK_ALWAYS) {
							$is_block = true;
							throw new Exception("Произошла ошибка в момент регистрации.<br>
								К сожалению, Вы больше не можете регистрироваться с этого IP адреса.");
						}
						else {
							if ($matches[2] >= time() - BLOCK_TIME) {
								$is_block = true;
								$time_block = $matches[2] - (time() - BLOCK_TIME) + 1;
								throw new Exception("Произошла ошибка в момент регистрации.<br>
									К сожалению, Вы временно заблокированы.<br>
									Вам придется подождать. Через " . $time_block . " секунд(ы) Вы будете автоматически разблокированы.");
							} else {
								unlink($path_to_file . $filename);
							}
						}
					}  
				} 
			}
			closedir($dir);
		}

		if (!$is_block) 
			touch($path_to_file . $ip_address . '_' . time());

    }


    /**
     * Метод получения текущего ip-адреса из переменных сервера.
     */
    private static function getIp() {
        // ip-адрес по умолчанию
        $ip_address = '127.0.0.1';

        // Массив возможных ip-адресов
        $addrs = array();

        // Сбор данных возможных ip-адресов
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // Проверяется массив ip-клиента установленных прозрачными прокси-серверами
            foreach (array_reverse(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])) as $value) {
                $value = trim($value);
                // Собирается ip-клиента
                if (preg_match('#^\d{1,3}.\d{1,3}.\d{1,3}.\d{1,3}$#', $value)) {
                    $addrs[] = $value;
                }
            }
        }
        // Собирается ip-клиента
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $addrs[] = $_SERVER['HTTP_CLIENT_IP'];
        }
        // Собирается ip-клиента
        if (isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
            $addrs[] = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
        }
        // Собирается ip-клиента
        if (isset($_SERVER['HTTP_PROXY_USER'])) {
            $addrs[] = $_SERVER['HTTP_PROXY_USER'];
        }
        // Собирается ip-клиента
        if (isset($_SERVER['REMOTE_ADDR'])) {
            $addrs[] = $_SERVER['REMOTE_ADDR'];
        }

        // Фильтрация возможных ip-адресов, для выявление нужного
        foreach ($addrs as $value) {
            // Выбирается ip-клиента
            if (preg_match('#^(\d{1,3}).(\d{1,3}).(\d{1,3}).(\d{1,3})$#', $value, $matches)) {
                $value = $matches[1] . '.' . $matches[2] . '.' . $matches[3] . '.' . $matches[4];
                if ('...' != $value) {
                    $ip_address = $value;
                    break;
                }
            }
        }

        // Возврат полученного ip-адреса
        return $ip_address;
    }

}