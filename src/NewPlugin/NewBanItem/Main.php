<?php

declare(strict_types=1);

namespace NewPlugin\NewBanItem;

use NewPlugin\NewBanItem\listeners\EventListener;
use pocketmine\item\ItemIds;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase {
	public function onEnable() : void {
		$this->getServer()->getPluginManager()->registerEvents(new EventListener((new Config($this->getDataFolder() . "config.yml", Config::YAML, [
			"message" => "§f(§cРежим игры§f) Вы не можете использовать этот предмет в креативе!",
			"enableLava" => FALSE,
			"enableWater" => FALSE,
			"banned" => [ItemIds::BEDROCK, ItemIds::TNT]
		]))->getAll()), $this);
	}
}