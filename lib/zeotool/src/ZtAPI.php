<?php
/*
 * @author      Antonio Membrides Espinosa
 * @package    	ZeoTool
 * @made        21/06/2015
 * @update      23/01/2016 
 * @copyright  	Copyright (c) 2015-2017 
 * @license    	GPL v2.0
 * @version    	1.0
 */
class ZtAPI extends ZtModule
{
    public $idiom = false;
    public $path = false;

    public static function reconfigure($path, $argc, $argv){
        $config = self::data("config", $path . "cfg/");
        $config = self::data("config", $path . "../../cfg/") + $config;
        $config["zt"]["dir"]["root"] = realpath($path . "../../");
        $config["zt"]["request"] = self::request($argc, $argv);
        self::this()->idiom = self::data($config['zt']['language'], $config["zt"]["dir"]["root"] . $config['zt']["dir"]["idiom"]);
        self::this()->idiom += self::data($config['zt']['language'], $path . $config['zt']["dir"]["idiom"]);
        self::this()->path = $path . "../../";
        self::this()->mtd = self::data('metadata', "$path/mtd/");
        self::this()->cfg = $config;
        return $config;
    }

    public static function lib($name = false){
        $class = self::load($name);
        if ($class) {
            $path = realpath(self::this()->path . self::this()->cfg['zt']["dir"]['lib'] . "{$name}/");
            $config = self::this()->cfg + self::data("config", $path . "/" . self::this()->cfg['zt']["dir"]['config']);
            if( class_exists( $class ) ) {
				$obj = new $class($config);
				$obj->cfg = $config;
				$obj->idiom = self::idiom($name);
				$obj->mtd = self::data('metadata', "$path/mtd/");
				$obj->path = $path;
				$obj->root = realpath(self::this()->path);
				return $obj;
			}
        }
        ZtPrompt::log(self::this()->idiom["zt"]["error"]["nodomine"], array("class" => $class));
        return false;
    }

    public static function load($name){
        if (is_object($name)) return get_class($name);
        $class = ucfirst($name);
        if (class_exists($class)) return $class;
        $file = self::this()->path . self::this()->cfg['zt']["dir"]['lib'] . "$name/src/$class.php";
        if (!file_exists($file)) {
            $file = self::this()->path . self::this()->cfg['zt']["dir"]['lib'] . "$name/src/server/$class.php";
            if (!file_exists($file)) {
                $file = self::this()->path . self::this()->cfg['zt']["dir"]['lib'] . "$name/$class.php";
                if (!file_exists($file)) return false;
            }
        }
        include_once $file;
        return $class;
    }

    public static function excecute($controller, $action, $params){
        $class = $controller ? (is_object($controller) ? get_class($controller) : ucfirst($controller)) : "ZeoTool";
        $obj = $controller ? self::lib($controller) : self::this();
        ZtPrompt::title();
        if (method_exists($obj, $action)) {
            ZtPrompt::log(self::this()->idiom["zt"]["msg"]["exec"], array("class" => $class, "action" => $action));
            $out = call_user_func_array(array($obj, $action), $params);
            return $out;
        } else {
            ZtPrompt::log(self::this()->idiom["zt"]["error"]["noservice"], array("class" => $class, "action" => $action));
            return false;
        }
    }

    public static function log($msg, $name = "info"){
        $path = self::this()->path ? self::this()->path : false;
        $path = $path ? $path . self::this()->cfg['zt']['dir']['log'] : realpath(__DIR__ . "/../../../") . '/log/';
        $file = realpath($path) . "/$name.log";
        $out = file_put_contents($file, date('Y/m/d-H:i:s') . " >> $msg  \n", FILE_APPEND);
        if (!$out && !empty(self::this()->idiom)) ZtPrompt::log(self::this()->idiom["zt"]["error"]["write"], array("file" => $file));
    }

    public static function request($argc, $argv){
        if (self::isCli()) {
            $opt = self::reqExtact(str_replace(':', '/', $argc > 1 ? $argv[1] : false));
            $opt['bin'] = $argv[0];
            for ($i = 2; $i < $argc; $i++)
                $opt['params'][] = $argv[$i];
            return $opt;
        } else {
            $_SERVER['PATH_INFO'][0] = "";
            $opt = self::reqExtact($_SERVER['PATH_INFO']);
            $opt['bin'] = 'index.php';
            return $opt;
        }
    }

    public static function idiom($mod = false){
        if (!$mod) return self::this()->idiom;
        $idiom = self::data(self::this()->cfg['zt']['language'], self::path($mod) . "/" . self::this()->cfg['zt']["dir"]["idiom"]);
        return $idiom + self::this()->idiom;
    }

    public static function path($mod = '', $valid = true){
        $entry = is_object($mod) ? lcfirst(get_class($mod)) : lcfirst($mod);
        $paths = self::this()->path . self::this()->cfg['zt']['dir']['lib'] . "$entry";
        return $valid ? realpath($paths) : $paths;
    }

    public static function compile($str, $data = false){
        if (is_array($data)) extract($data);
        ob_start();
        $str = str_replace('"', '\"', $str);
        eval(' ?><?php echo "' . $str . ' ";?><?php ');
        return ob_get_clean();
    }

    public static function debug($data, $trace = false, $ex = false, $break = true){
        if (!self::isCli()) echo '<pre>';
        if ($ex) var_dump($data);
        else print_r($data);
        if ($trace) {
            $opt = ($trace === "args") ? DEBUG_BACKTRACE_PROVIDE_OBJECT : DEBUG_BACKTRACE_IGNORE_ARGS;
            $trace = debug_backtrace($opt);
            unset($trace[0]);
            print_r($trace);
        }
        if ($break) die;
    }

    public static function isCli(){
        return (php_sapi_name() === 'cli');
    }

    public static function isVerbose(){
        return self::this()->cfg['zt']['verbose'];
    }

    public static function setVerbose($value = false){
        return self::this()->cfg['zt']['verbose'] = $value;
    }

    public static function data($name = "config", $path = ""){
        $file = $path . $name . ".json";
        if (!file_exists($file)) return array();
        $file = realpath($file);
        $out = json_decode(file_get_contents($file), true);
        $idiom = isset(self::this()->idiom['zt']) ? self::this()->idiom['zt'] : false;
        if (!$out) {
            if ($idiom) {
                switch (json_last_error()) {
                    case JSON_ERROR_NONE:
                        break;
                    case JSON_ERROR_DEPTH:
                        self::log($idiom['error']['json']['depth'] . " >> file: $file ", "error");
                        break;
                    case JSON_ERROR_STATE_MISMATCH:
                        self::log($idiom['error']['json']['mismatch'] . " >> file: $file ", "error");
                        break;
                    case JSON_ERROR_CTRL_CHAR:
                        self::log($idiom['error']['json']['ctrlchar'] . " >> file: $file ", "error");
                        break;
                    case JSON_ERROR_SYNTAX:
                        self::log($idiom['error']['json']['syntax'] . " >> file: $file ", "error");
                        break;
                    case JSON_ERROR_UTF8:
                        self::log($idiom['error']['json']['utf8'] . " >> file: $file ", "error");
                        break;
                    default:
                        self::log($idiom['error']['json']['unknown'] . " >> file: $file ", "error");
                        break;
                }
            } else self::log(" JSON syntax >> file: $file ", "error");
        }
        return (!is_array($out)) ? array() : $out;
    }

    public static function tpl($name, $path = '', $data = false) {
        $file = $path . $name . ".tpl";
        if (!file_exists($file)) return false;
        return self::compile(file_get_contents($file), $data);
    }

    public static function this($cfg = false) {
        static::$obj = (!static::$obj) ? new static($cfg) : static::$obj;
        return static::$obj;
    }

    protected static function reqExtact($opt = ''){
        $opt = is_string($opt) ? explode("/", $opt) : array();
        $pos = (count($opt) == 1) ? 0 : 1;
		$opt[$pos] = trim($opt[$pos]);
        $action = empty($opt) ? "help" : (!empty($opt[$pos]) ? $opt[$pos] : "help");
		$opt[0] = trim($opt[0]);
        $controller = empty($opt) ? false : ($pos ? (!empty($opt[0]) ? $opt[0] : false) : false);
        if ($pos > 0) unset($opt[0], $opt[1]);
        else unset($opt[0]);
        return array("controller" => $controller, "action" => $action, "params" => $opt);
    }

    public static function bin($name){
        $re = '';
        $bin = '';
        $cfg = self::this()->cfg['scope']['bin'][$name];
        if(isset($cfg['re'])){
            $re = self::bin($cfg['re']);
            return " $re {$cfg['index']} ";
        }else{
            if(isset($cfg['all'])){
                $bin = $cfg['all'];
            }else {
				$ostype = self::ostype();
                $bin = isset($cfg[$ostype] ) ? $cfg[$ostype] : "";
            }
        }
        return " $re $bin ";
    }
	
	public static function ostype(){
		$uname = php_uname('s');
		$ostype = "";
		if (preg_match('/Win/', $uname)) $ostype = "Windows";
        elseif ((preg_match('/Mac/', $uname)) || (preg_match('/PPC/', $uname))) $ostype = "Macintosh";
        elseif (preg_match('/Debian/', $uname)) $ostype = "Linux";
        elseif (preg_match('/Linux/', $uname)) $ostype = "Linux";
        elseif (preg_match('/FreeBSD/', $uname)) $ostype = "FreeBSD";
        elseif (preg_match('/SunOS/', $uname)) $ostype = "SunOS";
        elseif (preg_match('/IRIX/', $uname)) $ostype = "IRIX";
        elseif (preg_match('/BeOS/', $uname)) $ostype = "BeOS";
        elseif (preg_match('/OS\/2/', $uname)) $ostype = "OS/2";
        elseif (preg_match('/AIX/', $uname)) $ostype = "AIX";
		return strtolower($ostype);
	}

    public static function ds(){
        //...  linux => \ , windows => /
        return DIRECTORY_SEPARATOR;
    }

    public static function sle(){
        //...  linux => so, windows => dll
        return PHP_SHLIB_SUFFIX;
    }

    public static function ps(){
        //...  linux => : , windows => ;
        return PATH_SEPARATOR;
    }
}
