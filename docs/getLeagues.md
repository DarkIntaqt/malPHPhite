# getLeagues
```php
public function getLeagues($queue, $tier, $division, $page = 1)
```


## Parameters

| Parameter | Type    | Example          |      Required       |
| --------- | ------- | ---------------- | :-----------------: |
| queue     | string  | "solo" or "flex" |       **YES**       |
| tier      | string  | "diamond",...    |       **YES**       |
| division  | integer | 1,2,3,4          |       **YES**       |
| page      | integer | 1                | **NO** (default: 1) |


## Examples
```php
$soloIron3 = $api->getLeagues("solo","iron",3);
print_r($soloIron3);

```

## Cache
Rank requests are cached 1 minute by default. 