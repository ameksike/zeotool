<?php

/*
 * @author	Antonio Membrides Espinosa
 * @package    	Ldap
 * @date	18/06/2015
 * @update	18/06/2015
 * @copyright  	Copyright (c) 2015-2015 XETID
 * @license    	GPL
 * @version    	1.0
 */

class LdapAdmin
{
    public function url2dn($url)
    {
        $dn = '';
        $url = explode('.', $url);
        $con = count($url);
        foreach ($url as $k => $i) {
            $d = ($k < $con - 1) ? ',' : '';
            $dn .= "dc=$i$d";
        }
        return $dn;
    }

    public function encrypt($pass)
    {
        return exec("slappasswd -s $pass");
    }

    public function includeSchema($schema, $path = '')
    {
        $out = '';
        foreach ($schema as $i)
            $out .= "include {$path}$i.schema \n";
        return $out;
    }
}
