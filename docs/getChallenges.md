# getChallenges
```php
public function getChallenges($player)
```

| Parameter | Type(s)       | Example                     | Required |
| --------- | ------------- | --------------------------- | :------: |
| player    | array, string | "puuid", `$summoner` object | **YES**  |



## Example
```php
$summoner = $api->getSummonerByName("summoner");

$matches = $api->getChallenges($summoner);
# get the challenges for "summoner"
```

## Cache
Challenges requests are cached 15 minutes by default. 