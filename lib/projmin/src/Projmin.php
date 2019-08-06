<?php
/*
 * @author      Antonio Membrides Espinosa
 * @package    	Projmin
 * @made        18/06/2015
 * @update      21/01/2016
 * @copyright  	Copyright (c) 2015-2017
 * @license    	GPL
 * @version    	1.0
 * @description Module for project administration over ZeoTool
 */
class Projmin extends ZtModule
{
	public $version = '0.1.2';
	public function create($name=false, $template=false){
        $template = $this->requireTpl($template);
		$inf = $this->load($template); 
		$inf['prompt']['name']['value'] = $name ? $name : $inf['prompt']['name']['value'];
		$data = $this->getData($inf);
		$data['made'] = date('d/m/Y');
		$data['package'] = $data['name'];
		$data['name'] =  ucfirst($data['name']);
		$data['update'] = $data['made'];
		$data['copyright'] = "Copyright (c) 2015-2017";
		$data['license'] = "GPL";	
		$data['require'] = "[]";
		$path = Zt::path($data['name'], false)."/";
		$this->buildDir($inf, $path);
		$this->buildFile($inf, $path, $data);
		return $this->modules();
	}
	
	public function delete($lib=false){
        $lib = $this->requireModule($lib);
		if($lib){
			$path = Zt::path($lib);
			$this->rmdir($path);
		}else ZtPrompt::log($this->idiom["msg"]["error"]["rmdir"], array("name" => $lib));
		return $this->modules();
	}
	
	public function import($lib=false){
		$lib = $lib ? $lib :  ZtPrompt::get($this->idiom["msg"]["import"], true);
		if($lib && file_exists($lib)){
			ZtZip::unpack($lib, Zt::path());
		}else ZtPrompt::log($this->idiom["msg"]["error"]["addLib"], array("file" => $lib));
		return $this->modules();
	}

	public function export($dir=false, $zip=false){
        $dir = $this->requireModule($dir);
		$dir = !is_dir($dir) ? Zt::path($dir) : $dir;
		$zip = $zip ? $zip : ZtPrompt::get($this->idiom["msg"]["ztpackage"], "ztpackage");
		$dnm = dirname($zip) != "." ? dirname($zip) : "";
		$zip = is_dir($dnm) ? $zip : (realpath($this->root . Zt::ds() . ".."). Zt::ds() ."$zip.zip");
		ZtZip::pack($dir, $zip);
		ZtPrompt::log($this->idiom["msg"]["packLib"], array("name" => $dir, "file" => $zip));
	}
	
	public function modules(){
		$libs = array_diff( scandir( Zt::path() ), array( '.', '..' ) );
		ZtPrompt::show($this->idiom["msg"]["listLib"]);
		foreach($libs as $k=>$lib){
			$i = $k-1;
			ZtPrompt::show(" ($i) ".$lib);
		}
	}
	
	public function templates(){
		$libs = array_diff( scandir( $this->path."/tpl/" ), array( '.', '..' ) );
		ZtPrompt::show($this->idiom["msg"]["listTpl"]);
		foreach($libs as $k=>$lib){
			$i = $k-1;
			ZtPrompt::show(" ($i) ".$lib);
		}
	}
	
	public function inspect($lib=false){
        $lib = $this->requireModule($lib);
		$dat = substr(ZtMeta::lib($lib), 4);
		return $dat;
	}
	
	private function load($tpl){
		return Zt::data("index", $this->path."/tpl/$tpl/");
	}
	
	private function buildDir($lst, $path=''){
		if(isset($lst['build']['dir'])) foreach($lst['build']['dir'] as $i){
			if(!is_dir($path.$i)) mkdir($path.$i, 0777, true);
		}
	}
	
	private function buildFile($lst, $path='', $data=false){
		if(isset($lst['build']['file'])) foreach($lst['build']['file'] as $i){
			if(!isset($i['type'])) $i['type'] = 'tpl';			
			switch($i['type']){
				case 'main':
					$file = $path . $i['path']. $data['name'] . ".php";
					$this->persist($i['src'], $this->path . "/tpl/{$lst['name']}/tpl/", $data, $file);
				break;
				
				case 'tpl':
					$file = $path . $i['path'];
					$this->persist($i['src'], $this->path . "/tpl/{$lst['name']}/tpl/", $data, $file);
				break;
				
				default: 
					if (!copy($this->path . "/tpl/{$lst['name']}/".$i['src'], $path.$i['path'])) {
						ZtPrompt::log($this->idiom["msg"]["error"]["copy"], array("name" => $this->path . "/tpl/{$lst['name']}/".$i['src']));
					}
				break;
			}
		}
	}
	
	private function persist($tpl, $path, $data, $file){
		$content = Zt::tpl($tpl, $path, $data);
		return file_put_contents($file, $content ? $content : "");
	}
	
	private function getData($lst){
		$data = array();
		if(isset($lst['prompt'])) foreach($lst['prompt'] as $id=>$req){
			$data[$id] = ZtPrompt::get($req['label'], $req['value']);
		}
		return $data;
	}

	private function requireModule($mod=false){
		if(!$mod){
			$this->modules();
			return ZtPrompt::get($this->idiom["msg"]["ztmname"],true);
		}return $mod;
	}

    private function requireTpl($tpl=false){
        if(!$tpl) $this->templates();
		return ZtPrompt::get($this->idiom["msg"]["ztmtpl"], $tpl ? $tpl : "cli_std");
    }

	private function rmdir($path){
		if($path) {
			switch (Zt::ostype()){
				case 'windows': exec("rd /s /q {$path}"); break;
				case 'linux': exec("rm -rf {$path}"); break;
			}
			return true;
		} return false;
	}
}
