<?php
/*
 *
 * @author: Antonio Membrides Espinosa
 * @mail: amembrides@uci.cu
 * @made: 23/4/2011
 * @update: 23/4/2011
 * @description: This is simple and Light Driver for SQLite DBSM
 * @require: PHP >= 5.2.*, libphp5-sqlite
 *
 */
namespace Secretary\src\server\driver;
class DrSQLITE extends DbDriver
{
    public $dbm;
    public $path;

    public function __construct($config)
    {
        $this->path = ':memory:';
        $this->dbm = false;
        $this->ext = '.db';
        parent::__construct($config);
    }

    public function setting ($key=false, $value=false){
        parent::setting($key, $value);
        $file = $this->path.$this->name.$this->ext;
		$this->path = $file;
        //$this->path = ($this->path != ':memory:' and file_exists($file)) ? $file : ':memory:';
    }
    public function query($sql)
    {
		$this->connect();
		$out = false;
		if($this->selected($sql) ){
			$stmt = @$this->dbm->prepare($sql);
			$out = @$stmt->execute();
			$out = $this->extract($out);
		}else{
			$out = @$this->dbm->exec($sql);
			if(!$out) echo " ERROR: ". $this->dbm->lastErrorCode()." -->> ". $this->dbm->lastErrorMsg()." in: $sql <br>";
		}
		if (!$out) {
            $this->log('ERROR: ' . $this->dbm->lastErrorMsg());
            return false;
        }
		$this->records[] = $sql;
		$this->disconnect();
		return $out;
    }

    public function connect()
    {
		$this->dbm = new \SQLite3($this->path);
    }

    public function disconnect()
    {
        $this->dbm->close();
    }

    public function extract($results){
        if(!$results) return false;
        $res = array();
        while ($res[] = $results->fetchArray(SQLITE3_ASSOC));
        return $res;
    }
}
