# getMatches
```php
public function getMatches($player, $count = 100, $options = array())
```
## Parameters

| Parameter | Type(s)       | Example                                                                      |         Required          |
| --------- | ------------- | ---------------------------------------------------------------------------- | :-----------------------: |
| player    | array, string | "puuid", `$summoner` object                                                  |          **YES**          |
| count     | integer       | 0-1000                                                                       |   **NO** (default: 100)   |
| options   | array         | array("startTime"=>0,"endTime"=>1,"queue"=>400,"type"=>"normal","start"=>50) | **NO** (empty by default) |

The "count" parameter can be set up to 1000 at once, the api will then send multiple requests. 

## Example
```php
$summoner = $api->getSummonerByName("summoner");

$matches = $api->getMatches($summoner,1000,array(
   "startTime" => 0,
   "queue" => 420,
));
# get all possible solo queue matches for a summoner
```

## Cache
Match requests are cached 1 minute by default. 