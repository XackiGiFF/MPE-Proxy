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

use pocketmine\proxy\utils\Terminal;

class Logger{
	
	private static $COLOR_DARK_GRAY = "\x1b[38;5;59m";
	private static $COLOR_DARK_GREEN = "\x1b[38;5;34m";
	private static $FORMAT_RESET = "\x1b[m";
	private static $COLOR_AQUA = "\x1b[38;5;87m";
	private static $COLOR_GRAY = "\x1b[38;5;145m";
	private static $COLOR_YELLOW = "\x1b[38;5;227m";
	private static $COLOR_GREEN = "\x1b[38;5;83m";
	private static $COLOR_RED = "\x1b[38;5;203m";
	
	public function getPrefix(){
		return self::$COLOR_DARK_GRAY . "[" . self::$COLOR_DARK_GREEN . "MPE-Proxy" . self::$COLOR_DARK_GRAY . "] ";
	}
	public function getLogo(){
		echo self::$COLOR_AQUA . str_repeat("-", 70) . PHP_EOL . "
              __  __ ____   _____           _____                     
             |  \/  |  __ \|  ___|         |  __ \                    
             | |\/| | |__) | |___    ___   | |__) | __ _____  ___   _ 
             | |  | |  __ /|  ___|  |___|  |  ___/ '__/ _ \ \/ / | | |
             | |  | | |    | |___          | |   | | | (_) >  <| |_| |
             |_|  |_|_|    |_____|         |_|   |_|  \___/_/\_\ __, |
                                                                 __/ |
                                                                |___/ 
    github.com/XackiGiFF/MPE-Proxy\n\n" . str_repeat("-", 70) . self::$FORMAT_RESET . PHP_EOL;
	}

	public function __construct($path, $debuglevel){
		$this->path = $path;
		$this->debuglevel = $debuglevel;
	}

	public function getData(){
		return date("[H:i:s]");
	}

	public function info(string $message){
		$this->message(self::$COLOR_AQUA."[INFO]", $message);
	}

	public function debug(string $message, $level = 1){
		if($this->debuglevel > $level){
			$this->message(self::$COLOR_GRAY."[DEBUG]", $message);
		}
	}

	public function warn(string $message){
		$this->message(self::$COLOR_YELLOW."[WARN]", $message);
	}

	public function success(string $message){
		$this->message(self::$COLOR_GREEN."[SUCCESS]", $message);
	}

	public function error(string $message) : void{
		$this->message(self::$COLOR_RED."[ERROR]", $message);
	}

	public function message($level, $message){
		echo $this->getData().$this->getPrefix().$level." ".$message.self::$FORMAT_RESET.PHP_EOL;
	}

}
?>
