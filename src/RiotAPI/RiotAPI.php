<?php

namespace RiotAPI;

class RiotAPI {

	//Developer API KEY or APP KEY

	protected $key;

	protected $server_v1 = 'http://prod.api.pvp.net/api/lol/{region}/v1.1';

	protected $server_v2 = 'http://prod.api.pvp.net/api/{region}/v2.1';
 		
	protected $region;

	/**
     * Get Champions list
     * @param   string 		$region
     * @param 	boolean		$free
     * @return  array
    */
	public function getChampions($free = false, $region = null) {
		$region = (is_null($region)) ? $this->region : $region;
		$this->validate_region('champion', $region);
		if($free) {
			$call = $this->server_v1 . "/champion/?freeToPlay=true&";
			$result = $this->request($region,$call);
			
			return $result;

		} elseif(!$free) {
			$call = $this->server_v1."/champion/?";
			$result = $this->request($region,$call);
			
			return $result;

		} else {
			throw new \Exception("The parameter must be a boolean", 1);
		}
	}

	/**
     * Get Summoner's Information by ID or Name
     * @param   string 		$summoner
     * @param 	string		$get_by ('id' or 'name')
     * @param 	string		$region
     * @return  array
    */
	public function getSummonerBy($summoner, $get_by = "id",  $region = null) {
		$region = (is_null($region)) ? $this->region : $region;
		$this->validate_region('summoner', $region);
		if($get_by == "id") {
			$call = $this->server_v1 . "/summoner/" . $summoner . "?";
			$result = $this->request($region, $call);		
		} elseif($get_by == "name") {
			$call = $this->server_v1 . "/summoner/by-name/" . $summoner . "?";
			$result = $this->request($region, $call); 
		} else {
			throw new \Exception('You can only get summoners by ID or Name!', 1);
		}

		return $result;
	}

	/**
     * Get Summoner's stuff from ID
     * @param   integer 	$summoner_id
     * @param 	string		$options ('runes' or 'masteries' or 'name')
     * @param 	string		$region
     * @return  array
    */
	public function getSummoner($summoner_id, $options, $region = null) {
		$region = (is_null($region)) ? $this->region : $region;
		$this->validate_region('summoner', $region);
		$call = $this->server_v1 . "/summoner/" . $summoner_id;
		switch ($options) {
			case 'runes':
				$call .= "/runes/?";
				break;
			
			case 'masteries':
				$call .= "/masteries/?";
				break;
			
			case 'name':
				$call .= "/name/?";
				break;	
			default:
				throw new \Exception('Get summoner '.$options.' not available',1);
				break;
		}
		$result = $this->request($region , $call);
		
		return $result;
	}

	/**
     * Get Recent games from summoner's ID
     * @param   integer 	$summoner_id
     * @param 	string		$region
     * @return  array
    */
	public function getRecentGames($summoner_id, $region = null) {
		$region = (is_null($region)) ? $this->region : $region;
		$this->validate_region('game', $region);
		$call = $this->server_v1."/game/by-summoner/" . $summoner_id . "/recent/?";
		$result = $this->request($region, $call);

		return $result;
	}

	/**
     * Get Leagues from summoner's ID
     * @param   integer 	$summoner_id
     * @param 	string		$region
     * @return  array
    */
	public function getLeagues($summoner_id, $region = null) {
		$region = (is_null($region)) ? $this->region : $region;
		$this->validate_region('league', $region);
		$call = $this->server_v2."/league/by-summoner/" . $summoner_id . "?";
		$result = $this->request($region, $call);
		
		return $result;
	}

	/**
     * Get Player Stats from summoner's ID
     * @param   integer 	$summoner_id
     * @param 	string 		$options ('summary' or 'result')
     * @param 	string 		$season ('SEASONx', x -> N)
     * @param 	string		$region
     * @return  array
    */
	public function getPlayerStats($summoner_id, $options = "summary", $season = "SEASON3",  $region = null) {
		$region = (is_null($region)) ? $this->region : $region;
		$this->validate_region('stats', $region);
		switch ($options) {
			case 'summary':
				$call = $this->server_v1 . "/stats/by-summoner/" . $summoner_id . "/summary?season=" . $season . "&";
				$result = $this->request($region, $call);
				break;
			case 'ranked':
				$call = $this->server_v1 . "/stats/by-summoner/" . $summoner_id . "/ranked?season=" . $season . "&";
				$result = $this->request($region, $call);
				break;
			default:
				throw new \Exception('No option available', 1);
				break;
		}
			
			return $result;
	}

	/**
     * Get team from summoners ID
     * @param   integer 	$summoner_id
     * @param 	string		$region
     * @return  array
    */
	public function getTeam($summoner_id, $region = null) {
		$region = (is_null($region)) ? $this->region : $region;
		$this->validate_region('team', $region);
		$call = $this->server_v2 . "/team/by-summoner/" . $summoner_id . "?";
		$result = $this->request($region, $call);
		$this->validate_region('champion', 'euw');
		return $result;
	}

	/**
     * Set api key
     * @param   string 		$key
     * @return  void
    */
	public function setKey($key = null) {
		$this->key = $key;
	}

	/**
     * Set region
     * @param   string 		$region
     * @return  void
    */
	public function setRegion($region = null) {
		$this->region = $region;
	}

	/**
     * Get Recent games from summoners ID
     * @param   integer 	$summoner_id
     * @param 	string		$region
     * @return  array
    */
	private function request($region, $call) {
		$request = str_replace('{region}', $region, $call) . 'api_key=' . $this->key;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL,$request);

		$result=curl_exec($ch);
		$data = json_decode($result,true);
		
		if(is_null($data)) return $data;
		
		if(array_key_exists('status', $data)) {
			throw new \Exception($data['status']['status_code'] . ' - ' . $data['status']['message'], 1);
		}

		return $data;

	}

	/**
     * Get the queue's name from its ID
     * @param   intenger 	$queueID
     * @return  string
    */
	private function getQueueName($queueID) {
		$queues = array (
			2 => "Normal 5v5 Blind Pick",
			4 => "Ranked Solo 5v5",
			7 => "Coop vs AI 5v5",
			8 => "Normal 3v3",
			14 => "Normal 5v5 Draft Pick",
			16 => "Dominion 5v5 Blind Pick",
			17 => "Dominion 5v5 Draft Pick",
			25 => "Dominion Coop vs AI",
			41 => "Ranked Team 3v3",
			42 => "Ranked Team 5v5",
			52 => "Twisted Treeline Coop vs AI",
			65 => "ARAM",
			67 => "ARAM Coop vs AI",
		);

		if(array_key_exists($queueID, $queues)) {
			return $queues[$queueID];
		}
		else throw new \Exception('No queue with that QueueID', 1);
	}
	
	/**
     * Get the maps's name from its ID
     * @param   intenger 	$mapID
     * @return  string
    */
	private function getMapName($mapID) {
		$maps = array (
			1 => "Summoner's Rift", // Summer Variant
			2 => "Summoner's Rift", //Autumn Variant
			3 => "The Proving Grounds", // Tutorial Map
			4 => "Twisted Treeline", //Old Map
			8 => "The Crystal Scar", // Dominion Map
			10 => "Twisted Treeline", // Current Map
			12 => "Howling Abyss" //ARAM MAP
		);
	}
	/**
     * Checks if the region is valid within some module
     * @param   string 		$module
     * @param   string 		$region
     * @return  boolean
    */
	private function validate_region($module, $region) {
		$reference = array(
			"champion" => array(
				"regions" => "euw|eune|na",
				"version" => "v1.1"
				),
			"game" => array(
				"regions" => "euw|eune|na",
				"version" => "v1.1"
				),
			"league" => array(
				"regions" => "tr|br|euw|eune|na",
				"version" => "v2.1"
				),
			"stats" => array(
				"regions" => "euw|eune|na",
				"version" => "v1.1"
				),
			"summoner" => array(
				"regions" => "euw|eune|na",
				"version" => "v1.1"
				),
			"team" => array(
				"regions" => "euw|eune|na",
				"version" => "v2.1"
				),

		);

		$regions_list = array('euw','eune','na','tr','br');

		if(!in_array($region, $regions_list)) throw new \Exception("Region Not Found", 1);
		

		if (array_key_exists($module, $reference)) {
			$reference_region = explode("|", $reference[$module]['regions']);
			if(!in_array($region, $reference_region)) throw new \Exception("The current function (".$module.") is not available in region (".$region.")", 1);
			

		} else {
			throw new \Exception("Module not found", 1);
			
		}

	
	}

}

