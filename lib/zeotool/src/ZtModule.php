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
class ZtModule
{
    public function help()
    {
        $out = '\n';
        foreach ($this->cfg['help'] as $k => $i) {
            $out .= ZtMeta::line($k, $i, $this->idiom, $this->cfg['zt']['style']['meta']['option'], array('mod' => $this));
        }
        return $out;
    }

    protected function getServices()
    {
        $out = '\n';
        $out .= ZtMeta::lib($this);
        return $out;
    }
    
	public function __get($attr){
		switch($attr){
			case 'modname': return lcfirst(get_class($this));
			default: return isset($this->mtd[$attr]) ? $this->mtd[$attr] : false;
		}
	}
}
