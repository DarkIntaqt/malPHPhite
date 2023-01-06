# getSummoner
```php
public function getSummoner($name, $beautify = false, $method = "AUTO")
```

This functions **tries** to determine the type of the given name. It can differ pretty good between a summoner name and a puuid. 

Still it is recommened to "stay on the safe side of the road" and use an alias instead. 
## Aliases
```php
getSummonerByName($name, $beautify = false) {
   return getSummoner($name,$beautify,"NAME");
}

getSummonerByPUUID($puuid, $beautify = false) {
   return getSummoner($puuid,$beautify,"PUUID");
}

getSummonerById($id, $beautify = false) {
   return getSummoner($id,$beautify,"ID");
}

getSummonerByAccountId($id, $beautify = false) {
 return getSummoner($id,$beautify,"ACCOUNTID");
}
  ```

| Parameter | Type    | Example                                    |        Required         |
| --------- | ------- | ------------------------------------------ | :---------------------: |
| name      | string  | "puuid", "summoner name"                   |         **YES**         |
| beautify  | boolean | false                                      | **NO** (default: false) |
| method    | string  | "AUTO", "NAME", "PUUID", "ID", "ACCOUNTID" | **NO** (default: auto)  |

## Beautify

Beautify the result will "transform" the returned object to a more readable version:
```json
{
    "@type": "Summoner",
    "name": "Name",
    "level": 123,
    "profileIcon": {
        "id": 5410,
        "cdnLocation": "/profileicon/5410.png"
    },
    "ids": {
        "summonerId": "...",
        "accountId": "...",
        "puuid": "..."
    },
    "revisionDate": 1672956676383,
    "region": "euw1",
    "cache": {
        "enabled": true
    }
}
```

Other functions are can use the beautified summoner object as well as the not beautifed summoner object. 

By default the beautify function is disabled. 

## Examples
```php
$summonerByName = $api->getSummonerByName("summoner",true);
print_r($summonerByName);

$summonerByPuuid = $api->getSummonerByPuuid($summoner["ids"]["puuid"]);
print_r($summonerByPuuid);
```

## Cache
Summoner requests are cached 1 minute by default. 