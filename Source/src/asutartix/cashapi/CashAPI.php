<?php

namespace asutartix\cashapi;

use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\utils\Config;

class CashAPI extends PluginBase {
	static $_instance = null;
	
	protected $cash = [];
	
	public function onLoad() {
		self::$_instance = $this;
	}
	
	public static function getInstance() : ?CashAPI {
		return self::$_instance;
	}
	
	public function onEnable() {
		$this->loadFiles();
	}
	
	public function onDisable() {
		$this->saveFiles();
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
	
	protected function loadFiles() {
		$this->cash = (new Config($this->getDataFolder()."cash.json", Config::JSON, []))->getAll();
	}
	
	protected function saveFiles(bool $async = false) {
		$conf = new Config($this->getDataFolder()."cash.json", Config::JSON, []);
		$conf->setAll($this->cash);
		$conf->save($async);
	}
}
