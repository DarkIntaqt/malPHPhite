# getLeagueByLeague
```php
public function getLeagues($leagueid)
```


## Parameters

| Parameter | Type   | Example       | Required |
| --------- | ------ | ------------- | :------: |
| leagueid  | string | "xxx-xxx-xxx" | **YES**  |


## Examples
```php
$league = $api->getLeagueByLeague("leagueid");
print_r($league);

```

## Cache
League requests are cached 1 minute by default. 