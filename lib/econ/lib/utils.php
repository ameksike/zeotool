<?php
	function show($obj, $dump=false){
		echo "<pre>";
		if($dump) var_dump($obj);
		else print_r($obj);
		echo "</pre>";
	}
	
	function cfg($file='config', $path='cfg/'){
		return include $path.$file.".php";
	}
