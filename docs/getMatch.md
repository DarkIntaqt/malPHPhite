# getMatch
```php
public function getMatch($matchid)
```

## Aliases
```php
getMatchDetails($matchid);
```

## Parameters

| Parameter | Type(s)          | Example     | Required |
| --------- | ---------------- | ----------- | :------: |
| matchid   | string (matchid) | "EUW1_1234" | **YES**  |



## Example
```php
$matchDetails = $api->getMatch("EUW1_1234");
# get match details for the match EUW1_1234
```

## Cache
Match-detail requests are cached 1 hour by default. 