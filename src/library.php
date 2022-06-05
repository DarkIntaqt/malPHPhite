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
  private function getRegionParameters($region) {
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
  
  protected $rateLimitS = 0;
  protected $rateLimitMaxS = 20;
  protected $rateLimitM = 0;
  protected $rateLimitMaxM = 120;
  protected $key = "";
  protected $region = "";
  protected $cache = true;
  protected $cachedir = "/tmp";
  
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
