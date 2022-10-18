<?php

namespace aethteam\royal\autochangetool;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;

use pocketmine\item\Tool;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener {
    private Config $config;
    protected function onEnable() : void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveConfig();
        $this->config = $this->getConfig();
    }
    public function onBreakItem(BlockBreakEvent $event) {
        $player = $event->getPlayer();
        $item = $player->getInventory()->getItemInHand();
        $slot = $player->getInventory()->getHeldItemIndex();
        $type = $item->getBlockToolType();

        if ($item instanceof Tool) {
            $durability = $item->getDamage();
            $maxDurability = $item->getMaxDurability();
            if ($player->getInventory()->contains($item)) {
                if ($durability +1  >= $maxDurability) {
                    foreach ($player->getInventory()->getContents() as $slots => $items) {
                        if ($slot === $slots) {
                            continue;
                        } else {
                            if ($items->getBlockToolType() === $type) {
                                //change items slot
                                $player->getInventory()->setItem($slot, $items);
                                //delete items
                                $player->getInventory()->setItem($slots, $item);
                                $player->sendMessage($this->config->get("changeMessage"));
                                return;
                            }
                        }
                    }
                }
            }

        }
    }

}
