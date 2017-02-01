<?php
defined('ABSPATH') or die(':)');

abstract class ingalleryInput{
	
	public static function getVar($name, $default = null, $type = 'none', $hash = 'default'){
		$hash = strtoupper($hash);
		if ($hash === 'default'){
			$hash = strtoupper($_SERVER['REQUEST_METHOD']);
		}
		switch ($hash){
			case 'GET':
				$input = &$_GET;
				break;
			case 'POST':
				$input = &$_POST;
				break;
			case 'FILES':
				$input = &$_FILES;
				break;
			case 'COOKIE':
				$input = &$_COOKIE;
				break;
			case 'ENV':
				$input = &$_ENV;
				break;
			case 'SERVER':
				$input = &$_SERVER;
				break;
			default:
				$input = &$_REQUEST;
				$hash = 'REQUEST';
				break;
		}
		
		if (isset($input[$name]) && $input[$name] !== null){
			$var = self::_cleanVar($input[$name], $type);
		}
		elseif ($default !== null){
			$var = self::_cleanVar($default, $type);
		}
		else{
			$var = $default;
		}
		return $var;
	}
	
	public static function _cleanVar($source, $type = 'string'){
		switch (strtoupper($type)){
			case 'INT':
			case 'INTEGER':
				preg_match('/-?[0-9]+/', (string) $source, $matches);
				$result = @ (int) $matches[0];
				break;
			case 'UINT':
				preg_match('/-?[0-9]+/', (string) $source, $matches);
				$result = @ abs((int) $matches[0]);
				break;
			case 'FLOAT':
			case 'DOUBLE':
				preg_match('/-?[0-9]+(\.[0-9]+)?/', (string) $source, $matches);
				$result = @ (float) $matches[0];
				break;
			case 'BOOL':
			case 'BOOLEAN':
				$result = (bool) $source;
				break;
			case 'WORD':
				$result = (string) preg_replace('/[^A-Z_]/i', '', $source);
				break;
			case 'ALNUM':
				$result = (string) preg_replace('/[^A-Z0-9]/i', '', $source);
				break;
			case 'CMD':
				$result = (string) preg_replace('/[^A-Z0-9_]/i', '', $source);
				$result = trim($result);
				break;
			case 'BASE64':
				$result = (string) preg_replace('/[^A-Z0-9\/+=]/i', '', $source);
				break;
			case 'STRING':
				$result = (string)filter_var($source,FILTER_SANITIZE_STRING);
				break;
			case 'ARRAY':
				$result = self::_cleanArray((array)$source);
				break;
			case 'PATH':
				$pattern = '/^[A-Za-z0-9_-]+[A-Za-z0-9_\.-]*([\\\\\/][A-Za-z0-9_-]+[A-Za-z0-9_\.-]*)*$/';
				preg_match($pattern, (string) $source, $matches);
				$result = @ (string) $matches[0];
				break;
			case 'RAW':
				$result = $source;
				break;
			default:
				if (is_array($source)){
					$result = self::_cleanArray($source);
				}
				else if(preg_match('~^[0-9]+$~',$source)){
					$result = self::_cleanVar($source,'int');
				}
				else if (is_string($source)){
					$result = self::_cleanVar($source,'string');
				}
				else{
					$result = null;
				}
				break;
		}

		return $result;
	}
	
	protected static function _cleanArray($source){
		foreach ($source as $key => $value){
			if(is_array($value)){
				$source[$key] = self::_cleanArray($value);
			}
			else{
				$source[$key] = self::_cleanVar($value,'unknown');
			}
		}
		return $source;
	}
	
	public static function getInt($name, $default = 0, $hash = 'default'){
		return self::getVar($name, $default, 'int', $hash);
	}
	
	public static function getFloat($name, $default = 0.0, $hash = 'default'){
		return self::getVar($name, $default, 'float', $hash);
	}

	public static function getBool($name, $default = false, $hash = 'default'){
		return self::getVar($name, $default, 'bool', $hash);
	}

	public static function getWord($name, $default = '', $hash = 'default'){
		return self::getVar($name, $default, 'word', $hash);
	}

	public static function getCmd($name, $default = '', $hash = 'default'){
		return self::getVar($name, $default, 'cmd', $hash);
	}

	public static function getString($name, $default = '', $hash = 'default'){
		return (string) self::getVar($name, $default, 'string', $hash);
	}

	public static function setVar($name, $value = null, $overwrite = true, $hash = 'default'){
		if (!$overwrite && array_key_exists($name, $_REQUEST)){
			return $_REQUEST[$name];
		}
		$hash = strtoupper($hash);
		if ($hash === 'METHOD'){
			$hash = strtoupper($_SERVER['REQUEST_METHOD']);
		}
		$previous = array_key_exists($name, $_REQUEST) ? $_REQUEST[$name] : null;

		switch ($hash){
			case 'GET':
				$_GET[$name] = $value;
				$_REQUEST[$name] = $value;
				break;
			case 'POST':
				$_POST[$name] = $value;
				$_REQUEST[$name] = $value;
				break;
			case 'COOKIE':
				$_COOKIE[$name] = $value;
				$_REQUEST[$name] = $value;
				break;
			case 'FILES':
				$_FILES[$name] = $value;
				break;
			default:
				$_REQUEST[$name] = $value;
				break;
		}

		return $previous;
	}

	public static function get($hash = 'default'){
		$hash = strtoupper($hash);
		if ($hash === 'METHOD'){
			$hash = strtoupper($_SERVER['REQUEST_METHOD']);
		}
		switch ($hash){
			case 'GET':
				$input = $_GET;
				break;
			case 'POST':
				$input = $_POST;
				break;
			case 'FILES':
				$input = $_FILES;
				break;
			case 'COOKIE':
				$input = $_COOKIE;
				break;
			default:
				$input = $_REQUEST;
				break;
		}
		$result = self::_cleanArray($input);

		return $result;
	}

	public static function set($array, $hash = 'default', $overwrite = true){
		foreach ($array as $key => $value){
			self::setVar($key, $value, $hash, $overwrite);
		}
	}
	
}


