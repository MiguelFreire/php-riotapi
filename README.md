Riot API PHP (DEPRECATED) DO NOT USE
===========

Riot API ported to PHP!
Fully compatible with composer!


Available Functions
===========

getChampions($free, $region)

	$free 		-> bool

	$region 	-> string


getSummonerBy($summoner, $by, $region)

	$summoner 	-> string or int

	$by 		-> string -> "id" or "name"

	$region 	-> string
	

getSummoner($summoner_id, $options, $region)

	$summoner_id 	-> int

	$options 	-> string -> "runes" or "masteries" or "name"

	$region 	-> string


getRecentGames($summoner_id, $region)

	$summoner_id	-> int

	$region 	-> string


getLeagues($summoner_id, $region)

	$summoner_id	-> int

	$region 	-> string


getPlayerStats($summoner_id, $options, $season, $region)
	$summoner_id	-> int

	$options 	-> string -> "summary" or "ranked"

	$season		-> string -> "SEASON3" or "SEASONX"

	$region 	-> string


getTeam()

	$summoner_id	-> int

	$region 	-> string


setKey($key)

	$key 		-> string


setRegion($region) 

	$region		-> string


Available Regions and their functions:
===========
### Regions

| Regions          	 | Tag    | Functions  					   |
| :---------------------:|:------:| -----------------------------------------------|
| Europe West      	 | euw 	  | champions, game, league, stats, summoner, team |
| Europe Nordic and East | eune   | champions, game, league, stats, summoner, team |
| North America    	 | na     | champions, game, league, stats, summoner, team |	
| Turkey   		 | tr     | league                                         |	
| Brasil   		 | br     | league 					   |		

How to use
===========

1-Create a composer project

2- In a new folder inside your composer project create a php file

3- Include composer's autoload and create an RiotAPI instance


    require_once __DIR__ . '/../vendor/autoload.php';
    use RiotAPI\RiotAPI;
    $foo = new RiotAPI;
    $foo->setKey('Your-API-KEY');
    $foo->setRegion('na');
    $foo->getChampions();
    
    
Composer Require
===========
    "require": {
        "miguelfreire/riot-api": "v1.0"
    },
