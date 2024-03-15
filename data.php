<?php 
//temperaturebot backend. 
//i've tried to keep all the code vanilla and old school
//of course in php it's all kind of bleh
//gus mueller, April 7 2022
//////////////////////////////////////////////////////////////

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

include("config.php");

$conn = mysqli_connect($servername, $username, $password, $database);

$mode = "";
$out = [];
$date = new DateTime("now", new DateTimeZone('America/New_York'));//obviously, you would use your timezone, not necessarily mine
$formatedDateTime =  $date->format('Y-m-d H:i:s');
//$formatedDateTime =  $date->format('H:i');

if($_REQUEST) {
	$mode = $_REQUEST["mode"];
	$locationId = $_REQUEST["locationId"];
	if($mode=="kill") {
    $method  = "kill";
	
	} else if ($_REQUEST["mode"] && $mode=="getData") {

 
		if(!$conn) {
			$out = ["error"=>"bad database connection"];
		} else {
			$scale = $_REQUEST["scale"];
			if($scale == ""  || $scale == "fine") {
				$sql = "SELECT * FROM " . $database . ".weather_data  
				WHERE recorded > DATE_ADD(NOW(), INTERVAL -1 DAY) AND location_id=" . $locationId . " 
				ORDER BY weather_data_id ASC";
			} else {
				if($scale == "hour") {
					$sql = "SELECT
					 *,
					 YEAR(recorded), DAYOFYEAR(recorded), HOUR(recorded) FROM " . $database . ".weather_data  
					 WHERE recorded > DATE_ADD(NOW(), INTERVAL -7 DAY) AND location_id=" . $locationId . " 
						GROUP BY YEAR(recorded), DAYOFYEAR(recorded), HOUR(recorded)
					 	ORDER BY weather_data_id ASC";
				}
				if($scale == "day") {
					$sql = "SELECT 	 
					*,
			 		YEAR(recorded), DAYOFYEAR(recorded) FROM " . $database . ".weather_data  
					WHERE location_id=" . $locationId . " 
						GROUP BY YEAR(recorded), DAYOFYEAR(recorded)
					 	ORDER BY weather_data_id ASC";
				}
			}
			/*
			//using averages didn't work for some reason:
			weather_data_id, 
			 recorded, 
			  AVG(temperature) AS temperature, 
			  AVG(pressure) AS pressure, 
			  AVG(humidity) AS humidity, 
			 wind_direction, 
			 AVG(precipitation) AS precipitation, 
			 wind_increment, 
			 */
			//echo $sql;
			$result = mysqli_query($conn, $sql);
			$out = [];
			while($row = mysqli_fetch_array($result)) {
				array_push($out, $row);
			}
		}
		$method  = "read";	
	} else if ($mode == "saveData") { //save data
      //test url;:
      // //http://randomsprocket.com/weather/data.php?storagePassword=butterfly&locationId=3&mode=saveData&data=10736712.76*12713103.20*1075869.28*NULL|0*0*1710464489*1710464504*1710464519*1710464534*1710464549*1710464563*1710464579*1710464593*
      if(!$conn) {
        $out = ["error"=>"bad database connection"];
      } else {
        $data = $_REQUEST["data"];
        $lines = explode("|",$data);
        $weatherInfoString = $lines[0];
        $arrWeatherData = explode("*", $weatherInfoString);
        
        $temperature = $arrWeatherData[0];
        $pressure = intval($arrWeatherData[1]);
        $humidity = $arrWeatherData[2];
        $gasMetric = "NULL";
        if(count($arrWeatherData)>3) {
          $gasMetric = $arrWeatherData[3];
        }
		//select * from weathertron.weather_data where location_id=3 order by recorded desc limit 0,10;
        $weatherSql = "INSERT INTO weather_data(location_id, recorded, temperature, pressure, humidity, gas_metric, wind_direction, precipitation, wind_speed, wind_increment) VALUES (" . 
          mysqli_real_escape_string($conn, $locationId) . ",'" .  
          mysqli_real_escape_string($conn, $formatedDateTime)  . "'," . 
          mysqli_real_escape_string($conn, $temperature) . "," . 
          mysqli_real_escape_string($conn, $pressure) . "," . 
          mysqli_real_escape_string($conn, $humidity) . "," . 
          mysqli_real_escape_string($conn, $gasMetric) .
          ",NULL,NULL,NULL,NULL)";
        //echo $weatherSql;
        if(count($lines)>1) {
          $recentReboots = explode("*", $lines[1]);
          foreach($recentReboots as $rebootOccasion) {
            if(intval($rebootOccasion) > 0 && $storagePassword == $_REQUEST["storagePassword"]) {
              $dt = new DateTime();
              $dt->setTimestamp($rebootOccasion);
              $rebootOccasionSql = $dt->format('Y-m-d H:i:s');
              $rebootLogSql = "INSERT INTO reboot_log(location_id, recorded) SELECT " . intval($locationId) . ",'" .$rebootOccasionSql . "' 
                FROM DUAL WHERE NOT EXISTS (SELECT * FROM reboot_log WHERE location_id=" . intval($locationId) . " AND recorded='" . $rebootOccasionSql . "' LIMIT 1)";
               
              $result = mysqli_query($conn, $rebootLogSql);
            }
          }
        
        }
        if($storagePassword == $_REQUEST["storagePassword"]) { //prevents malicious data corruption
          $result = mysqli_query($conn, $weatherSql);
        }
        $method  = "insert";
        $out = Array("message" => "done", "method"=>$method);
  	}

    }
	echo json_encode($out);
	
	
} else {
	echo '{"message":"done", "method":"' . $method . '"}';
}

//some helpful sql examples for creating sql users:
//CREATE USER 'weathertron'@'localhost' IDENTIFIED  BY 'your_password';
//GRANT CREATE, ALTER, DROP, INSERT, UPDATE, DELETE, SELECT, REFERENCES, RELOAD on *.* TO 'weathertron'@'localhost' WITH GRANT OPTION;
//GRANT ALL PRIVILEGES ON *.* TO 'weathertron'@'localhost' WITH GRANT OPTION;
 
