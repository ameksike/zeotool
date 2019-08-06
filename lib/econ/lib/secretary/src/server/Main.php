<?php
/*
 *
 * @framework: Bycod
 * @package: Secretary
 * @version: 0.1
 * @description: This is simple and light lib for manage DBSM
 * @authors: ing. Antonio Membrides Espinosa
 * @mail: amembrides@uci.cu
 * @made: 23/4/2011
 * @update: 23/4/2011
 * @license: GPL v3
 * @require: PHP >= 5.2.*, Loader <= 0.1
 *
 */
namespace Secretary\src\server;
class Main
{
	protected $drivers;
	protected $active;

	public function __construct($driver='mysql'){
		$this->drivers = array();
		$this->setting($driver);
	}

	protected function driver($driver=false, $config=false){
		$driver = $driver ? $driver : $this->active;
        return (!isset($this->drivers[$driver])) ? $this->load($driver, $config) : $this->drivers[$driver];
	}

	protected function load($driver='mysql', $config=false){
		if(empty($driver)) $driver='sqlite';
		$class = __NAMESPACE__."\\driver\\"."Dr".strtoupper($driver);
		$this->drivers[$driver] = new $class($config);
        return $this->drivers[$driver];
	}

	public function setting ($key=false, $value='$'){
		if($key){
			if(is_string($key)){
				if($key=='driver') $this->active = $value;
				else if($value=='$') $this->active = $key;
			}else $this->active = isset($key['driver']) ? $key['driver'] : $this->active;
		}
		return $this->driver()->setting($key, $value);
	}
    //...
    public function __call($action, $params){
        if(method_exists($this->driver(), $action))
           return call_user_func_array(array($this->driver(), $action), $params);
    }
	public function __get($key)
	{
		if(isset($this->{$key})) return $this->{$key};
		else return $this->driver()->{$key};
	}
	public function __set($key, $value)
	{
		if(isset($this->{$key})) return $this->{$key} = $value;
		else return $this->driver()->{$key} = $value;
	}
	//...
	static  $obj = 0;
	public static function this($driver=false){
		self::$obj = (!self::$obj) ? new self($driver) : self::$obj;
		return self::$obj;
	}
}
