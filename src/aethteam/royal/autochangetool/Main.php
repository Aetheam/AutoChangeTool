<?php

namespace aethteam\royal\autochangetool;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;

use pocketmine\item\Tool;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener {

    protected function onEnable() : void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveDefaultConfig();
    }
    public function onBreakItem(BlockBreakEvent $event) {
        $player = $event->getPlayer();
        $item = $player->getInventory()->getItemInHand();
        $slot = $player->getInventory()->getHeldItemIndex();
        $type = $item->getBlockToolType();

        if ($item instanceof Tool) {
            if ($player->getInventory()->contains($item)) {
                if ($item->getDamage() +1  >=  $item->getMaxDurability()) {
                    foreach ($player->getInventory()->getContents() as $slots => $items) {
                        if ($slot === $slots) {
                            continue;
                        } else {
                            if ($items->getBlockToolType() === $type) {
                                //change items slot
                                $player->getInventory()->setItem($slot, $items);
                                //delete items
                                $player->getInventory()->setItem($slots, $item);
                                $player->sendMessage($this->getConfig()->get("changeMessage"));
                                return;
                            }
                        }
                    }
                }
            }

        }
    }

}
