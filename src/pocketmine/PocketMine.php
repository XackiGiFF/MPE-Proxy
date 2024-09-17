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

    $vendorDir = __DIR__ . '/../../vendor';
    if (PHP_SAPI === 'cli' && str_starts_with(__FILE__, 'phar://')) {
        $vendorDir = 'phar://' . substr(__FILE__, 0, strpos(__FILE__, '://')) . '/vendor';
    }
    require_once $vendorDir . '/autoload.php';

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
