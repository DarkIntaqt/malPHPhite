<?php 

/**
 * LeagueOfLegendsAPI by DarkIntaqt
 */
 
class LeagueOfLegendsAPI
{
  // VALID REGIONS
  private static $regions = ["euw","euw1","eune","eun1","kr","oc","oc1","na1","na","br","br1","jp1","jp","ru"];
  
  // REGIONS TO PARSE
  private static $parameters = array(
    array("euw1","europe",[0,1]),
    array("eun1","europe",[2,3]),
    array("kr","asia",[4]),
    array("oc1","americas",[5,6]),
    array("na1","americas",[7,8]),
    array("br1","americas",[9,10]),
    array("jp1","asia",[11,12]),
    array("ru","europe",[13])
  );
  
  
  // PARSE REGION STRING TO REGION PARAMETERS
  private function getRegionParameters(string $region) {
    $key = array_search($region,self::$regions);
    if($key >= 0) {
      foreach (self::$parameters as $arrayKey => $value) {
        if(in_array($key,($value[2]))) {
          return array("region"=>$value[0],"server"=>$value[1]);
        }
      }
    } else {
      throw new \Exception("Unknown region {$region} with id {$key}", 4);
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
  
  protected function query(string $url,int $cacheduration = 300) {
    /* CHECK FOR FILE IN CACHE FIRST */
    if($this->cache) {
      $filepath = $this->cachedir . "/LolCache" . hash("md5",$url) . ".json";
      if(@file_exists($filepath)) {
        try {
         if(filemtime($filepath) + $cacheduration > strtotime("now") && filesize($filepath) > 0) {
           $file = file_get_contents($filepath);
           return json_decode($file,true);
         }        
        } catch (\Exception $e) {
          trigger_error("Cannot read cached file: {$e}",E_USER_WARNING);
          #return 0;
        }
      }
    }
    
    
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array("X-Riot-Token: {$this->key}"));
    $result = curl_exec($curl);
    curl_close($curl);
    
    if($this->cache) {
      try {
        file_put_contents($filepath,$result);
      } catch (\Exception $e) {
        trigger_error("Cannot write cache file to cache directory: {$e}",E_USER_WARNING);
      }
    }
    
    return json_decode($result,true);
  }
  
  
  public function getSummoner(string $name="", $beautify = false, string $method = "AUTO") {
    
    // DETERMINE TYPE BY STRING LENGTH
    if($method == "AUTO") {
      $len = strlen($name);
      if($len == 0) {
        throw new \Exception("Provide a summoner name/id/puuid", 5);
      } elseif($len <= 16) {
        $method = "NAME";
      } elseif($len == 78) {
        $method = "PUUID";
      } elseif($len == 47) {
        $method = "ID";
        trigger_error("Using method AUTO is not recommended for id and account id. If you know the method please provide it: [NAME,PUUID,ID,ACCOUNTID]", E_USER_WARNING);
      } else {
        $method = "ACCOUNTID";
        trigger_error("Using method AUTO is not recommended for id and account id. If you know the method please provide it: [NAME,PUUID,ID,ACCOUNTID]", E_USER_WARNING);
        
      }
    }
    $method = strtolower($method);
    
    $requesturl = "https://{$this->currentRegionParameters["region"]}.api.riotgames.com/lol/summoner/v4/summoners";
    switch ($method) {
      case 'name':
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
        break;
    }
    
    $result = $this->query($requesturl . "/" . str_replace(" ","",$name));
    
     
    if($beautify === true) {
      if(!isset($result["name"])) {
        throw new \Exception("The summoner request is empty. ", 8);
      }
      $result = array(
        "@type"=>"Summoner",
        "name"=>$result["name"],
        "level"=>$result["summonerLevel"],
        "profileIcon"=>array(
          "id"=>$result["profileIconId"],
          "cdnLocation"=>$this->basePath."/profileicon/{$result["profileIconId"]}.png"
        ),
        "ids"=>array(
          "summonerId"=>$result["id"],
          "accountId"=>$result["accountId"],
          "puuid"=>$result["puuid"]
        ),
        "revisionDate"=>$result["revisionDate"],
        "region"=>$this->currentRegionParameters["region"],
        "cache"=>array(
          "enabled"=>$this->cache
        )
      );
    }
    return $result;
  }
  
  // GETSUMMONER FUNCTION ALIAS
  public function getSummonerByName(string $name, bool $beautify) {
    return $this->getSummoner($name,$beautify,"NAME");
  }
  public function getSummonerByPUUID(string $puuid, bool $beautify) {
    return $this->getSummoner($puuid,$beautify,"PUUID");
  }
  public function getSummonerById(string $id, bool $beautify) {
    return $this->getSummoner($id,$beautify,"ID");
  }
  public function getSummonerByAccountId(string $id, bool $beautify) {
    return $this->getSummoner($id,$beautify,"ACCOUNTID");
  }
  
  
  // CONSTRUCTOR(KEY, REGION, CACHE, CACHEDIR)
  function __construct(string $key = "", string $region = "", bool $cache = true, string $cachedir = "/tmp")
  {
    if($key === "") {
      throw new \Exception("Provide a key as a first argument", 1);
    }
    if($region === "") {
      throw new \Exception("Provide a region as a second argument", 2);
    }
    if(!in_array(strtolower($region),self::$regions)) {
      throw new \Exception("Invalid region: {$region}", 3);
    }
    $this->key = $key;
    $this->region = strtolower($region);
    $this->cache = $cache;
    $this->cachedir = $cachedir;
    $this->currentRegionParameters = $this->getRegionParameters($this->region);
  }
}




 ?>
