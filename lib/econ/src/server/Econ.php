<?php
/*
 * @author      Antonio Membrides Espinosa
 * @package    	econ
 * @made        11/10/2016
 * @update      11/10/2016
 * @copyright  	Copyright (c) ZeoTool
 * @license    	GPL
 * @version    	0.1
 * @description Gestion economica
 */
include __DIR__.DIRECTORY_SEPARATOR."../../lib/loader/Main.php";
use Loader\Main as Loader;
use LQLS\src\Main as LQLS;
class Econ
{
    public function __construct(){
        Zt::setVerbose(false);
		Loader::active(array(
			'Secretary'=>__DIR__.DIRECTORY_SEPARATOR."../../lib/secretary",
			'LQL'=>__DIR__.DIRECTORY_SEPARATOR."../../lib/lql",
			'LQLS'=>__DIR__.DIRECTORY_SEPARATOR."../../lib/lqls"
		));
    }
    
	public function index(){
		return ZtView::get("html/index.html", $this);
	}

	public function inspectDB(){
		$this->cfg["econ"]["db"]["path"] = $this->path . DIRECTORY_SEPARATOR. $this->cfg["econ"]["db"]["path"];
		LQLS::setting($this->cfg["econ"]["db"]);
		return LQLS::create()->execute("SELECT tbl_name as name FROM sqlite_master WHERE type = 'table'");
	}

	public function buildb(){
		LQLS::setting($this->cfg["econ"]["db"]);
		$this->cfg["econ"]["db"]["path"] = $this->path . DIRECTORY_SEPARATOR. $this->cfg["econ"]["db"]["path"];
		$out = LQLS::create()->flush(realpath($this->cfg["econ"]["db"]["path"]."create.sql"));
	}
	public function home(){
		$this->cfg["econ"]["db"]["path"] = $this->path . DIRECTORY_SEPARATOR. $this->cfg["econ"]["db"]["path"];
		LQLS::setting($this->cfg["econ"]["db"]);
		$out = LQLS::create()->select("*")->from("person")->flush();
		$out["title"] = "Ejemplo dinamico";
		$out["data"] = "no data";
		return ZtView::tpl("home", $this, $out);
	}

	public function mshome(){

		echo "<pre>";
		LQLS::setting($this->cfg["econ"]["dbms"]);
		return LQLS::create()->execute("SELECT * FROM dbo.con_comprobante"); 
	}
}
 