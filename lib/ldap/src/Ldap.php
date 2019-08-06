<?php

/*
 * @author		Antonio Membrides Espinosa
 * @package    	Ldap
 * @date		18/06/2015
 * @update		21/01/2016
 * @copyright  	Copyright (c) 2015-2017 
 * @license    	GPL
 * @version    	1.0
 * @description Module LDAP(Lightweight Directory Access Protocol) Admin
 */

class Ldap extends ZtModule
{
    public function __construct()
    {
        include Zt::path($this) . "/src/LdapAdmin.php";
        $this->manager = new LdapAdmin;
    }

    protected function promptSlapdConf()
    {
        $path = Zt::this()->path;
        ZtPrompt::show($this->idiom['msg']['init']);
        $dnBase = ZtPrompt::get($this->idiom['msg']['getBaseURL'], $this->cfg['ldap']['dnbase']);
        $dnBase = $this->manager->url2dn($dnBase);
        ZtPrompt::show("Dn: $dnBase");
        $dbtype = ZtPrompt::get($this->idiom['msg']['dbtype'], $this->cfg['ldap']['dbtype']['default'], $this->cfg['ldap']['dbtype']['range']);
        ZtPrompt::show("dbtype: $dbtype");
        $rootdn = ZtPrompt::get($this->idiom['msg']['getRootdn'], $this->cfg['ldap']['rootdn']);
        ZtPrompt::show("rootdn: $rootdn");
        $pathtpl = $path . $this->cfg['ldap']['dir']['tpl'];
        $content = Zt::tpl('slapd.conf', $pathtpl, array(
            'schemas' => $this->manager->includeSchema($this->cfg['ldap']['schema'], $this->cfg['ldap']['dir']['schema']),
            'pass' => $this->manager->encrypt(ZtPrompt::newPassword()),
            'user' => $rootdn,
            'base' => $dnBase,
            'dbtype' => $dbtype,
            'ppolicy' => 'tatat'
        ));
        if (file_put_contents($this->cfg['ldap']['dir']['slapd.conf'], $content)) return $this->idiom['success'];
        return $this->idiom['failure'];
    }

    public function installTPL()
    {
        $out = exec("ldapadd -Y EXTERNAL -H ldapi:/// -f /etc/ldap/schema/cosine.ldif");
        $out .= exec("ldapadd -Y EXTERNAL -H ldapi:/// -f /etc/ldap/schema/nis.ldif");
        $out .= exec("ldapadd -Y EXTERNAL -H ldapi:/// -f /etc/ldap/schema/inetorgperson.ldif");
        return $out;
    }

    public function config()
    {
        return $this->promptSlapdConf();
    }

    public function addOU()
    {

    }

    public function delOU()
    {

    }

    public function newPasswd()
    {
        return $this->manager->encrypt(ZtPrompt::newPassword());
    }

    public function rePasswd()
    {

    }

    public function sslSupport()
    {

    }

    public function deleteDB()
    {
        $files = array_diff(scandir(__DIR__), array('.', '..',));
        //unlink("$dir/$file");
        print_r($files);
    }

    public function server($cmd = 'status')
    {
        $out = exec($this->cfg['ldap']['service'] . " $cmd");
        return $out;
    }
}
