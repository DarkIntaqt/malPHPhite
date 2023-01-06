# getMatchTimeline
```php
public function getMatchTimeline($matchid)
```

## Parameters

| Parameter | Type(s)          | Example     | Required |
| --------- | ---------------- | ----------- | :------: |
| matchid   | string (matchid) | "EUW1_1234" | **YES**  |



## Example
```php
$matchTimeline = $api->getMatchTimeline("EUW1_1234");
# get match timeline for the match EUW1_1234
```

## Cache
Match-timeline requests are cached 1 hour by default. 