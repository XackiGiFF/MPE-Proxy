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

namespace pocketmine\proxy;

use pocketmine\proxy\{
                    utils\CommandReader,
                    utils\Config,
                    utils\Logger,
                    utils\SocketReader};

class MPEProxy{
    protected $path, $logger, $config, $working, $commandreader, $socketreader;
    protected static $interface;

    public function getPath(){
        return $this->path;
    }

    public function getLogger(): Logger
    {
        return $this->logger;
    }

    public static function getInterface(): MPEProxy|static
    {
        return self::$interface;
    }

    public function __construct($path){
        set_error_handler(function($severity, $message, $file, $line){
            echo "LINE: ".$line . "\n";
            echo "log: ".$message . "\n";
            $debug = debug_backtrace();
            if(isset($debug[1])){
                if(isset($debug[1]["class"]) && $debug[1]["function"])
                    echo $debug[1]["class"] . " : " . $debug[1]["function"] . "\n";
            }
            if(isset($debug[2])){
                if(isset($debug[2]["class"]) && $debug[2]["function"])
                    echo $debug[2]["class"] . " : " . $debug[2]["function"] . "\n";
            }
        });

        $this->path = $path;
        self::$interface = clone $this;

        $this->config = new Config($this->path.DIRECTORY_SEPARATOR . "config.json", [
            "host" => "0.0.0.0",
            "port" => "20003",
            "serverip" => "0.0.0.0",
            "serverport" => "19132",
            "debuglevel" => 0,
        ]);
        $this->config->save();

        $this->logger = new Logger($this->path, $this->config->get("debuglevel"));
        $this->logger->info("MPE-Proxy starting now...");
        $this->logger->getLogo();
        $this->working = true;

        $this->commandreader = new CommandReader();
        $this->socketreader = new SocketReader($this->logger, $this->config->get("host"), $this->config->get("port"), $this->config->get("serverip"), $this->config->get("serverport"));

        $this->logger->info("MPE-Proxy start!");

        echo "\x1b]0;MCBEProxy running!\x07";

        $this->tick();
    }

    public function tick(): void
    {
        while($this->working){
            $this->getCommandLine();
            for($i = 0; $i <= 100000; $i++){
                $this->socketreader->tick();
            }
        }
    }

    public function getCommandLine(): void
    {
        $line = $this->commandreader->getCommandLine();
        if($line !== null){
            $line = explode(" ", $line);
            switch($line[0]){
                case "switch":
                    if(isset($line[1]) && isset($line[2])) {
                        $this->config->set("serverip", $line[1]);
                        $this->config->set("serverport", $line[2]);
                        $this->config->save();
                        $this->socketreader->setIP($line[1]);
                        $this->socketreader->setPort($line[2]);
                        $this->logger->info("Switch to {$line[1]}:{$line[2]}...");
                    } else {
                        $this->logger->info("Usage: /switch <ip> <port>.\n");
                    }
                break;
                case "stop":
                case "shutdown":
                    $this->shutdown();
                break;
                case"help":
                    if(isset($line[1])){
                        switch($line[1]){
                            case "stop":
                            case "shutdown":
                                $this->logger->info("Shutdown system.\n");
                            break;
                        }
                    }else{
                        $this->logger->info("Usage:\n" .
                             "- switch <ip> <port> - Switch target server\n" .
                             "- shutdown - Shutdown system.\n");
                    }
                break;
                default:
                    $this->logger->info("UnknownCommand: " . $line[0] . "\n");
                break;
            }
        }
    }

    public function shutdown(): void
    {
        $this->working = false;
        $this->config->save();
        $this->socketreader->shutdown();
        $this->logger->info("Shutdown a system now...");
    }
}