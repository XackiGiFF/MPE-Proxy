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

class Logger{
	protected string $path;
	protected bool $debuglevel;
    
	public const COLOR_DARK_GRAY = "\x1b[38;5;59m";
	public const COLOR_DARK_GREEN = "\x1b[38;5;34m";
	public const FORMAT_RESET = "\x1b[m";
	public const COLOR_AQUA = "\x1b[38;5;87m";
	public const COLOR_GRAY = "\x1b[38;5;145m";
	public const COLOR_YELLOW = "\x1b[38;5;227m";
	public const COLOR_GREEN = "\x1b[38;5;83m";
	public const COLOR_RED = "\x1b[38;5;203m";

	public function __construct($path, $debuglevel){
		$this->path = $path;
		$this->debuglevel = $debuglevel;
	}

	/** This is used to get the prefix of the log.
	 * @return string
	 */
	public function getPrefix(): string
    {
		return self::COLOR_DARK_GRAY . "[" . self::COLOR_DARK_GREEN . "MPE-Proxy" . self::COLOR_DARK_GRAY . "] ";
	}

	/** This is used to get the logo.
	 * @return void
	 */
	public function getLogo(): void
    {
		$msg = self::COLOR_AQUA . str_repeat("-", 70) . PHP_EOL . "
              __  __ ____   _____           _____                     
             |  \/  |  __ \|  ___|         |  __ \                    
             | |\/| | |__) | |___    ___   | |__) | __ _____  ___   _ 
             | |  | |  __ /|  ___|  |___|  |  ___/ '__/ _ \ \/ / | | |
             | |  | | |    | |___          | |   | | | (_) >  <| |_| |
             |_|  |_|_|    |_____|         |_|   |_|  \___/_/\_\ __, |
                                                                 __/ |
                                                                |___/ 
    github.com/XackiGiFF/MPE-Proxy\n\n" . str_repeat("-", 70) . self::FORMAT_RESET . PHP_EOL;
        $this->writeLog($msg);
        echo $msg;
	}

	public function log($message): void
    {
		$this->writeLog($message);
		$this->output($message);
	}

	public function getData(): string
    {
		return date("[H:i:s]");
	}

	public function info(string $message): void
	{
		$msg = $this->message(self::COLOR_AQUA."[INFO]", $message);
		$this->log($msg);
	}

	public function debug(string $message, $level = 1): void
    {
		if($this->debuglevel > $level){
			$msg = $this->message(self::COLOR_GRAY."[DEBUG]", $message);
			$this->log($msg);
		}
	}

	public function warn(string $message): void
	{
		$msg = $this->message(self::COLOR_YELLOW."[WARN]", $message);
		$this->log($msg);
	}

	public function success(string $message): void
	{
		$msg = $this->message(self::COLOR_GREEN."[SUCCESS]", $message);
		$this->log($msg);
	}

	public function error(string $message): void
	{
		$msg = $this->message(self::COLOR_RED."[ERROR]", $message);
		$this->log($msg);
	}

	public function message($level, $message): string
    {
		return $this->getData().$this->getPrefix().$level." ".$message.self::FORMAT_RESET.PHP_EOL;
	}

	public function writeLog($message): void
    {
		$logFile = 'server.log';
		$formattedMessage = $message . PHP_EOL;
		$fileHandle = fopen($logFile, 'a');
		fwrite($fileHandle, $formattedMessage);
		fclose($fileHandle);
	}

	public function output($message): void
    {
		// Instead of using echo directly,
		// you could use a more flexible output method.
		// For example, you could use a logger that supports different output streams.
		echo $message;
	}
}
