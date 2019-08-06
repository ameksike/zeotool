<?php
/* 
 *
 * @author: Antonio Membrides Espinosa
 * @mail: amembrides@uci.cu
 * @made: 23/4/2011 
 * @update: 23/4/2011 
 * @description: This is simple and Light Driver for MySql DBSM 
 * @require: PHP >= 5.2.*, libphp5-mysql 
 * 
 */
namespace Secretary\src\server\driver;
class DrMYSQL extends DbDriver
{
	public $user;   
	public $pass;    
	public $host; 
	public $port;  

	public function __construct($config){
		$this->name = 'default';
		$this->user = 'root';   
		$this->pass = 'root';    
		$this->host = 'localhost'; 
		$this->port = '3306';  
		parent::__construct($config);
	}

	public function query($sql){
		if(!$this->connect()) return false;
		$out = @mysql_query($sql);	
		if(!$out) {
			$this->log('ERROR: '. mysql_error($this->connection)); 
			return false;
		}
		$this->records[] = $sql;
		$out = $this->selected($sql) ? $this->extract($out) : $out;
		$this->disconnect();
		return $out;
	}

	public function connect(){
		if(!$this->connection = @mysql_connect($this->host, $this->user, $this->pass)){
			$this->log("ERROR: No se pudo establecer la coneccion con el servidor con el: driver:mysql, host={$this->host}, user={$this->user}, password={$this->pass}"); 
			return false;
		}
		if(!@mysql_select_db($this->name, $this->connection)) 	{
			$this->log("ERROR: No se pudo establecer la coneccion con la BD denominada {$this->name}"); 
			return false;
		}		
		return true;
	}

	public function disconnect(){
		if(!@mysql_close($this->connection)){
			//... throw new \Exception("ERROR: No se pudo cerrar la coneccion");
			$this->log('ERROR: No se pudo cerrar la coneccion');  
			return false;
		}
		return true;
	}

	public function extract($count){
		if(!$count) return false;
		$out = array();
		while( $value = mysql_fetch_assoc($count) ) $out[] = count($value)>1 ? $value : array_pop($value);
		return $out;
	}
}
