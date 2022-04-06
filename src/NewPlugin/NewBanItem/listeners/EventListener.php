<?php

declare(strict_types=1);

namespace NewPlugin\NewBanItem\listeners;

use pocketmine\block\Lava;
use pocketmine\block\Water;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerBucketEmptyEvent;
use pocketmine\event\player\PlayerBucketFillEvent;
use pocketmine\event\player\PlayerInteractEvent;

class EventListener implements Listener {
	/** @var array<int> $banned */
	private array $banned;
	private string $message;
	private bool $disableLava;
	private bool $disableWater;

	public function __construct(array $cfg) {
		$this->banned = $cfg["banned"];
		$this->message = $cfg["message"];
		$this->disableLava = !$cfg["enableLava"];
		$this->disableWater = !$cfg["enableWater"];
	}

	public function onFill(PlayerBucketFillEvent $event) : void {
		if (($player = $event->getPlayer())->hasPermission("newplugin.bypass")) return;

		$bucket = $event->getBucket()->getLiquid();
		if (($bucket instanceof Lava && $this->disableLava) || ($bucket instanceof Water && $this->disableWater)) {
			$player->sendMessage($this->message);
			$event->cancel();
		}
	}

	public function onEmpty(PlayerBucketEmptyEvent $event) : void {
		if (($player = $event->getPlayer())->hasPermission("newplugin.bypass")) return;

		$bucket = $event->getBucket()->getLiquid();
		if (($bucket instanceof Lava && $this->disableLava) || ($bucket instanceof Water && $this->disableWater)) {
			$player->sendMessage($this->message);
			$event->cancel();
		}
	}

	public function onBreak(BlockBreakEvent $event) : void {
		if (($player = $event->getPlayer())->hasPermission("newplugin.bypass")) return;

		if ($this->contains($event->getBlock()->getId())) {
			$player->sendMessage($this->message);
			$event->cancel();
		}
	}

	public function onPlace(BlockPlaceEvent $event) : void {
		if (($player = $event->getPlayer())->hasPermission("newplugin.bypass")) return;

		if ($this->contains($event->getBlock()->getId())) {
			$player->sendMessage($this->message);
			$event->cancel();
		}
	}

	public function onInteract(PlayerInteractEvent $event) : void {
		if (($player = $event->getPlayer())->hasPermission("newplugin.bypass")) return;

		if ($this->contains($event->getBlock()->getId())) {
			$player->sendMessage($this->message);
			$event->cancel();
		}
	}

	private function contains(int $id) : bool{
		return in_array($id, $this->banned, TRUE);
	}
}