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

class SocketReader{
	private $working = true, $sessions = [];

	public function __construct($logger, $host, $port, $serverip, $serverport){
		$this->logger = $logger;
		$this->host = $host;
		$this->port = $port;
		$this->serverip = gethostbyname($serverip);
		$this->serverport = $serverport;

		$this->clientSocket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
		if(@socket_bind($this->clientSocket, $host, $port) === true){
			$this->logger->debug("socket open (".$host.":".$port.")");
		}else{
			$this->working = false;
			echo "Error\n";
		}
		socket_set_nonblock($this->clientSocket);

		$this->logger->info("The proxy configure to ".$serverip." : ".$serverport);
	}

	public function tick(){
		if(!$this->working){
			return;
		}
		$this->clientSocket();
		$this->serverSocket();
	}

	public function clientSocket(){
		$bytes = $this->receiveClientSocket($buffer, $address, $port);
		if($bytes !== false){
			if(!isset($this->sessions[$address.":".$port])){
				$this->logger->info("Client - {$address}:{$port}");

				$this->sessions[$address.":".$port] = new Session($this->logger, $this->host, $this->serverip, $this->serverport);
			}

			$this->sessions[$address.":".$port]->sendServerSocket($buffer);
		}
	}

	public function serverSocket(){
		foreach($this->sessions as $value => $session){
			$bytes = $session->receiveServerSocket($buffer);

			if($bytes !== false){
				$value = explode(":", $value);
				$this->sendClientSocket($buffer, $value[0], $value[1]);
			}
		}
	}

	public function sendClientSocket($buffer, $address, $port){
		return socket_sendto($this->clientSocket, $buffer, strlen($buffer), 0, $address, $port);
	}

	public function receiveClientSocket(&$buffer, &$address, &$port){
		return socket_recvfrom($this->clientSocket, $buffer, 65535, 0, $address, $port);
	}

	public function shutdown(){
		$this->working = false;
		socket_close($this->clientSocket);

		$this->logger->debug("Closed Socket.");
		
		foreach($this->sessions as $value => $session){
			$session->close($value);
		}
	}

}
