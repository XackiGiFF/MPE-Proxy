<?php

/*
 *  __  __ ____   _____           _____                     
 * |  \/  |  __ \|  ___|         |  __ \                    
 * | |\/| | |__) | |___    ___   | |__) | __ _____  ___   _ 
 * | |  | |  __ /|  ___|  |___|  |  ___/ '__/ _ \ \/ / | | |
 * | |  | | |    | |___          | |   | | | (_) >  <| |_| |
 * |_|  |_|_|    |_____|         |_|   |_|  \___/_/\_\ __, |
 *                                                     __/ |
 *                                                    |___/ 
 *
 * This software is simply implemented in proxy of minecraft.
 * Source: github.com/XackiGiFF/MPE-Proxy
 * 
 */

namespace pocketmine\proxy\utils;

class Config{
	protected $path, $content, $overwrite;

	public function __construct($path, $content = [], $overwrite = false){
		$this->path = $path;
		$this->content = $content;
		$this->overwrite = $overwrite;
		if(file_exists($this->path)){
			$this->content = json_decode(file_get_contents($this->path), true);
		}else{
			$this->save();
		}
	}

	public function get($name){
		if(isset($this->content[$name])){
			return $this->content[$name];
		}
		return null;
	}

	public function set($name, $content){
		return $this->content[$name] = $content;
	}

	public function save(){
		file_put_contents($this->path, json_encode($this->content, JSON_PRETTY_PRINT));
	}

}
?>
