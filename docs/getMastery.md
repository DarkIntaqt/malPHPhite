# getMastery
```php
public function getMastery($player)
```

## Parameters

| Parameter | Type(s)       | Example                  | Required |
| --------- | ------------- | ------------------------ | :------: |
| player    | array, string | "id", `$summoner` object | **YES**  |



## Example
```php
$summoner = $api->getSummonerByName("summoner");

$matches = $api->getMastery($summoner);
# get the mastery for "summoner"
```

## Cache
Mastery requests are cached 15 minutes by default. 