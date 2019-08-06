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
class ZtProcess
{

    public static function exec($cmd, $logfile = false)
    {
        $logfile = $logfile ? $logfile : realpath(__DIR__ . "/../../../") . '/log/process.log';
		switch(Zt::ostype()){
			case "linux": $command = 'nohup ' . $cmd . ' > ' . $logfile . ' 2>&1 & echo $!'; break;
            case "windows": $command = 'start "" /B ' . $cmd . ' > ' . $logfile . ' 2>&1 & echo $!'; break;
		}
		$out = exec($command, $op);
		return isset($op[0]) ? (int)$op[0] : false;
    }

    public static function kill($pid = false)
    {
        $command = "";
		switch(Zt::ostype()){
            case "linux":
                switch (gettype($pid)){
                    case "string":
                        $command = "pkill -kill $pid ";
                        break;
                    default:
                        $command = "kill -kill $pid ";
                        break;
                }
             break;
            case "windows":
                switch (gettype($pid)){
                    case "string":
                        $command = "taskkill /F /IM  $pid ";
                        break;
                    default:
                        $command = "taskkill /F /PID  $pid ";
                        break;
                }
        }
        $out = exec($command . ' 2>&1 & echo $!', $op);
        if (!isset($op[1])) return false;
        else return true;
    }

    public static function status($pid = false)
    {
        switch(Zt::ostype()){
            case "linux":
                if (!$pid) return false;
                $command = 'ps -p ' . $pid;
                exec($command, $op);
                return (isset($op[1]));
            break;
            case "windows":
                $command = "TASKLIST /FI \"imagename eq $pid\" ";
                exec($command, $op);
                return (count($op)>4);
            break;
        }
        return false;
    }
}
