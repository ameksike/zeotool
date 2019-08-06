<?php

/*
 * @author		Antonio Membrides Espinosa
 * @package    	ZeoTool
 * @date		21/06/2015
 * @copyright  	Copyright (c) 2015-2015 XETID
 * @license    	GPL
 * @version    	1.0
 */

class BuildPhar
{
    private $sourceDirectory = null;
    private $stubFile = null;
    private $outputDirectory = null;
    private $pharFileName = null;

    /**
     * @param $sourceDirectory // This is the directory where your project is stored.
     * @param $stubFile // Name the entry point for your phar file. This file have to be within the source directory.
     * @param null $outputDirectory // Directory where the phar file will be placed.
     * @param string $pharFileName // Name of your final *.phar file.
     */
    public function __construct($sourceDirectory, $stubFile, $outputDirectory = null, $pharFileName = 'myPhar.phar')
    {
        if ((file_exists($sourceDirectory) === false) || (is_dir($sourceDirectory) === false)) {
            throw new Exception('No valid source directory given.');
        }
        $this->sourceDirectory = $sourceDirectory;
        if (file_exists($this->sourceDirectory . '/' . $stubFile) === false) {
            throw new Exception('Your given stub file doesn\'t exists.');
        }
        $this->stubFile = $stubFile;
        if (empty($pharFileName) === true) {
            throw new Exception('Your given output name for your phar-file is empty.');
        }
        $this->pharFileName = $pharFileName;
        if ((empty($outputDirectory) === true) || (file_exists($outputDirectory) === false) || (is_dir($outputDirectory) === false)) {
            if ($outputDirectory !== null) {
                trigger_error('Your output directory is invalid. We set the fallback to: "' . dirname(__FILE__) . '".', E_USER_WARNING);
            }
            $this->outputDirectory = dirname(__FILE__);
        } else {
            $this->outputDirectory = $outputDirectory;
        }
        $this->prepareBuildDirectory();
        $this->buildPhar();
    }

    private function prepareBuildDirectory()
    {
        if (preg_match('/.phar$/', $this->pharFileName) == FALSE) {
            $this->pharFileName .= '.phar';
        }
        if (file_exists($this->pharFileName) === true) {
            unlink($this->pharFileName);
        }
    }

    private function buildPhar()
    {
        $phar = new Phar($this->outputDirectory . '/' . $this->pharFileName);
        $phar->buildFromDirectory($this->sourceDirectory);
        $phar->setDefaultStub($this->stubFile);
        //$phar->compressFiles(Phar::GZ);
    }
}
/*-$readonly = ini_get('phar.readonly');
ini_set('phar.readonly', "Off");
self::show("sintaxis: {$this->cfg['zt']['bin']} install ");
include_once __DIR__."/BuildPhar.php";
$builder = new BuildPhar( __DIR__.'/..', 'index.php', __DIR__.'/../bin', 'zeotool.phar' );
ini_set('phar.readonly', $readonly);*/
