<?php
/*
 * @author      Antonio Membrides Espinosa
 * @package    	ZeoTool
 * @made        16/06/2015
 * @update      19/06/2015
 * @copyright   Copyright (c) 2015-2017 
 * @license    	GPL v2.0
 * @version    	1.0
 */
class ZtView
{
    public static function get($view, $mod = "zeotool")
    {
        $file = $view;
        if (!file_exists($file)) {
            $path = Zt::path($mod);
            $file = "$path/src/client/$view";
        }
        return file_get_contents($file);
    }

    public static function tpl($view, $mod = "zeotool", $data = array())
    {
        $path = Zt::path($mod) . "/src/client/tpl/";
        return Zt::tpl($view, $path, $data);
    }
}
