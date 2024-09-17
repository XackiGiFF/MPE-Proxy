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

class ClassLoader{
	private array $path = [];

	public function addPath($path): void
    {
		$this->path[] = $path;
	}

	public function register(): bool
    {
		return spl_autoload_register([$this, "loadClass"]);
	}

	public function findClass($name): ?string
    {
		$components = explode("\\", $name);

		$baseName = implode(DIRECTORY_SEPARATOR, $components);

		foreach($this->path as $path){
			if(file_exists($path.DIRECTORY_SEPARATOR.$baseName.".php")){
				return $path.DIRECTORY_SEPARATOR.$baseName.".php";
			}else{
				echo "NotPath: ".$path.DIRECTORY_SEPARATOR.$baseName.".php".PHP_EOL;//Debug
			}
		}
		return null;
	}

	public function loadClass($name): bool
    {
		$path = $this->findClass($name);
		if($path !== null){
			include($path);
			return true;
		}
		return false;
	}

}
