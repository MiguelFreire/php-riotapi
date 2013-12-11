Riot API PHP
===========

Riot API ported to PHP!
Fully compatible with composer!


Available Functions
===========

getChampions()

getSummonerBy()

getSummoner()

getRecentGames()

getLeagues()

getPlayerStats()

getTeam()

setKey()


How to use
===========

1-Create a composer project

2- In a new folder inside your composer project create a php file

3- Include composer's autoload and call the class


    require_once __DIR__ . '/../vendor/autoload.php';
    use RiotAPI\RiotAPI;
    $foo = new RiotAPI;
    $foo->setKey('Your-API-KEY');
    $foo->getChampions();
    
    
Composer Require
===========
    "require": {
        "miguelfreire/riot-api": "v1.0"
    },
