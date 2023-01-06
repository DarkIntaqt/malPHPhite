# malPHPhite

[![CodeFactor](https://www.codefactor.io/repository/github/darkintaqt/malphphite/badge)](https://www.codefactor.io/repository/github/darkintaqt/malphphite)

A rock-solid php library for the league of legends API. 


## Quick start

### Usage example
To get quickly started with this library, include the library first. Then, generaze a new LeagueOfLegendsAPI object. 

After that we fetch "Hide on bush"'s profile in KR and print the result. 

```php
<?php

   include_once("/path/to/library.php");
   #include the library

   $api = new LeagueOfLegendsAPI("RGAPI-KEY","kr",true,"/var/tmp/api-cache");
   # be careful: the library throws an error if the region doesn't exist

   $summoner = $api->getSummonerByName("Hide on bush");
   print_r($summoner);
   # {
   #  "id": ...,
   #  "name": "Hide on bush",
   #  ...
   #}
?>
```

### Generating a new object
To use this library you need to generate a new object from the LeagueOfLegendsAPI class. 

The constructor takes several parameters
| Parameter      | Type          | Example              |        Required        |
| -------------- | ------------- | -------------------- | :--------------------: |
| Api Key        | String        | "RGAPI-..."          |        **YES**         |
| Region         | String        | "euw1", "euw"        |        **YES**         |
| Cache          | Boolean       | true                 | **NO** (default: true) |
| CacheDirectory | String (Path) | "/var/tmp/api-cache" | **NO** (default: /tmp) |

### Error Handling
The library throws error if the usage is wrong. 
If the Riot Games API throws errors to the library the library returns the **Integer** as a return value. 

For example:
```php
$summoner = $api->getSummonerByName("THIS SUMMONER DOES NOT EXIST");
# returns 404

# possible catches are
if(gettype($summoner) === "integer") {
   echo "the requested summoner does not exist";
}
```

## Disclaimer
This library should work, but of course there is no warranty for using it.

*The 'MalPHPhite' isn't endorsed by Riot Games and doesn't reflect the views or opinions of Riot Games or anyone officially involved in producing or managing Riot Games properties. Riot Games, and all associated properties are trademarks or registered trademarks of Riot Games, Inc.*