<?php
//####################################################
//### Schedule this script to run every 15 minutes ###
//####################################################

// Include core configuration modules
require("/var/www/_library/_config.php");

// Yahoo Location ID to retreive data for
$OPENWEATHER_LOCATIONID = "put here your OpenWeather location ID";
$OPENWEATHER_APIKEY = "put here your OpenWeather API key code";

// Yahoo API constants and query strings
$OPENWEATHER_APIURL = "http://api.openweathermap.org/data/2.5/weather?id=".$OPENWEATHER_LOCATIONID."&units=metric&lang=it&appid=".$OPENWEATHER_APIKEY;

// Fetch JSON weather data with CURL
//$session = curl_init($YAHOO_APIURL . urlencode($YAHOO_QUERY));
$session = curl_init($OPENWEATHER_APIURL);
curl_setopt($session, CURLOPT_RETURNTRANSFER,true);
$jsonDATA = curl_exec($session);
echo "-------------------------------------------------------------\n";
echo $jsonDATA;
echo "-------------------------------------------------------------\n";

// Decode JSON data into a structured object
$jsonOBJECT = json_decode($jsonDATA);

// Extract data from JSON object
$time_stamp = date("Y-m-d H:i");
$city_id = $OPENWEATHER_LOCATIONID;
$city_text = $jsonOBJECT->name;
$weather_id = $jsonOBJECT->weather[0]->icon;
$weather_text = $jsonOBJECT->weather[0]->description;
$temperature = $jsonOBJECT->main->temp;
$pressure = $jsonOBJECT->main->pressure;
$humidity = $jsonOBJECT->main->humidity;

if (strlen($weather_id) > 2) { $weather_id = substr($weather_id, 0, 2); }

// WEATHER_ID values:
//
// 01 = Sunny
// 02 = Few Clouds
// 03 = Scattrered clouds
// 04 = Broken clouds
// 09 = Shower rain
// 10 = Rain
// 11 = Thunderstorm
// 13 = Snow
// 50 = Mist

// Display received weather data
echo "----------------------------------------------------------\n";
echo "time_stamp.......: '".$time_stamp."'\n";
echo "city_id..........: '".$city_id."'\n";
echo "city.............: '".$city_text."'\n";
echo "weather_id.......: ".$weather_id."\n";
echo "weather_text.....: '".$weather_text."'\n";
echo "temp.............: ".$temperature."^C\n";
echo "pressure.........: ".$pressure." hPa\n";
echo "humidity.........: ".$humidity."%\n";
echo "----------------------------------------------------------\n";

// Open MySQL database connection
$sqlCONN = mysqli_connect($DB_Hostname,$DB_Username,$DB_Password, $DB_Schema);
if (!$sqlCONN) {
  die("Could not connect to database.\n");
}

// Insert RAW DATA into database
$sqlCMD = "INSERT INTO `".$DB_Schema."`.`weather_data` (`timestamp`,`city_id`,`city`,`weather_id`,`weather_text`,`temperature`,`humidity`,`pressure`) VALUES ('".$time_stamp."',".$city_id.",'".$city_text."',".$weather_id.",'".$weather_text."',".$temperature.",".$humidity.",".$pressure.")";

// Execute MySQL insert query and check the results
$sqlRESULT = $sqlCONN->query($sqlCMD);
if (!$sqlRESULT) {
   echo "Failed to insert weather data into database.\n";
	exit();
}

// Close MySQL connection
mysqli_close($sqlCONN);

// MySQL code to create WEATHER_DATA table
//
// CREATE TABLE `solar_db`.`weather_data` (
//  `idx` INT NOT NULL AUTO_INCREMENT,
//  `timestamp` VARCHAR(16) NOT NULL,
//  `city_id` INT NOT NULL,
//  `city` VARCHAR(45) NOT NULL,
//  `weather_id` INT NOT NULL,
//  `weather_text` VARCHAR(60) NOT NULL,
//  `temperature` INT NOT NULL,
//  PRIMARY KEY (`idx`, `timestamp`),
//  UNIQUE INDEX `idx_UNIQUE` (`idx` ASC));
//
// CREATE INDEX timestamp_index ON weather_data (timestamp);
  
?>
