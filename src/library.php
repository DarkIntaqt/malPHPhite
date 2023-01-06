<?php

/**
 * MalPHPhite by DarkIntaqt
 * Welcome to this rock solid League of Legends API
 * Free to use
 */

class LeagueOfLegendsAPI
{
  // VALID REGIONS
  private static $regions = [
    "euw1", "euw",
    "eun1", "eune",
    "kr",
    "oc1", "oc",
    "na1", "na",
    "br", "br1",
    "jp1", "jp",
    "la1", "lan",
    "la2", "las",
    "tr", "tr1",
    "ru",
    "ph2", "ph",
    "sg2", "sg",
    "th2", "th",
    "tw2", "tw",
    "vn2", "vn"
  ];


  // REGIONS TO PARSE 
  private static $parameters = array(
    array("euw1", "europe", [0, 1]),
    array("eun1", "europe", [2, 3]),
    array("kr", "asia", [4]),
    array("oc1", "sea", [5, 6]),
    array("na1", "americas", [7, 8]),
    array("br1", "americas", [9, 10]),
    array("jp1", "asia", [11, 12]),
    array("la1", "americas", [13, 14]),
    array("la2", "americas", [15, 16]),
    array("tr1", "europe", [17, 18]),
    array("ru", "europe", [19]),
    array("ph2", "sea", [20, 21]),
    array("sg2", "sea", [22, 23]),
    array("th2", "sea", [24, 25]),
    array("tw2", "sea", [26, 27]),
    array("vn2", "sea", [28, 29])
  );


  // PARSE REGION STRING TO REGION PARAMETERS
  private function getRegionParameters(string $region)
  {
    $key = array_search($region, self::$regions);
    if ($key >= 0) {
      foreach (self::$parameters as $TEMPORARY_KEY_NOT_IN_USE => $value) {
        if (in_array($key, ($value[2]))) {
          return array("region" => $value[0], "server" => $value[1]);
        }
      }
    } else {
      throw new \Exception("Unknown region {$region} with id {$key}", 4);
    }
  }


  // parse summoner object
  private function parseSummoner($summoner, $get = "puuid")
  {

    if (gettype($summoner) === "string") {
      return $summoner;
    }

    if (gettype($summoner) === "array") {

      //check for a beautified summoner object
      if (isset($summoner["@type"])) {
        try {
          switch ($get) {
            case "id":
              return $summoner["ids"]["summonerId"];

            case "accountId":
            case "puuid":
              return $summoner["ids"][$get];

            case "name":
            case "revisionDate":
              return $summoner[$get];

            case "profileIconId":
              return $summoner["profileIcon"]["id"];

            case "summonerLevel":
              return $summoner["level"];

            default:
              throw new \Exception("The summoner request is empty. ", 8);
          }
        } catch (\Exception $e) {
          throw new \Exception("Can't parse the summoner object successfully: {$e}", 20);
        }
      }

      if (!isset($summoner[$get])) {
        throw new \Exception("Expected valid player array", 13);
      }

      return $summoner[$get];
    } else {
      throw new \Exception("Expected puuid or player array", 12);
    }
  }



  protected $currentRegionParameters = array();

  // protected $rateLimitS = 0;
  // protected $rateLimitMaxS = 20;
  // protected $rateLimitM = 0;
  // protected $rateLimitMaxM = 120;
  protected $key = "";
  protected $basePath = "";
  protected $region = "";
  protected $cache = true;
  protected $cachedir = "/tmp";


  // Returns status code in case of 4xx requests
  protected function query(string $url, int $cacheduration = 300, bool $byPassCacheOnError = false)
  {
    /* CHECK FOR FILE IN CACHE FIRST */
    if ($this->cache) {
      $filepath = $this->cachedir . "/LolCache" . hash("md5", $url) . hash("ripemd128", $url) . ".json";
      if (@file_exists($filepath)) {
        try {
          if (filemtime($filepath) + $cacheduration > strtotime("now") && filesize($filepath) > 0) {
            $file = file_get_contents($filepath);
            $return = json_decode($file, true);
            if (isset($return["status"]["status_code"])) {
              return $return["status"]["status_code"];
            }
            return $return;
          }
        } catch (\Exception $e) {
          trigger_error("Cannot read cached file: {$e}", E_USER_WARNING);
          #return 0;
        }
      }
    }


    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array("X-Riot-Token: {$this->key}"));
    curl_setopt($curl, CURLOPT_TIMEOUT_MS, 5000);
    $response = curl_exec($curl);
    $header = substr($response, 0, curl_getinfo($curl, CURLINFO_HEADER_SIZE));
    $result = substr($response, curl_getinfo($curl, CURLINFO_HEADER_SIZE));
    curl_close($curl);

    $return = json_decode($result, true);


    if ($this->cache && (($byPassCacheOnError == false) || ($byPassCacheOnError && !isset($return["status"]["status_code"])))) {
      try {
        file_put_contents($filepath, $result);
      } catch (\Exception $e) {
        trigger_error("Cannot write cache file to cache directory: {$e}", E_USER_WARNING);
      }
    }

    if (isset($return["status"]["status_code"])) {
      return $return["status"]["status_code"];
    }

    return $return;
  }

  // returns array, even with wrong request
  public function getMatches($player, int $count = 100, array $options = array())
  {

    $player = $this->parseSummoner($player, "puuid");

    if ($count <= 0 || $count > 1000) {
      throw new \Exception("Request maximum is between 1 to 1000 due to API limitations", 14);
    }

    $validOptions = [
      "startTime",
      "endTime",
      "queue",
      "type",
      "start"
    ];

    $optionQuery = "";

    foreach ($options as $key => $value) {
      if (!in_array($key, $validOptions)) {
        trigger_error("Unknown match option: {$key}", E_USER_WARNING);
      } else {
        if ($key !== "start") {
          $optionQuery .= "&{$key}=$value";
        }
      }
    }

    $i = 0;
    if (isset($options["start"])) {
      if ($options["start"] + $count > 1000) {
        throw new \Exception("Request maximum is between 1 to 1000 due to API limitations. Please lower your START parameter", 14);
      }
      $i = $options["start"];
    }

    $matches = array();
    if ($count > 100) {
      $c = 100;
      while ($i < $count) {
        if (($i + 100) >= $count) {
          $c = ($i + 100) - ($i + (100 - abs($i - $count)));
        }
        $result = $this->query("https://{$this->currentRegionParameters["server"]}.api.riotgames.com/lol/match/v5/matches/by-puuid/{$player}/ids?start={$i}&count={$c}{$optionQuery}", 60);
        if (gettype($result) == "integer") {
          return $matches;
        }
        $i = $i + 100;
        if (gettype($result) == "array" && count($result) > 0) {
          foreach ($result as $k) {
            array_push($matches, $k);
          }
        } else {
          $i = $count;
        }
      }
    } else {
      $matches = $this->query("https://{$this->currentRegionParameters["server"]}.api.riotgames.com/lol/match/v5/matches/by-puuid/{$player}/ids?start={$i}&count={$count}{$optionQuery}", 60);
    }
    return $matches;
  }


  public function getChallenges($player)
  {

    $player = $this->parseSummoner($player, "puuid");

    $result = $this->query("https://{$this->currentRegionParameters["region"]}.api.riotgames.com/lol/challenges/v1/player-data/{$player}", 900);
    if (gettype($result) == "integer") {
      return $result;
    }
    return $result;
  }

  public function getRegion()
  {
    return $this->currentRegionParameters;
  }

  // returns false if summoner does not exists or other error
  public function getSummoner(string $name = "", $beautify = false, string $method = "AUTO")
  {

    // DETERMINE TYPE BY STRING LENGTH
    if ($method == "AUTO") {
      $len = strlen($name);
      if ($len === 0) {
        throw new \Exception("Provide a summoner name/id/puuid", 5);
      } elseif ($len <= 16) {
        $method = "NAME";
      } elseif ($len == 78) {
        $method = "PUUID";
      } else {
        $method = "ID";
        trigger_error("Using method AUTO is not recommended for id and account id. If you know the method please provide it: [NAME,PUUID,ID,ACCOUNTID]", E_USER_WARNING);
      }
    }
    $method = strtolower($method);

    $requesturl = "https://{$this->currentRegionParameters["region"]}.api.riotgames.com/lol/summoner/v4/summoners";

    #$name = str_replace(" ", "", $name);

    switch ($method) {
      case 'name':
        $name = urlencode($name);
        $requesturl .= "/by-name";
        break;
      case 'puuid':
        $requesturl .= "/by-puuid";
        break;
      case 'id':
        // ID does not need anything to append;
        break;
      case 'accountid':
        $requesturl .= "/by-account";
        break;
      default:
        throw new \Exception("Invalid method: {$method}. [NAME,PUUID,ID,ACCOUNTID]", 6);
    }

    $result = $this->query($requesturl . "/" . $name, 60, true);

    if (gettype($result) === "integer") {
      return $result;
    }

    if ($beautify === true) {
      if (!isset($result["name"])) {
        throw new \Exception("The summoner request is empty. ", 8);
      }
      $result = array(
        "@type" => "Summoner",
        "name" => $result["name"],
        "level" => $result["summonerLevel"],
        "profileIcon" => array(
          "id" => $result["profileIconId"],
          "cdnLocation" => $this->basePath . "/profileicon/{$result["profileIconId"]}.png"
        ),
        "ids" => array(
          "summonerId" => $result["id"],
          "accountId" => $result["accountId"],
          "puuid" => $result["puuid"]
        ),
        "revisionDate" => $result["revisionDate"],
        "region" => $this->currentRegionParameters["region"],
        "cache" => array(
          "enabled" => $this->cache
        )
      );
    }
    return $result;
  }

  // GETSUMMONER FUNCTION ALIAS
  public function getSummonerByName(string $name, bool $beautify = false)
  {
    return $this->getSummoner($name, $beautify, "NAME");
  }
  public function getSummonerByPUUID(string $puuid, bool $beautify = false)
  {
    return $this->getSummoner($puuid, $beautify, "PUUID");
  }
  public function getSummonerById(string $id, bool $beautify = false)
  {
    return $this->getSummoner($id, $beautify, "ID");
  }
  public function getSummonerByAccountId(string $id, bool $beautify = false)
  {
    return $this->getSummoner($id, $beautify, "ACCOUNTID");
  }


  // CONSTRUCTOR(KEY, REGION, CACHE, CACHEDIR)
  function __construct(string $key = "", string $region = "", bool $cache = true, string $cachedir = "/tmp")
  {
    if ($key === "") {
      throw new \Exception("Provide a key as a first argument", 1);
    }
    if ($region === "") {
      throw new \Exception("Provide a region as a second argument", 2);
    }
    if (!in_array(strtolower($region), self::$regions)) {
      throw new \Exception("Invalid region: {$region}", 3);
    }
    $this->key = $key;
    $this->region = strtolower($region);
    $this->cache = $cache;
    $this->cachedir = $cachedir;
    $this->currentRegionParameters = $this->getRegionParameters(strtolower($this->region));
  }
}
