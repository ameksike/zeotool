<?php
/*
 * @author      $author
 * @package    	$package
 * @made        $made
 * @update      $update
 * @copyright  	Copyright (c) ZeoTool
 * @license    	GPL
 * @version    	$version
 * @description $description
 */
class $name
{
    public function __construct(){
        Zt::setVerbose(false);
    }
    
	public function index(){
		return ZtView::get("html/index.html", \$this);
	}
	
	public function home(){
		 return ZtView::tpl("home", \$this, array("title" => "Ejemplo dinamico"));
	}
}
