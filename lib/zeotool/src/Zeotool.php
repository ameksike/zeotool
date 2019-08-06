<?php

/*
 * @author		Antonio Membrides Espinosa
 * @package    	ZeoTool
 * @date		21/06/2015
 * @update		17/01/2016 
 * @copyright  	Copyright (c) 2015-2017 
 * @license    	GPL v2.0
 * @version    	1.0
 */

class Zeotool extends ZtAPI
{
    static protected $obj = 0;

    public function __construct($cfg = false)
    {
        $this->idiom = array();
        $this->cfg = $cfg;
        spl_autoload_register(__CLASS__."::autoload");
    }

    protected static function autoload($name){
        $file = __DIR__ . "/$name.php";
        if(file_exists($file)) 
            include $file;
    }

    public function install($config, $params)
    {
        $path = self::path('zeotool');
        $bind = self::tpl('bin.linux', "$path/tpl/", array('path' => $path));
        $name = isset($params[2]) ? $params[2] : 'zt';
        $local = '/usr/local/bin';
        $bin = "$local/$name";
        file_put_contents($bin, $bind);
        //return exec("chmod -x $bin");
        return chmod($bin, '777') ? self::this()->idiom['zt']['error']['install']['success'] : self::this()->idiom['zt']['error']['install']['failure'];
    }

    public function uninstall($config, $params)
    {
        $name = isset($params[2]) ? $params[2] : 'zt';
        $local = '/usr/local/bin';
        $bin = "$local/$name";
        return unlink($bin) ? self::this()->idiom['zt']['error']['uninstall']['success'] : self::this()->idiom['zt']['error']['uninstall']['failure'];
    }

    protected function scann($path = '.')
    {
        $out = '';
        $dir = dir($path);
        $nod = [".", "..", "zeotool"];
        while (false !== ($entry = $dir->read())) {
            if (!in_array($entry, $nod)) {
                $out .= ZtMeta::lib($entry);
            }
        }
        $dir->close();
        return $out;
    }

    protected function getServices()
    {
        $helpc = ZtPrompt::nl();
        $helpc .= ZtMeta::line('help', false, $this->idiom);
        $helpc .= ZtMeta::line('install', false, $this->idiom);
        $helpc .= ZtMeta::line('uninstall', false, $this->idiom);
        if (isset($this->cfg['zt']["autoload"]))
            if ($this->cfg['zt']["autoload"])
                $helpc .= $this->scann($this->path . $this->cfg['zt']["dir"]['lib']);
        return $helpc;
    }
}
/*
 * @author		Antonio Membrides Espinosa
 * @package    	ZeoTool
 * @made		15/01/2016
 * @copyright  	Copyright (c) 2015-2017 
 * @license    	GPL v2.0
 * @version    	1.0
 */
class Zt extends Zeotool
{
    public function __construct($cfg = false)
    {
        error_reporting(E_ALL);
        set_error_handler("Zt::onError");
        set_exception_handler("Zt::onException");
        parent::__construct($cfg);
    }

    static public function onError($errno, $errstr, $errfile, $errline)
    {
        self::log(" $errno: $errstr >> line: $errline >> file: $errfile ", "error");
    }

    static public function onException($exc)
    {
        self::log(" {$exc->getMessage()} >> line: {$exc->getLine()} >> file: {$exc->getFile()} ", "exception");
    }
}
