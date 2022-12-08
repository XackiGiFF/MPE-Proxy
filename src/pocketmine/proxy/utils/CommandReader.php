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

class CommandReader{

	public function __construct(){
		$this->read = [];
		$this->write = null;
		$this->except = null;
	}

	public function getCommandLine(){
		$this->read[] = STDIN;
		if(stream_select($this->read, $this->write, $this->except, 0, 200000) > 0){
			$line = trim(fgets(STDIN));
			return $line;
		}
		return null;
	}

}
