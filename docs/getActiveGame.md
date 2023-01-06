# getActiveGame
```php
public function getActiveGame($player)
```

## Parameters

| Parameter | Type          | Example                           | Required |
| --------- | ------------- | --------------------------------- | :------: |
| player    | array, string | "summoner id", `$summoner` object | **YES**  |


## Examples
```php
$livegame = $api->getActiveGame($summoner);
print_r($livegame);

```

## Cache
Live game requests are cached 15 seconds by default. 