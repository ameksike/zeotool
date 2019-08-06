<?php
/*
 *
 * @author: Antonio Membrides Espinosa
 * @mail: amembrides@uci.cu
 * @made: 20/10/2016
 * @update: 23/4/2016
 * @description: This is simple and Light Driver for MSSQL Server DBSM
 * @require: PHP >= 5.5.15, libphp5-sqlsrv
 *
 */
namespace Secretary\src\server\driver;
class DrSQLSRV extends DbDriver
{
    public $user;
    public $pass;
    public $host;
    public $port;

    public function __construct($config){
        $this->name = 'postgres';
        $this->auth = "sqlserver";
        $this->user = 'sa';
        $this->pass = '';
        $this->host = 'localhost';
        $this->port = '1433';
        $this->instance = "";
        parent::__construct($config);
    }

    public function query($sql){
        if(!$this->connect()) return false;
        $out = @sqlsrv_query($this->connection, $sql);
        if($out === false) {
            $erros = sqlsrv_errors();
            $this->log('ERROR: '. $erros[0]);
            return false;
        }
        $this->records[] = $sql;
        $out = $this->selected($sql) ? $this->extract($out) : $out;
        $this->disconnect();
        return $out;
    }

    public function connect(){
        $this->connection = sqlsrv_connect( $this->dsn(), $this->conncfg() );
        if( $this->connection === false ) {
            $error =  sqlsrv_errors();
            foreach ($error as $e) $this->log($e["message"]);
            $this->log("ERROR: No se pudo establecer la coneccion con el msdns: ".$this->dsn());
            return false;
        }
        return true;
    }

    public function disconnect(){
        if(!@sqlsrv_close($this->connection)){
            $this->log('ERROR: No se pudo cerrar la coneccion');
            return false;
        }
        return true;
    }

    public function extract($count){
        if(!$count) return false;
        $out = array();
        while( $row = sqlsrv_fetch_array( $count, SQLSRV_FETCH_ASSOC) )$out[] =  $row;
        return $out;
    }

    public function dsn()
    {   //... host\instance, port
        $dsn = $this->host;
        if(!empty($this->instance)){
            $dsn .= "\\".$this->instance;
        }
        $dsn .= ", ".$this->port;
        return $dsn;
    }

    public function conncfg(){
        $cfg = array(
            "Database"=>$this->name
        );
        if($this->auth == "sqlserver"){
            $cfg["UID"] = $this->user;
            $cfg["PWD"] = $this->user;
        }
        return $cfg;
    }

    public function inf(){
        if( $clientInfo = sqlsrv_client_info($this->connection )) {
          return $clientInfo;
        } else {
            $this->log('Error al recuperar la información del cliente.');
        }
    }
}
