
 

  
  const char* ssid = "katydid-g";//"xxxx"; //mine was Moxee Hotspot83_2.4G
  const char* password = "";//"xxxx";
  const char* storagePassword = "xxxx"; //to ensure someone doesn't store bogus data on your server. should match value in config.php
  //data posted to remote server so we can keep a historical record
  //url will be in the form: http://your-server.com:80/weather/data.php?data=
  const char* urlGet = "/weather/data.php";
  const char* hostGet = "randomsprocket.com";
  const int sensorType = 0;//680; //for BME680 -- we may support others
  const int locationId = 3; //3 really is watchdog
  const int secondsGranularity = 10; //how often to store data in the backend in seconds //300 makes sense
  const int connectionFailureRetrySeconds = 4;
  const int connectionRetryNumber = 12;
  
  const int granularityWhenInConnectionFailureMode = 70; //40 was too little time for everything to come up and start working reliably, at least with my sketchy cellular connection
  const int numberOfHotspotRebootsOverLimitedTimeframeBeforeEspReboot = 4; //reboots moxee four times in 340 seconds (number below) and then reboots itself
  const int hotspotLimitedTimeFrame = 340; //seconds
 
