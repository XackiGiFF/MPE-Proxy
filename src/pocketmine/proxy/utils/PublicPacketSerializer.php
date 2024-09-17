<?php
namespace pocketmine\proxy\utils;

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\utils\BinaryStream;

class PublicPacketSerializer extends PacketSerializer {

    public function __construct(int $protocol, string $buffer) {

        parent::__construct($protocol, $buffer);

    }

}