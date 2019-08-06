<?php
/* 
 *
 * @author: Antonio Membrides Espinosa
 * @mail: amembrides@uci.cu
 * @made: 23/4/2011 
 * @update: 23/4/2011 
 * @description: This is simple and Light Driver for PostgresSQL DBSM 
 * @require: PHP >= 5.2.*, libphp5-mysql 
 * 
 */
namespace Secretary\src\server\driver;
class DrPGSQL extends DbDriver
{
	public $user;   
	public $pass;    
	public $host; 
	public $port;  

	public function __construct($config){
		$this->name = 'postgres';
		$this->user = 'postgres';
		$this->pass = 'postgres';
		$this->host = 'localhost';
		$this->port = '5432';
		parent::__construct($config);
	}

	public function query($sql){
		if(!$this->connect()) return false;
		$out = @pg_query($this->connection, $sql);
		if(!$out) {
			$this->log('ERROR: '.pg_last_error($this->connection)); 
			return false;
		}
		$this->records[] = $sql;
		$out = $this->selected($sql) ? $this->extract($out) : $out;
		$this->disconnect();
		return $out;
	}

	public function connect(){
		if(!$this->connection = @pg_connect($this->dsn())){
			$this->log("ERROR: No se pudo establecer la coneccion con el servidor con el driver:pgsql, host={$this->host}, port={$this->port}, dbname={$this->name}, user={$this->user}, password={$this->pass}");
			return false;
		}
		return true;
	}

	public function disconnect(){
		if(!@pg_close($this->connection)){
			$this->log('ERROR: No se pudo cerrar la coneccion'); 
			return false;
		}
		return true;
	}

	public function extract($count){
		if(!$count) return false;
		$out = array();
		while( $value = pg_fetch_assoc($count) ) $out[] = count($value)>1 ? $value : array_pop($value);
		return $out;
	}

	public function dsn()
	{
		return "host={$this->host} port={$this->port} dbname={$this->name} user={$this->user} password={$this->pass}";
	}
}
