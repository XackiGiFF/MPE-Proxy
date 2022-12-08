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

class Session{
	public $working = true;

	public function __construct($logger, $host, $serverip, $serverport){
		$this->serverip = $serverip;
		$this->serverport = (int) $serverport;
		$this->logger = $logger;

		$this->serverSocket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
		if(@socket_bind($this->serverSocket, $host) === true){
			$this->logger->debug("socket open (".$host.":random)");
		}else{
			$this->working = false;
			echo "Error\n";
		}
		socket_set_nonblock($this->serverSocket);
	}

	public function sendServerSocket($buffer){
		return socket_sendto($this->serverSocket, $buffer, strlen($buffer), 0, $this->serverip, $this->serverport);
	}

	public function receiveServerSocket(&$buffer){
		$bytes = socket_recvfrom($this->serverSocket, $buffer, 65535, 0, $address, $port);
		if($bytes !== false){
			if($address === $this->serverip and $port === $this->serverport){
				return $bytes;
			}
		}

		return false;
	}

	public function close($value){
		$this->logger->debug("Closed Session.");

		socket_close($this->serverSocket);
	}

}