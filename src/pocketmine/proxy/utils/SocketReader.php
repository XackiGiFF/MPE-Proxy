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

use pocketmine\utils\BinaryStream;

use raklib\protocol\MessageIdentifiers;
use raklib\protocol\PacketSerializer;

use pocketmine\proxy\utils\raklib\protocol\PublicOpenConnectionRequest1;
use pocketmine\proxy\utils\raklib\protocol\PublicOpenConnectionRequest2;


class SocketReader{
    private $working = true, $sessions = [];
    protected $logger, $host, $port, $serverip, $serverport, $clientSocket;

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

    public function setIP($ip) {
        $this->serverip = $ip;
    }

    public function setPort($port) {
        $this->serverport = $port;
    }

    public function tick(): void
    {
        if(!$this->working){
            return;
        }
        $this->clientSocket();
        $this->serverSocket();
    }

    // Это отправляет сервер
    public function clientSocket(): void
    {
        $bytes = $this->receiveClientSocket($buffer, $address, $port);
        if($bytes !== false){
            if(!isset($this->sessions[$address.":".$port])){
                $this->logger->info("Client - {$address}:{$port}");

                $this->sessions[$address.":".$port] = new Session($this->logger, $this->host, $this->serverip, $this->serverport);
                $this->handleClientPacket($buffer, $address, $port);
            }
            $this->sessions[$address.":".$port]->sendServerSocket($buffer);
        }
    }

    // Это отправляет сервер
    public function serverSocket(): void
    {
        foreach($this->sessions as $value => $session){
            if($session instanceof Session){
                $bytes = $session->receiveServerSocket($buffer);

                if($bytes !== false){
                    $value = explode(":", $value);
                    $this->sendClientSocket($buffer, $value[0], $value[1]);
                    $this->handleClientPacket($buffer, "0.0.0.0", 19132);
                }
            }
        }
    }

    public function sendClientSocket($buffer, $address, $port): bool|int
    {
        return socket_sendto($this->clientSocket, $buffer, strlen($buffer), 0, $address, $port);
    }

    public function receiveClientSocket(&$buffer, &$address, &$port): bool|int
    {
        return socket_recvfrom($this->clientSocket, $buffer, 65535, 0, $address, $port);
    }

    public function shutdown(): void
    {
        $this->working = false;
        socket_close($this->clientSocket);

        $this->logger->debug("Closed Socket.");

        foreach($this->sessions as $value => $session){
            $session->close($value);
        }
    }

    private function handleClientPacket($buffer, $address, $port): void
    {
        $this->logger->debug("Received buffer from {$address}:{$port}: " . bin2hex($buffer));

        // Try read the type of packet
        $stream = new BinaryStream($buffer);
        $packetType = $stream->getByte();

        $hexString = dechex($packetType);
        $packetCode = '0x' . strtoupper($hexString);


        $this->logger->debug($packetType);

        switch ($packetType) {
            case 0x01:
                $this->logger->info(Logger::COLOR_YELLOW . "(С => S) Client {$address}:{$port} send unconnected ping!"); // С => S
                break;
            case 0x1c:
                $this->logger->info(Logger::COLOR_RED . "(C <= S) Server {$address}:{$port} send unconnected pong!"); // C <= S
                break;

            case 0x05: // MessageIdentifiers::ID_OPEN_CONNECTION_REQUEST_1
                $this->logger->info(Logger::COLOR_YELLOW . "(C => S) Client {$address}:{$port} send request 1"); // C => S
                break;
            case 0x06: // MessageIdentifiers::ID_OPEN_CONNECTION_REQUEST_2
                $this->logger->info(Logger::COLOR_RED . "(C <= S) Server {$address}:{$port} send response 1"); // C <= S
                break;
            case 0x07:
                $this->logger->info(Logger::COLOR_YELLOW . "(C => S) Client {$address}:{$port} send request 2"); // C => S
                break;
            case 0x08:
                $this->logger->info(Logger::COLOR_RED . "(C <= S) Server {$address}:{$port} send response 2"); // C <= S
                break;
            case 0x09:
                $this->logger->info(Logger::COLOR_YELLOW . "(C => S) Client {$address}:{$port} send request to connect"); // C => S
                break;
            case 0x10:
                $this->logger->info(Logger::COLOR_RED . "(C <= S) Server {$address}:{$port} send response to connect"); // C <= S
                break;

            case 0x80:
                $this->logger->info("Client {$address}:{$port} get I DONT KNOW!");
                break;
            case 0xC0:
                $this->logger->info("Server {$address}:{$port} get I DONT KNOW!");
                break;
            case 0xfe:
                $this->logger->info("Client {$address}:{$port} get QUERY INFO!");
                break;
            default:
                $this->logger->warn("Received unknown packet type {$packetType} from {$address}:{$port}");
                $this->logger->info("Packet num: {$packetCode}");
                return;
                break;
        }
        $this->logger->info("Packet num: {$packetCode}");
        // Add debug message for checking the contents of the buffer
        // Decode the packet
        if(isset($packet)){
            $raklib_packet_serializer = new PacketSerializer($buffer);
            $packet->publicDecodePayload($raklib_packet_serializer);
            //var_dump($packet);
            // Do something with the packet
            $this->processBedrockPacket($packet, $address, $port);
        }
    }

    private function processBedrockPacket($packet, $address, $port): void
    {
        // Here you can process the packet using BedrockProtocol
        // For example you can check the protocol and MTU size
        if ($packet instanceof PublicOpenConnectionRequest1) {
            $protocolVersion = $packet->protocol;
            $this->logger->info(
                "Protocol: " . $protocolVersion . "\n" .
                "MTU Size: " . $packet->mtuSize . "\n");
            //$this->logger->info("Client {$address}:{$port} is using RakNet protocol version {$protocolVersion}");
        }
        if ($packet instanceof PublicOpenConnectionRequest2) {
            $clientID = $packet->clientID;
            $this->logger->info("ClientID: " . $clientID . "\n" .
            "Server Address: " . var_dump($packet->serverAddress) . "\n" .
            "MTU Size: " . $packet->mtuSize . "\n");
            //$this->logger->info("Client {$address}:{$port} is using RakNet protocol version {$protocolVersion}");
        }
    }
}
