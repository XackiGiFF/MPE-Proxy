<?php

namespace pocketmine\proxy\utils\raklib\protocol;

use raklib\protocol\OpenConnectionRequest2;
use raklib\protocol\PacketSerializer;

class PublicOpenConnectionRequest2 extends OpenConnectionRequest2 {

    public function __construct() {

    }
    
    public function publicDecodePayload(PacketSerializer $in) : void {
        $this->decodePayload($in);
    }
}