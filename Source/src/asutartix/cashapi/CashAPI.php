<?php

namespace asutartix\cashapi;

use pocketmine\plugin\PluginBase;
use pocketmine\Player;

class CashAPI extends PluginBase {
	static $_instance = null;
	
	public function onLoad() {
		self::$_instance = $this;
	}
	
	public static function getInstance() : ?CashAPI {
		return self::$_instance;
	}
	
	public function onEnable() {
	}
	
	public function setCash($player, $amount = 0, callable $call = null) : bool {
		if ($player instanceof Player) $player = $player->getName();
		
		$this->cash[$player] = $amount;
		
		if (is_callable($call)) {
			$call($name, $amount);
		}
		return true;
	}
	
	public function addCash($player, $amount = 0, callable $call = null) : bool {
		if ($player instanceof Player) $player = $player->getName();
		
		$ncash = $this->getCash($player);
		$this->cash[$player] = $ncash + $amount;
		
		if (is_callable($call)) {
			$call($name, $amount);
		}
		return true;
	}
	
	public function reduceCash($player, $amount, callable $call = null) : bool {
		if ($player instanceof Player) $player = $player->getName();
		
		$ncash = $this->getCash($player);
		if ($ncash < $amount) return false;
		
		$this->cash[$player] = $ncash - $amount;
		
		if (is_callable($call)) {
			$call($name, $amount);
		}
		return true;
	}
	
	public function getCash($player) {
		if ($player instanceof Player) $player = $player->getName();
		return $this->cash[$player] ?? 0;
	}
}
