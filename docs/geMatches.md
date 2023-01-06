# getMatches
```php
public function getMatches($player, $count = 100)
```

| Parameter | Type(s)       | Example                     |       Required        |
| --------- | ------------- | --------------------------- | :-------------------: |
| player    | array, string | "puuid", `$summoner` object |        **YES**        |
| count     | integer       | 0-1000                      | **NO** (default: 100) |

The "count" parameter can be set up to 1000 at once, the api will then send multiple requests. 

## Example
```php
$summoner = $api->getSummonerByName("summoner");

$matches = $api->getMatches($summoner,1000);
# get all possible 1000 matches for a summoner
```