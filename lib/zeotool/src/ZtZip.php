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
class ZtZip
{
    public static function unpack($pack, $path)
    {
        $zip = new ZipArchive;
        $zip->open($pack);
        $zip->extractTo($path);
        $zip->close();
    }

    public static function pack($dir, $zip){
        if(is_string($zip)){
            $zia = new ZipArchive();
            $zia->open($zip, ZipArchive::CREATE);
            $zip = $zia;
            chdir(realpath($dir . Zt::ds() . "../" ));
            $dir = basename($dir);
        }
        if ($dh = opendir($dir)) {
            $zip->addEmptyDir($dir);
            while (($file = readdir($dh)) !== false) {
                if(!is_file($dir . zt::ds() . $file)){
                    if( ($file !== ".") && ($file !== "..") ){
                        self::pack($dir . zt::ds() . $file, $zip);
                    }
                }else{
                    $zip->addFile($dir . zt::ds() . $file);
                }
            }
        }
    }
}
