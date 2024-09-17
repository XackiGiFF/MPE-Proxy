<?php

namespace pocketmine\proxy\utils\raklib\protocol;

use raklib\protocol\OpenConnectionRequest1;
use raklib\protocol\PacketSerializer;

class PublicOpenConnectionRequest1 extends OpenConnectionRequest1 {

    public function __construct() {

    }
    
    public function publicDecodePayload(PacketSerializer $in) : void {
        $this->decodePayload($in);
    }
}