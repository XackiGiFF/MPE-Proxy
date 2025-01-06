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
    use pocketmine\proxy\utils\Logger;

    date_default_timezone_set('Europe/Moscow');

    require_once(__DIR__ . "/proxy/utils/ClassLoader.php");

    $path = __DIR__.DIRECTORY_SEPARATOR."/../../";

    $loader = new ClassLoader();
    $loader->addPath(__DIR__ . "/../");
    $loader->register();

    if(php_sapi_name() === "cli"){

        $logger = new Logger($path, 2);

        try{
            if (PHP_SAPI === 'cli' && str_starts_with(__FILE__, 'phar://')) {
                $vendorDir = dirname(__FILE__) . '/../../vendor'; // Убедитесь, что используете dirname
                require("$vendorDir/autoload.php");
            } else {
                require_once (__DIR__ . "/../../vendor/autoload.php");
            }
        } catch (\Error $exception) {
            $logger->error("Vendor unfounded! Try \"composer install\"!");
            die;
        }

        $class = new MPEProxy($path);
        $class->getLogger()->info("Thank you for using MPE-Proxy!");
    }else{
        echo "It cannot start from web.<br> Please start from a command-line<br>";
    }
}
