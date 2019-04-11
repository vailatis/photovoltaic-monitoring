<?php
//header("Content-Type: application/json; charset=UTF-8");
// Include needed external modules
$webROOT = realpath($_SERVER["DOCUMENT_ROOT"]);
require("$webROOT/_library/_config.php");
require("$webROOT/_library/_masterPage.php");
require("$webROOT/_library/_functions.php");

// Open MySQL database connection
$sqlCONN = mysqli_connect($DB_Hostname,$DB_Username,$DB_Password, $DB_Schema);
if ($sqlCONN->connect_errno) {
   // Create the response object container
   $myOBJ->statusCode = 5;
   $myOBJ->statusText = "DB Error";
   // Convert PHP structured object into JSON string
   $myJSON = json_encode($myOBJ);
   // Return JSON response
   print("".$myJSON);
   // Stop script execution
   exit(0);
}

// Get values from GET/POST method
if (isset($_GET['type'])) { $reqTYPE = strtoupper($_GET['type']); } else { $reqTYPE = ""; }

// Check which data-range has been requested
switch ($reqTYPE) {
   case "DATA";
      // Get dashboard data
      $sqlCMD = "CALL dashboard_infos";
      $sqlRESULT = $sqlCONN->query($sqlCMD);
      $sqlRECORD = $sqlRESULT->fetch_assoc();
      // Store dashboard data values
      $myOBJ->statusCode = 0;
      $myOBJ->statusText = "OK";
      $myOBJ->timeStamp = $sqlRECORD["data_timestamp"];
      $myOBJ->weatherTEMP = intval($sqlRECORD["weather_temp"]);
      $myOBJ->weatherID = intval($sqlRECORD["weather_icon"]);
      $myOBJ->weatherTEXT = $sqlRECORD["weather_desc"];
      $myOBJ->weatherICON = "wi-na";
      $myOBJ->systemAGE = intval($sqlRECORD["runtime_days"]);
      $myOBJ->productionInstant = number_format(($sqlRECORD["production_instant"]), 0, ".", "");
      $myOBJ->productionPeak = number_format(($sqlRECORD["production_peak"]), 0, ".", "");
      $myOBJ->storageToday = number_format(($sqlRECORD["storage_today"] / 1000), 2, ".", "");
      $myOBJ->storageMonth = number_format(($sqlRECORD["storage_month"] / 1000), 2, ".", "");
      $myOBJ->consumedToday = number_format(($sqlRECORD["consumed_today"] / 1000), 2, ".", "");
      $myOBJ->consumedMonth = number_format(($sqlRECORD["consumed_month"] / 1000), 2, ".", "");
      $myOBJ->importToday = number_format(($sqlRECORD["import_today"] / 1000), 2, ".", "");
      $myOBJ->importMonth = number_format(($sqlRECORD["import_month"] / 1000), 2, ".", "");
      $myOBJ->savingsToday = number_format(($sqlRECORD["savings_day"]), 2, ".", "");
      $myOBJ->savingsMonth = number_format(($sqlRECORD["savings_month"]), 2, ".", "");
      $myOBJ->inverterSTATUS = $sqlRECORD["inverter_status"];
      $myOBJ->inverterMode = $sqlRECORD["inverter_mode"];
      $myOBJ->inverterLoad = intval($sqlRECORD["inverter_load"]);
      $myOBJ->pvVoltage = $sqlRECORD["pv_voltage"];
      $myOBJ->pvCurrent = $sqlRECORD["pv_current"];
      $myOBJ->gridVoltage = $sqlRECORD["grid_voltage"];
      $myOBJ->gridFrequency = $sqlRECORD["grid_frequency"];
      $myOBJ->batteryStatus = $sqlRECORD["battery_status"];
      $myOBJ->batteryVoltage = $sqlRECORD["battery_voltage"];
      $myOBJ->batteryVSCC = $sqlRECORD["battery_voltage_scc"];
      $myOBJ->batteryChargeCurrent = $sqlRECORD["battery_charging_current"];
      $myOBJ->batteryDisachargeCurrent = $sqlRECORD["battery_discharge_current"];
      $myOBJ->batteryCapacity = intval($sqlRECORD["battery_capacity"]);
      $myOBJ->outputVoltage = $sqlRECORD["ac_output_voltage"];
      $myOBJ->outputFrequency = $sqlRECORD["ac_output_frequency"];
      $myOBJ->outputPowerVA = intval($sqlRECORD["ac_output_apparent_power"]);
      $myOBJ->outputPowerW = intval($sqlRECORD["ac_output_active_power"]);
      $myOBJ->diodeJunctionTemp = number_format(($sqlRECORD["diode_junction_temp"]), 1, ".", "");
      // Process and analyze data to be converted
      $myOBJ->timeStamp = substr($myOBJ->timeStamp, 8, 2).":".substr($myOBJ->timeStamp, 10, 2)." del ".substr($myOBJ->timeStamp, 6, 2)."-".substr($myOBJ->timeStamp, 4, 2)."-".substr($myOBJ->timeStamp, 0, 4);
      if ($myOBJ->systemAGE == 1) { $myOBJ->systemAGE = $myOBJ->systemAGE." giorno"; } else { $myOBJ->systemAGE = $myOBJ->systemAGE." giorni"; }
      if (in_array($myOBJ->weatherID, array(1,1))) { $myOBJ->weatherICON = "wi-day-sunny"; }
      if (in_array($myOBJ->weatherID, array(2,2))) { $myOBJ->weatherICON = "wi-day-cloudy"; }
      if (in_array($myOBJ->weatherID, array(3,3))) { $myOBJ->weatherICON = "wi-cloud"; }
      if (in_array($myOBJ->weatherID, array(4,4))) { $myOBJ->weatherICON = "wi-cloudy"; }
      if (in_array($myOBJ->weatherID, array(9,9))) { $myOBJ->weatherICON = "wi-showers"; }
      if (in_array($myOBJ->weatherID, array(10,10))) { $myOBJ->weatherICON = "wi-rain"; }
      if (in_array($myOBJ->weatherID, array(11,11))) { $myOBJ->weatherICON = "wi-thunderstorm"; }
      if (in_array($myOBJ->weatherID, array(13,13))) { $myOBJ->weatherICON = "wi-snow"; }
      if (in_array($myOBJ->weatherID, array(50,50))) { $myOBJ->weatherICON = "wi-fog"; }
      // Close sql recordset
      $sqlRESULT->close();
      // Exit switch clause
      break;
   case "GRAPH";
      // Get current year production data GRAPH
      //$sqlCONN = mysql_connect($DB_Hostname,$DB_Username,$DB_Password);
      //mysql_select_db($DB_Schema,$sqlCONN);
      $sqlCMD = "SELECT date_mm, prod_wh FROM vw_solar_production WHERE date_yy=date_format(now(), '%Y') ORDER BY date_mm ASC";
      //$sqlRESULT = mysql_query($sqlCMD) or die("FIRST QUERY: ".mysql_error());
      $productionCURRENT = array("'null'", "'null'", "'null'", "'null'", "'null'", "'null'", "'null'", "'null'", "'null'", "'null'", "'null'", "'null'", "'null'");
      $sqlRESULT = $sqlCONN->query($sqlCMD);
      while ($sqlROW = $sqlRESULT->fetch_assoc()) {
         $dataMONTH = intval($sqlROW["date_mm"]);
         $dataPROD = number_format($sqlROW["prod_wh"] / 1000, 0, ".", "");
         $productionCURRENT[$dataMONTH] = $dataPROD;
      }
      $sqlRESULT->close();
      // Get previous years average production data GRAPH
      //$sqlCONN = mysql_connect($DB_Hostname,$DB_Username,$DB_Password);
      //mysql_select_db($DB_Schema,$sqlCONN);
      $sqlCMD = "SELECT date_mm, CAST(AVG(prod_wh) AS DECIMAL(9,2)) AS 'avg_wh', MIN(prod_wh) AS 'min_wh', MAX(prod_wh) AS 'max_wh' FROM vw_solar_production WHERE date_yy<date_format(now(), '%Y') GROUP BY date_mm ORDER BY date_mm ASC";
      //$sqlRESULT = mysql_query($sqlCMD) or die("SECOND QUERY: ".mysql_error());
      $productionPASTmin = array("null", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null");
      $productionPASTmax = array("null", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null");
      $productionPASTavg = array("null", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null");
      $productionPREDICTION = array("null", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null");
      $sqlRESULT = $sqlCONN->query($sqlCMD);
      while ($sqlROW = $sqlRESULT->fetch_assoc()) {
         $dataMONTH = intval($sqlROW["date_mm"]);
         $dataMIN = number_format($sqlROW["min_wh"] / 1000, 0, ".", "");
         $dataMAX = number_format($sqlROW["max_wh"] / 1000, 0, ".", "");
         $dataAVG = number_format($sqlROW["avg_wh"] / 1000, 0, ".", "");
         $productionPASTmin[$dataMONTH] = $dataMIN;
         $productionPASTmax[$dataMONTH] = $dataMAX;
         $productionPASTavg[$dataMONTH] = $dataAVG;
         //if ($productionCURRENT[$dataMONTH] == "0") { $productionCURRENT[$dataMONTH] = ($dataAVG * 0.65); }
         $productionPREDICTION[$dataMONTH] = $dataAVG;
      }
      $sqlRESULT->close();
      // Get the current month number
      $currMONTH = date("n");
      // Create the response object container
      $myOBJ->statusCode = 0;
      $myOBJ->statusText = "OK";
      $myGRAPH = array();
      for ($i = 1; $i<= 12; $i++) {
         // Store each month data into object
         $myDATA = null;
         $myDATA->month = $i;
         $myDATA->avgYears = $productionPASTavg[$i];
         $myDATA->minYears = $productionPASTmin[$i];
         $myDATA->maxYears = $productionPASTmax[$i];
         $myDATA->curYears = $productionCURRENT[$i];
         $myDATA->predMonth = $productionPREDICTION[$i];
         // If there aren't data from past years, store statistical data for predictions
         if ((($myDATA->predMonth == "0")||($myDATA->predMonth == "null"))&&($i == $currMONTH)) {
            switch ($myDATA->month) {
               case  1: $myDATA->predMonth = 221.00; break;
               case  2: $myDATA->predMonth = 342.00; break;
               case  3: $myDATA->predMonth = 505.00; break;
               case  4: $myDATA->predMonth = 502.00; break;
               case  5: $myDATA->predMonth = 582.00; break;
               case  6: $myDATA->predMonth = 587.00; break;
               case  7: $myDATA->predMonth = 637.00; break;
               case  8: $myDATA->predMonth = 580.00; break;
               case  9: $myDATA->predMonth = 494.00; break;
               case 10: $myDATA->predMonth = 351.00; break;
               case 11: $myDATA->predMonth = 230.00; break;
               case 12: $myDATA->predMonth = 215.00; break;
            }
         }
         // Add object month data into response array
         $myGRAPH[] = $myDATA;
      }
      $myOBJ->graph = $myGRAPH;
      // Exit switch clause
      break;
   default:
      // Unknown request type
      $myOBJ->statusCode = 2;
      $myOBJ->statusText = "Unknown Request";
}

// Convert PHP structured object into JSON string
$myJSON = json_encode($myOBJ);

// Return JSON response
print("".$myJSON);

// Close the database connection
mysqli_close($sqlCONN);

// Stop script execution
exit(0);
?>
