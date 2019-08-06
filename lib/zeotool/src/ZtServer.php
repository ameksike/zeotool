<?php
/*
 * @author		Antonio Membrides Espinosa
 * @package    	ZeoTool
 * @made		21/06/2015
 * @update		21/01/2016
 * @copyright  	Copyright (c) 2015-2017 
 * @license    	GPL v2.0
 * @version    	1.0
 */
include __DIR__ . "/ZtModule.php";
include __DIR__ . "/ZtAPI.php";
include __DIR__ . "/Zeotool.php";
if (!Zt::isCli()) {
    $argc = false;
    $argv = false;
}
$cfg = Zt::reconfigure(__DIR__ . '/../', $argc, $argv);
$out = Zt::excecute(
    $cfg["zt"]["request"]["controller"],
    $cfg["zt"]["request"]["action"],
    $cfg["zt"]["request"]["params"]
);
ZtPrompt::show($out);
