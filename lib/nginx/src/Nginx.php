<?php

/*
 * @author		Antonio Membrides Espinosa
 * @package    	Ldap
 * @date		18/06/2015
 * @update		21/01/2016
 * @copyright  	Copyright (c) 2015-2017
 * @license    	GPL v2.0
 * @version    	1.0
 * @description Module LDAP(Lightweight Directory Access Protocol) Admin
 */

class Nginx extends ZtModule
{
    public function addVirtualHost()
    {
        $demvh = ZtPrompt::get($this->idiom['msg']['demvh'], $this->cfg['nginx']['demvh']);
        $content = Zt::tpl('vh', $this->path . '/tpl/', array(
            'port' => ZtPrompt::get($this->idiom['msg']['port'], $this->cfg['nginx']['port']),
            'name' => ZtPrompt::get($this->idiom['msg']['servername'], $this->cfg['nginx']['servername']),
            'www' => ZtPrompt::get($this->idiom['msg']['www'], $this->cfg['nginx']['www'])
        ));
        return $this->savehv($content, $demvh);
    }

    public function delVirtualHost()
    {
        $demvh = ZtPrompt::get($this->idiom['msg']['delvh'], $this->cfg['nginx']['demvh']);
        $available = $this->cfg['nginx']['cfg'] . 'sites-available';
        $enabled = $this->cfg['nginx']['cfg'] . 'sites-enabled';
        $out = (file_exists("$enabled/$demvh.conf")) ? exec("rm $enabled/$demvh.conf") : $out;
        $out = (file_exists("$available/$demvh.conf")) ? exec("rm $available/$demvh.conf") : $out;
        $out = exec($this->cfg['nginx']['service'] . " restart");
        if ($out) {
            ZtPrompt::show($this->idiom['success']);
            ZtPrompt::show($out);
        } else return $this->idiom['failure'];
    }

    public function addInverseProxyLb()
    {
        $demvh = ZtPrompt::get($this->idiom['msg']['demvh'], $this->cfg['nginx']['demvh']);
        $listip = ZtPrompt::get($this->idiom['msg']['listip'], true);
        $listip = explode(' ', $listip);
        $lstips = '';
        foreach ($listip as $i)
            $lstips .= "server $i; \n";

        $content = Zt::tpl('pilb', $this->path . '/tpl/', array(
            'port' => ZtPrompt::get($this->idiom['msg']['port'], $this->cfg['nginx']['port']),
            'name' => ZtPrompt::get($this->idiom['msg']['servername'], $this->cfg['nginx']['servername']),
            'www' => ZtPrompt::get($this->idiom['msg']['www'], $this->cfg['nginx']['www']),
            'server' => $lstips,
            'dem' => $demvh
        ));
        return $this->savehv($content, $demvh);
    }

    public function addInverseProxySp()
    {
        $demvh = ZtPrompt::get($this->idiom['msg']['demvh'], $this->cfg['nginx']['demvh']);
        $content = Zt::tpl('pisp', $this->path . '/tpl/', array(
            'port' => ZtPrompt::get($this->idiom['msg']['port'], $this->cfg['nginx']['port']),
            'name' => ZtPrompt::get($this->idiom['msg']['servername'], $this->cfg['nginx']['servername']),
            'url' => ZtPrompt::get($this->idiom['msg']['url'], $this->cfg['nginx']['url']),
        ));
        return $this->savehv($content, $demvh);
    }

    protected function savehv($content, $demvh)
    {
        $available = $this->cfg['nginx']['cfg'] . 'sites-available';
        $enabled = $this->cfg['nginx']['cfg'] . 'sites-enabled';
        $out = file_put_contents("$available/$demvh.conf", $content);
        $out = (file_exists("$enabled/$demvh.conf")) ? exec("rm $enabled/$demvh.conf") : $out;
        $out = exec("ln -s $available/$demvh.conf $enabled/$demvh.conf");
        $out = exec($this->cfg['nginx']['service'] . " restart");
        if ($out) {
            ZtPrompt::show($this->idiom['success']);
            ZtPrompt::show($out);
        } else return $this->idiom['failure'];
    }

    public function delInverseProxy()
    {
        $this->delVirtualHost();
    }
}
