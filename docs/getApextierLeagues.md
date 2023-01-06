# getApextierLeagues
```php
public function getApextierLeagues($tier, $queue)
```

## Aliases
```php
getChallengerLeague($queue) {
   return getApextierLeagues("challenger",$queue);
}
getGrandmasterLeague($queue) {
   return getApextierLeagues("grandmaster",$queue);
}
getMasterLeague($queue) {
   return getApextierLeagues("master",$queue);
}
  ```

## Parameters

| Parameter | Type   | Example                                 | Required |
| --------- | ------ | --------------------------------------- | :------: |
| tier      | string | "master", "grandmaster" or "challenger" | **YES**  |
| queue     | string | "solo" or "flex"                        | **YES**  |


## Examples
```php
$challengerSolo = $api->getChallengerLeague("solo");
print_r($challengerSolo);

$challengerFlex = $api->getApextierLeagues("challenger", "flex");
print_r($challengerFlex);
```

## Cache
Rank requests are cached 1 minute by default. 