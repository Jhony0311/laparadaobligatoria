<?php
defined('ABSPATH') or die(':)');

class ingalleryView
{
	private $_tmpl;
	private $_data = array();
	
	public function __construct($tmpl=null){
		if($tmpl){
			$this->setTemplate($tmpl);
		}
	}
	
	public function get($name,$default=null){
		if(isset($this->_data[$name])){
			return $this->_data[$name];
		}
		return $default;
	}
	
	public function set($name,$value){
		$this->_data[$name] = $value;
	}
	
	public function setTemplate($tmpl){
		$this->_tmpl = $tmpl;
	}
	
	public function getTemplate(){
		return $this->_tmpl;
	}
	
	protected function getTemplatePath(){
		return INGALLERY_PATH.'tmpl/';
	}
	
	public function loadTemplate($tmpl){
		$path = $this->getTemplatePath().$tmpl.'.php';
		if(!is_file($path) || !is_readable($path)){
			throw new Exception(sprintf(__('Template "%s" for the view "%s" not found.','ingallery'),$path,get_class($this)));
		}
		ob_start();
		include($path);
		$result = ob_get_contents();
		ob_end_clean();
		return $result;
	}

	public function render(){
		return $this->loadTemplate($this->getTemplate());
	}
}