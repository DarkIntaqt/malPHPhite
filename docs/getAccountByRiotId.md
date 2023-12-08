# getAccountByRiotId
```php
public function getAccountByRiotId(...$riotId)
```

## Parameters

| Parameter | Type        | Example                | Required     |
| --------- | ----------- |------------------------| ------------ |
| riotId    | string      | `"Hide on bush#KR1"`   | **YES**      |
| OR        |             |                        |              |
| riotId    | string      | `"Hide on bush", "KR1"` | **YES**      |


## Examples
```php
$account = $api->getAccountByRiotId('Hide on bush#KR1');
print_r($account);
# Gets the account for "Hide on bush#KR1" (on current region)
# {
#  "puuid": .....,
#  "gameName": "Hide on bush",
#  "tagLine": "KR1",
# }
```

```php
$account = $api->getAccountByRiotId('Hide on bush', 'KR1');
print_r($account);
# Gets the account for "Hide on bush#KR1" (on current region)
# {
#  "puuid": .....,
#  "gameName": "Hide on bush",
#  "tagLine": "KR1",
# }
```

## Cache
Requests are cached 1 minute by default. 