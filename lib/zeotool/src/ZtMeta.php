<?php
/*
 * @author      Antonio Membrides Espinosa
 * @package    	ZeoTool
 * @made        16/06/2015
 * @update      19/06/2015
 * @copyright   Copyright (c) 2015-2017 
 * @license     GPL v2.0
 * @version    	1.0
 */
class ZtMeta
{
    static public function lib($mod, $opt = false, $cpl = array())
    {
        $idiom = ($mod!='zeotool') ? Zt::idiom($mod) : Zt::this()->idiom;
       // $mod  = ($mod!='zeotool') ? $mod : Zt::this();
        return self::info($mod, $idiom, $opt);
    }

    static public function data($mod='zeotool')
    {
		return Zt::data('metadata', Zt::path($mod) . "/mtd/");
    }

    static public function info($mod, $idiom, $opt, $cpl = array())
    {
        $opt = $opt ? $opt : Zt::this()->cfg['zt']['style']['meta']['service'];
        $name = is_object($mod) ? lcfirst(get_class($mod)) : lcfirst($mod);
        $metainfo = '';
        $metainfo .= self::line($name, false, $idiom, $opt);
        $strlen = strlen($name);
        $opt['left'] .= self::pad($strlen) . ':';
        $opt['max'] -= $strlen + 1;
        $mod = Zt::load($mod);
        $methods = get_class_methods($mod);
        foreach ($methods as $i) {
            if (!in_array($i, ["__construct", "__get", "__set", ucfirst($name)]))
                $metainfo .= self::line($i, false, $idiom, $opt);
        }
        return $metainfo;
    }

    static public function line($attr, $value = false, $idiom = false, $opt = false, $cpl = array())
    {
        $cpl['zt'] = Zt::this();
        $opt = $opt ? $opt : Zt::this()->cfg['zt']['style']['meta']['service'];
        $idiom = $idiom ? $idiom : Zt::this()->idiom;
        $value = $value ? trim($value) : $attr;
        $value = (isset($idiom[$value]) ? $idiom[$value] : $value);
        $value = Zt::compile($value, $cpl);
        $attr = Zt::compile($attr, $cpl);
        $opt['center'] = self::pad($opt['max'], $attr) . $opt['center'];
        return $opt['left'] . $attr . $opt['center'] . $value . $opt['right'];
    }

    static public function pad($max = 10, $min = 0, $str = false)
    {
        $min = is_string($min) ? strlen($min) : $min;
        $str = is_string($str) ? $str : self::np();
        return ($max - $min) >= 0 ? (str_repeat($str, $max - $min)) : $str;
    }

    public static function np()
    {
        return Zt::isCli() ? " " : "&nbsp;";
    }
}
