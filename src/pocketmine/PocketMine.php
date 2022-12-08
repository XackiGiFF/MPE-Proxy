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

namespace pocketmine{
    use pocketmine\proxy\MPEProxy;
    use pocketmine\proxy\utils\ClassLoader;

    date_default_timezone_set('Europe/Moscow');
    require_once(__DIR__ . "/proxy/utils/ClassLoader.php");

    $loader = new ClassLoader();
    $loader->addPath(__DIR__ . "/../");
    $loader->register();

    if(php_sapi_name() === "cli"){
        $class = new MPEProxy(__DIR__.DIRECTORY_SEPARATOR."/../../");
        $class->getLogger()->info("Thank you for using MPE-Proxy!");
    }else{
        echo "It cannot start from web.<br> Please start from a command-line<br>";
    }
}
