<?php
namespace pocketmine\proxy\utils;

use pocketmine\network\mcpe\protocol\serializer\PacketBatch;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\utils\BinaryStream;

class PublicPacketBatch extends PacketBatch {

    public function __construct() {

        parent::__construct();

    }

}