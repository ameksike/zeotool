<?php
/*
 * @author      Antonio Membrides Espinosa
 * @package    	ZeoTool
 * @made        16/06/2015
 * @update      19/06/2015
 * @copyright  	Copyright (c) 2015-2017 
 * @license    	GPL v2.0
 * @version    	1.0
 */
class ZtPrompt
{
    public static function newPassword() {
        $pass1 = self::get(Zt::this()->idiom['prompt']['passw']['new'], true);
        $pass2 = self::get(Zt::this()->idiom['prompt']['passw']['re'], true);
        if ($pass1 == $pass2)
            return $pass1;
        else {
            self::show(Zt::this()->idiom['prompt']['passw']['nomatch']);
            return self::newPassword();
        }
    }

    public static function password() {
        $pass = self::get(Zt::this()->idiom['prompt']['passw']['get'], true);
        return $pass;
    }

    public static function show($data, $opt = false) {
        $pr = Zt::isVerbose() ? Zt::this()->cfg["zt"]['style']["<"] : '';
        $nl = Zt::isVerbose() ? self::nl() : '';
        switch (gettype($data)) {
            case "array":
            case "object":
                $out = $pr . print_r($data, true) . $nl;
                break;
            default:
                $out = $pr . Zt::compile($data, $opt) . $nl;
                break;
        }
        echo Zt::isCli() ? $out : (Zt::isVerbose() ? nl2br($out) : $out);
    }

    public static function get($label = "", $required = false, $validate = false) {
        $dfval = "";
        $default = "";
        $req = $required;
        if (is_string($required)) {
            $dfval = "[$required]";
            $default = $required;
            $req = false;
        }
        echo Zt::this()->cfg["zt"]['style']["<"] . "$label{$dfval}: ";
        $out = trim(fgets(STDIN));
        if (!$out && $req) {
            self::show(Zt::this()->idiom['prompt']['get']['requireField'], "error");
            return self::get($label, $required);
        }
        $out = empty($out) ? $default : $out;
        if ($validate) {
            $validate = is_array($validate) ? $validate : array($validate);
            if (!in_array($out, $validate)) {
                self::show(Zt::this()->idiom['prompt']['get']['invalid'] . implode(',', $validate) . ']');
                return self::get($label, $required, $validate);
            }
        }
        return $out;
    }

    public static function nl() {
        return Zt::isCli() ? " \n" : ' </br>';
    }

    public static function np() {
        return Zt::isCli() ? " " : '&nbsp;';
    }

    public static function log($data, $opt = false) {
        if (Zt::isVerbose())
            self::show($data, $opt);
    }

    public static function title() {
        self::log(Zt::compile(Zt::this()->idiom["title"], array("version"=>Zt::this()->version)));
    }

    public static function confirm($text, $default = false) {
        $defaul = $default ? Zt::this()->idiom['prompt']['confirm']['yes'] : Zt::this()->idiom['prompt']['confirm']['no'];
        $data = self::get($text, $defaul, array(Zt::this()->idiom['prompt']['confirm']['no'], Zt::this()->idiom['prompt']['confirm']['yes']));
        return $data === Zt::this()->idiom['prompt']['confirm']['yes'] ? true : false;
    }
}
