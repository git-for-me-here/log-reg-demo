<?php

class TemplatesWorker {
	public $ext;
	private $variables = array();
	
	public function __construct() {
		$this->ext = ".tpl.php";
	}
	
	/**
	* Присваивание переменных в шаблоне
	* @param $name <string>
	* @param $value <mixed>
	*
	*/
	public function assign($name, $value) {
		$this->variables[$name] = $value;
	}
	
	/**
	* Отображение шаблона
	*/
	public function show_display($file_include) {
		if(!file_exists(TMP.'/'.$file_include.$this->ext)) {
			throw new Exception("Файл шаблона не найден!");
		}
		require_once TMP.'/'.$file_include.$this->ext;
	}
	
	public function __get($name) {
		if(isset($this->variables[$name])) {
			$variable = $this->variables[$name];

			return $variable;
		}
		
		return false;
	}
}