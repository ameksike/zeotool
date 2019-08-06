<?php
/*
 * @author      Antonio Membrides Espinosa
 * @package    	webserver
 * @made		18/06/2015
 * @update		21/01/2016
 * @copyright  	Copyright (c) 2015-2017
 * @license    	GPL
 * @version    	1.0
 * @description Module LDAP(Lightweight Directory Access Protocol) Admin
 */
class Webserver extends ZtModule
{
    private function savePid($pid)
    {
        $pid = zt::ostype() == "windos" ? "php.exe" : $pid;
        file_put_contents($this->path . "/cfg/spid", $pid);
    }

    private function getPid()
    {
        return (int) file_get_contents($this->path . "/cfg/spid");
    }

    public function start()
    {
        if(!ZtProcess::status($this->getPid())){
			$www = $this->cfg["webserver"]["www"];
            $bin = Zt::bin($this->cfg["webserver"]["bin"]);
            $cmd =  $bin . " -S " . $this->cfg["webserver"]["host"] . ":" . $this->cfg["webserver"]["port"] . " -t " .  $www;
            $this->savePid(ZtProcess::exec($cmd));
            ZtPrompt::log($this->idiom["msg"]["start"]);
        }
        $this->statusmsg();
    }
	
    public function restart()
    {
        $this->stop();
        $this->start();
    }

    public function stop()
    {
        $pid = zt::ostype() == "windows" ? "php.exe" : $this->getPid();
        if(!ZtProcess::status($pid)){
            ZtPrompt::log($this->idiom["msg"]["stoped"]);
        }else{
            ZtProcess::kill($pid);
            ZtPrompt::log($this->idiom["msg"]["stop"]);
        }
    }

    public function status()
    {
        $pid = zt::ostype() == "windows" ? "php.exe" : $this->getPid();
        if(ZtProcess::status($pid)){
            $this->statusmsg();
        }else{
            ZtPrompt::log($this->idiom["msg"]["stoped"]);
        }
    }

    private function runCom($cmd)
    {
        $command = 'nohup ' . $cmd . ' > /media/data/dev/develop/proy/log/server.log 2>&1 & echo $!';
        echo $command . " ****";
        exec($command, $op);
        return (int)$op[0];
    }

    private function statusmsg(){
        ZtPrompt::log( $this->idiom["msg"]["status"], array(
            "host" => $this->cfg["webserver"]["host"],
            "port" => $this->cfg["webserver"]["port"],
            "pid" => $this->getPid(),
            "www" => $this->cfg["webserver"]["www"]
        ));
    }
}
