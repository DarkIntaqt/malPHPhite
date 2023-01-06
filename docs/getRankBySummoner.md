# getRankBySummoner
```php
public function getRankBySummoner($player, $beautify = false)
```

## Parameters

| Parameter | Type          | Example                     |        Required         |
| --------- | ------------- | --------------------------- | :---------------------: |
| player    | array, string | "puuid", `$summoner` object |         **YES**         |
| beautify  | boolean       | false                       | **NO** (default: false) |

## Beautify

Beautify the result will "transform" the returned object to a more readable version:
```json
{
    "RANKED_FLEX_SR": {
        "leagueId": "...",
        "queueType": "RANKED_FLEX_SR",
        "tier": "BRONZE",
        "rank": "I",
        "summonerId": "...",
        "summonerName": "Summonername",
        "leaguePoints": 44,
        "wins": 117,
        "losses": 102,
        "veteran": false,
        "inactive": false,
        "freshBlood": false,
        "hotStreak": false,
        "promos": false
    },
    "RANKED_SOLO_5x5": {
        "leagueId": "...",
        "queueType": "RANKED_SOLO_5x5",
        "tier": "SILVER",
        "rank": "IV",
        "summonerId": "...",
        "summonerName": "Summonername",
        "leaguePoints": 53,
        "wins": 130,
        "losses": 106,
        "veteran": false,
        "inactive": false,
        "freshBlood": false,
        "hotStreak": false,
        "promos": false
    }
}
```

By default the beautify function is disabled. 

## Examples
```php
$rank = $api->getRankBySummoner($summoner,true);
print_r($rank);
```

## Cache
Rank requests are cached 15 seconds by default. 