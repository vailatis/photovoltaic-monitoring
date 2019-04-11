<?php
// Configure the content type
header("Content-Type: text/plain");
header("Pragma: no-cache"); 
header("Expires: 0");

// Include needed external modules
$webROOT = realpath($_SERVER["DOCUMENT_ROOT"]);
require("$webROOT/_library/_config.php");
require("$webROOT/_library/_functions.php");

// Check if we received an ACTION parameter and store its value
$reqACTION = "";
if (isset($_GET['action'])) { $reqACTION = $_GET['action']; }
$reqACTION = "" . trim(strtoupper($reqACTION));

// Open MySQL database connection
$sqlCONN = mysqli_connect($DB_Hostname,$DB_Username,$DB_Password, $DB_Schema);
if ($sqlCONN->connect_errno) {
  die("Could not connect to database: " . $sqlCONN->connect_error . "\n");
}

// Reset the RETURN DATA variable
$RETURN_Data = "";

// Check which data return
switch ($reqACTION) {
	case "DISPLAY":
		// Return all the needed data for DOMOTICA display
		$RETURN_Data = GET_DisplayCSV($sqlCONN);
	break;
	case "DOMOTICA":
		// Return all the needed data for DOMOTICA display
		$RETURN_Data = GET_DomoticaJSON($sqlCONN);
	break;
	default:
		// Return the STATUS of last queried data
		$RETURN_Data = GET_OperationStatus($sqlCONN);
}


// Close the database connection
mysqli_close($sqlCONN);

// Write out the result ( G=GRID | B=BATTERY | H=BATTERY+FOTOVOLTAICO | F=FOTOVOLTAICO )
print $RETURN_Data;

//######################################################################################################
//######################################################################################################
//######################################################################################################
function GET_OperationStatus($sqlCONN) {
	// Query database for last status
	$sqlCMD = "SELECT status_code,battery_discharge_current FROM inverter_data ORDER BY timestamp DESC LIMIT 1";
	$sqlRESULT = $sqlCONN->query($sqlCMD);
	$sqlRECORD = $sqlRESULT->fetch_assoc();
	$INVERTER_Status = $sqlRECORD["status_code"];
	$BATTERY_Discharge = $sqlRECORD["battery_discharge_current"];
	$sqlRESULT->close();
	// Reset the result
	$myRES = "(--?--)";
	// Analyze the queried data
	switch ($INVERTER_Status) {
	   case "L":
         // Line mode
         $myRES = "(--G--)";
         break;
	   case "B":
         // Battery mode
         $myRES = "(--B--)";
         break;
	   case "G":
         // Photovoltaic mode
         $myRES = "(--F--)";
         if ($BATTERY_Discharge > 0) { $myRES = "(--H--)"; }
         break;
	}
	// Return the result
	return $myRES;
}
//------------------------------------------------------------------------------------------------------
function GET_LastTime($sqlCONN) {
	// Reset the result
	$myRES = "--:--";
	// Query database for last status
	$sqlCMD = "SELECT * FROM inverter_data ORDER BY timestamp DESC LIMIT 1";
	$sqlRESULT = $sqlCONN->query($sqlCMD);
	$sqlRECORD = $sqlRESULT->fetch_assoc();
	$TIMESTAMP = "";
	$TIMESTAMP = $sqlRECORD["timestamp"];
	if ($TIMESTAMP != "") { $myRES = substr($TIMESTAMP, 8, 2).":".substr($TIMESTAMP, 10, 2); }
   $sqlRESULT->close();
	// Return the result
	return $myRES;
}
//------------------------------------------------------------------------------------------------------
function GET_LastDate($sqlCONN) {
	// Reset the result
	$myRES = "--/--/--";
	// Query database for last status
	$sqlCMD = "SELECT * FROM inverter_data ORDER BY timestamp DESC LIMIT 1";
	$sqlRESULT = $sqlCONN->query($sqlCMD);
	$sqlRECORD = $sqlRESULT->fetch_assoc();
	$TIMESTAMP = "";
	$TIMESTAMP = $sqlRECORD["timestamp"];
	if ($TIMESTAMP != "") {
		$myRES = substr($TIMESTAMP, 6, 2);
		switch (substr($TIMESTAMP, 4, 2)) {
		   case "01": $myRES .= " Gen"; break;
		   case "02": $myRES .= " Feb"; break;
		   case "03": $myRES .= " Mar"; break;
		   case "04": $myRES .= " Apr"; break;
		   case "05": $myRES .= " Mag"; break;
		   case "06": $myRES .= " Giu"; break;
		   case "07": $myRES .= " Lug"; break;
		   case "08": $myRES .= " Ago"; break;
		   case "09": $myRES .= " Set"; break;
		   case "10": $myRES .= " Ott"; break;
		   case "11": $myRES .= " Nov"; break;
		   case "12": $myRES .= " Dic"; break;
		}
	}
   $sqlRESULT->close();
	// Return the result
	return $myRES;
}
//------------------------------------------------------------------------------------------------------
function GET_ProductionW($sqlCONN) {
	// Reset the result
	$myRES = "0";
	// Query database for last status
	//$sqlCMD = "SELECT SUM(produzione_pv_watt+produzione_batt_watt) AS 'produzione_watt' FROM vw_inverter_power WHERE timestamp=date_format(now(), '%Y%m%d%H%i')";
	$sqlCMD = "SELECT SUM(produzione_pv_watt+produzione_batt_watt) AS 'produzione_watt' FROM vw_inverter_power WHERE timestamp=(SELECT MAX(timestamp) FROM inverter_data)";
	$sqlRESULT = $sqlCONN->query($sqlCMD);
	$sqlRECORD = $sqlRESULT->fetch_assoc();
	$myRES = $sqlRECORD["produzione_watt"];
   $sqlRESULT->close();
	// Return the result
	return $myRES;
}
//------------------------------------------------------------------------------------------------------
function GET_ProductionWH($sqlCONN) {
	// Reset the result
	$myRES = "0,0";
	// Query database for last status
	$sqlCMD = "SELECT SUM(produzione_pv_wattora+produzione_batt_wattora) AS 'produzione_wattora' FROM vw_inverter_power WHERE timestamp>=date_format(now(), '%Y%m%d0000')";
	$sqlRESULT = $sqlCONN->query($sqlCMD);
	$sqlRECORD = $sqlRESULT->fetch_assoc();
	$myRES = $sqlRECORD["produzione_wattora"];
   $sqlRESULT->close();
	// Return the result
	return $myRES;
}
//------------------------------------------------------------------------------------------------------
function GET_BatteriesPRCT($sqlCONN) {
	// Reset the result
	$myRES = "0";
	// Query database for last status
	//$sqlCMD = "SELECT battery_capacity FROM inverter_data WHERE timestamp=date_format(now(), '%Y%m%d%H%i')";
	$sqlCMD = "SELECT battery_capacity FROM inverter_data WHERE timestamp=(SELECT MAX(timestamp) FROM inverter_data)";
	$sqlRESULT = $sqlCONN->query($sqlCMD);
	$sqlRECORD = $sqlRESULT->fetch_assoc();
	$myRES = $sqlRECORD["battery_capacity"];
   $sqlRESULT->close();
	// Return the result
	return $myRES;
}
//------------------------------------------------------------------------------------------------------
function GET_ImportedW($sqlCONN) {
	// Reset the result
	$myRES = "0";
	// Query database for last status
	//$sqlCMD = "SELECT * FROM vw_inverter_power WHERE timestamp=date_format(now(), '%Y%m%d%H%i')";
	$sqlCMD = "SELECT * FROM vw_inverter_power WHERE timestamp=(SELECT MAX(timestamp) FROM inverter_data)";
	$sqlRESULT = $sqlCONN->query($sqlCMD);
	$sqlRECORD = $sqlRESULT->fetch_assoc();
	$myRES = $sqlRECORD["importazione_watt"];
   $sqlRESULT->close();
	// Return the result
	return $myRES;
}
//------------------------------------------------------------------------------------------------------
function GET_ImportedWH($sqlCONN) {
	// Reset the result
	$myRES = "0,0";
	// Query database for last status
	$sqlCMD = "SELECT SUM(importazione_wattora) AS 'importazione_wattora' FROM vw_inverter_power WHERE timestamp>=date_format(now(), '%Y%m%d0000')";
	$sqlRESULT = $sqlCONN->query($sqlCMD);
	$sqlRECORD = $sqlRESULT->fetch_assoc();
	$myRES = $sqlRECORD["importazione_wattora"];
   $sqlRESULT->close();
	// Return the result
	return $myRES;
}
//------------------------------------------------------------------------------------------------------
function GET_ConsumedW($sqlCONN) {
	// Reset the result
	$myRES = "0";
	// Query database for last status
	//$sqlCMD = "SELECT * FROM vw_inverter_power WHERE timestamp=date_format(now(), '%Y%m%d%H%i')";
	$sqlCMD = "SELECT * FROM vw_inverter_power WHERE timestamp=(SELECT MAX(timestamp) FROM inverter_data)";
	$sqlRESULT = $sqlCONN->query($sqlCMD);
	$sqlRECORD = $sqlRESULT->fetch_assoc();
	$myRES = $sqlRECORD["consumo_watt"];
   $sqlRESULT->close();
	// Return the result
	return $myRES;
}
//------------------------------------------------------------------------------------------------------
function GET_ConsumedWH($sqlCONN) {
	// Reset the result
	$myRES = "0,0";
	// Query database for last status
	$sqlCMD = "SELECT SUM(consumo_wattora) AS 'consumo_wattora' FROM vw_inverter_power WHERE timestamp>=date_format(now(), '%Y%m%d0000')";
	$sqlRESULT = $sqlCONN->query($sqlCMD);
	$sqlRECORD = $sqlRESULT->fetch_assoc();
	$myRES = $sqlRECORD["consumo_wattora"];
   $sqlRESULT->close();
	// Return the result
	return $myRES;
}
//------------------------------------------------------------------------------------------------------
function GET_DisplayCSV($sqlCONN) {
   // Query database for last status
   $sqlCMD = "SELECT timestamp,status_code,battery_capacity,ac_output_active_power,battery_charging_current,battery_discharge_current,battery_voltage,pv_current,pv_voltage FROM inverter_data ORDER BY timestamp DESC LIMIT 1";
	$sqlRESULT = $sqlCONN->query($sqlCMD);
	$sqlRECORD = $sqlRESULT->fetch_assoc();
	$INVERTER_Status = $sqlRECORD["status_code"];
	$BATTERY_Discharge = $sqlRECORD["battery_discharge_current"];
	$myStatusCode = "(--?--)";
	$myStatusText = "Unknown";
	switch ($INVERTER_Status) {
		case "L":
			// Line mode
			$myStatusCode = "(--G--)";
			$myStatusText = "Line";
			break;
		case "B":
			// Battery mode
			$myStatusCode = "(--B--)";
			$myStatusText = "Batteries";
			break;
		case "G":
			// Photovoltaic mode
			$myStatusCode = "(--F--)";
			$myStatusText = "Photovoltaic";
         if ($BATTERY_Discharge > 0) { 
            $myStatusCode = "(--H--)"; 
            $myStatusText = "Batt+Photo";
         }
			break;
	}
	// Calculate time and date values
	$myCurrTime = "--:--";
	$myCurrDate = "--/--/----";
	$myBatt = "0";
	$TIMESTAMP = $sqlRECORD["timestamp"];
	if ($TIMESTAMP != "") { 
		$myCurrTime = substr($TIMESTAMP, 8, 2).":".substr($TIMESTAMP, 10, 2); 
      $myCurrDate = substr($TIMESTAMP, 6, 2);
      switch (substr($TIMESTAMP, 4, 2)) {
         case "01": $myCurrDate .= " Gen"; break;
         case "02": $myCurrDate .= " Feb"; break;
         case "03": $myCurrDate .= " Mar"; break;
         case "04": $myCurrDate .= " Apr"; break;
         case "05": $myCurrDate .= " Mag"; break;
         case "06": $myCurrDate .= " Giu"; break;
         case "07": $myCurrDate .= " Lug"; break;
         case "08": $myCurrDate .= " Ago"; break;
         case "09": $myCurrDate .= " Set"; break;
         case "10": $myCurrDate .= " Ott"; break;
         case "11": $myCurrDate .= " Nov"; break;
         case "12": $myCurrDate .= " Dic"; break;
      }
		$myBatt = strval($sqlRECORD["battery_capacity"]);
	}
	$sqlRESULT->close();
	// Query database for last WATT
	$myImpW = "0";
	$myProdW = "0";
	$myConsW = "0";
	$sqlCMD = "SELECT produzione_pv_watt,produzione_batt_watt,importazione_watt,consumo_watt FROM vw_inverter_power ORDER BY timestamp DESC LIMIT 1";
	$sqlRESULT = $sqlCONN->query($sqlCMD);
	$sqlRECORD = $sqlRESULT->fetch_assoc();
	$myImpW = strval($sqlRECORD["importazione_watt"]);
	$myProdW = strval($sqlRECORD["produzione_pv_watt"]+$sqlRECORD["produzione_batt_watt"]);
	$myConsW = strval($sqlRECORD["consumo_watt"]);
	$sqlRESULT->close();
	// Query database for DAILY WATTORA
	$myImpWH = "0";
	$myProdWH = "0";
	$myConsWH = "0";
	$sqlCMD = "SELECT SUM(produzione_pv_wattora+produzione_batt_wattora) AS 'produzione_wattora',SUM(consumo_wattora) AS 'consumo_wattora',SUM(importazione_wattora) AS 'importazione_wattora' FROM vw_inverter_power WHERE timestamp>=date_format(now(), '%Y%m%d0000')";
	$sqlRESULT = $sqlCONN->query($sqlCMD);
	$sqlRECORD = $sqlRESULT->fetch_assoc();
	$myImpWH = strval(number_format($sqlRECORD["importazione_wattora"] / 1000, 2, ",", ""));
	$myProdWH = strval(number_format($sqlRECORD["produzione_wattora"] / 1000, 2, ",", ""));
	$myConsWH = strval(number_format($sqlRECORD["consumo_wattora"] / 1000, 2, ",", ""));
	$sqlRESULT->close();
	// Convert PHP structured object into CSV string
	$myCSV = "".$myStatusCode."|".$myCurrTime."|".$myCurrDate."|".$myBatt."|".$myProdW."|".$myProdWH."|".$myImpW."|".$myImpWH."|".$myConsW."|".$myConsWH;
	// Return the result
	return $myCSV;
}
//------------------------------------------------------------------------------------------------------
function GET_DomoticaJSON($sqlCONN) {
   // Query database for last status
   $sqlCMD = "SELECT timestamp,status_code,battery_capacity,ac_output_active_power,battery_charging_current,battery_discharge_current,battery_voltage,pv_current,pv_voltage FROM inverter_data ORDER BY timestamp DESC LIMIT 1";
	$sqlRESULT = $sqlCONN->query($sqlCMD);
	$sqlRECORD = $sqlRESULT->fetch_assoc();
	$INVERTER_Status = $sqlRECORD["status_code"];
	$BATTERY_Discharge = $sqlRECORD["battery_discharge_current"];
	$myStatusCode = "(--?--)";
	$myStatusText = "Unknown";
	switch ($INVERTER_Status) {
		case "L":
			// Line mode
			$myStatusCode = "(--G--)";
			$myStatusText = "Line";
			break;
		case "B":
			// Battery mode
			$myStatusCode = "(--B--)";
			$myStatusText = "Batteries";
			break;
		case "G":
			// Photovoltaic mode
			$myStatusCode = "(--F--)";
			$myStatusText = "Photovoltaic";
         if ($BATTERY_Discharge > 0) { 
            $myStatusCode = "(--H--)"; 
            $myStatusText = "Batt+Photo";
         }
			break;
	}
	// Calculate time and date values
	$myCurrTime = "--:--";
	$myCurrDate = "--/--/----";
	$myBatt = "0";
	$TIMESTAMP = $sqlRECORD["timestamp"];
	if ($TIMESTAMP != "") { 
		$myCurrTime = substr($TIMESTAMP, 8, 2).":".substr($TIMESTAMP, 10, 2); 
		$myCurrDate = substr($TIMESTAMP, 6, 2)."/".substr($TIMESTAMP, 4, 2)."/".substr($TIMESTAMP, 0, 4); 
		$myBatt = strval($sqlRECORD["battery_capacity"]);
	}
	$I2H = 0;
	$I2B = 0;
	$B2I = 0;
	$S2I = 0;
	$G2I = 0;
   $BATTV = 0;
   $BATTA = 0;
	$I2H = round($sqlRECORD["ac_output_active_power"], 0);
	$I2B = round($sqlRECORD["battery_charging_current"] * $sqlRECORD["battery_voltage"], 0);
	$B2I = round($sqlRECORD["battery_discharge_current"] * $sqlRECORD["battery_voltage"], 0);
	$S2I = round($sqlRECORD["pv_current"] * $sqlRECORD["pv_voltage"], 0);
	if ($INVERTER_Status == "L") { $G2I = round($sqlRECORD["ac_output_active_power"], 0); }
   $BATTA = round($sqlRECORD["battery_charging_current"], 0);
   if ($B2I > 0) { $BATTA = round($sqlRECORD["battery_discharge_current"], 0); }
   $BATTV = round($sqlRECORD["battery_voltage"], 1);
	$sqlRESULT->close();
	// Query database for last WATT
	$myImpW = "0";
	$myProdW = "0";
	$myConsW = "0";
	$sqlCMD = "SELECT produzione_pv_watt,produzione_batt_watt,accumulo_batt_watt,importazione_watt,consumo_watt FROM vw_inverter_power ORDER BY timestamp DESC LIMIT 1";
	$sqlRESULT = $sqlCONN->query($sqlCMD);
	$sqlRECORD = $sqlRESULT->fetch_assoc();
	$myImpW = strval($sqlRECORD["importazione_watt"]);
	$myProdW = strval($sqlRECORD["produzione_pv_watt"]);
	$myConsW = strval($sqlRECORD["consumo_watt"]);
   $myBattW = strval($sqlRECORD["produzione_batt_watt"]);
   if ($I2B > 0) { $myBattW = strval($sqlRECORD["accumulo_batt_watt"]); }
	$sqlRESULT->close();
	// Query database for DAILY WATTORA
	$myImpWH = "0";
	$myProdWH = "0";
	$myConsWH = "0";
	$sqlCMD = "SELECT SUM(produzione_pv_wattora) AS 'produzione_wattora',SUM(produzione_batt_wattora) AS 'scarica_wattora',SUM(accumulo_batt_wattora) AS 'carica_wattora',SUM(consumo_wattora) AS 'consumo_wattora',SUM(importazione_wattora) AS 'importazione_wattora' FROM vw_inverter_power WHERE timestamp>=date_format(now(), '%Y%m%d0000')";
	$sqlRESULT = $sqlCONN->query($sqlCMD);
	$sqlRECORD = $sqlRESULT->fetch_assoc();
	$myImpWH = strval(number_format($sqlRECORD["importazione_wattora"] / 1000, 2, ",", ""));
	$myProdWH = strval(number_format($sqlRECORD["produzione_wattora"] / 1000, 2, ",", ""));
	$myConsWH = strval(number_format($sqlRECORD["consumo_wattora"] / 1000, 2, ",", ""));
   $myBattWH = strval(number_format($sqlRECORD["scarica_wattora"] / 1000, 2, ",", ""));
   if ($I2B > 0) { $myBattWH = strval($sqlRECORD["carica_wattora"]); }
	$sqlRESULT->close();
	// Reset the result
	$myJSON = "";
	// Create the response object container
	$myOBJ->statusCode = $myStatusCode;
	$myOBJ->statusText = $myStatusText;
	$myOBJ->currTime = $myCurrTime;
	$myOBJ->currDate = $myCurrDate;
	$myOBJ->batt = $myBatt;
	$myOBJ->prodW = $myProdW;
	$myOBJ->prodWH = $myProdWH;
	$myOBJ->battW = $myBattW;
	$myOBJ->battWH = $myBattWH;
	$myOBJ->impW = $myImpW;
	$myOBJ->impWH = $myImpWH;
	$myOBJ->consW = $myConsW;
	$myOBJ->consWH = $myConsWH;
	$myOBJ->I2H = $I2H;
	$myOBJ->I2B = $I2B;
	$myOBJ->B2I = $B2I;
	$myOBJ->S2I = $S2I;
	$myOBJ->G2I = $G2I;
   $myOBJ->BattV = $BATTV;
   $myOBJ->BattA = $BATTA;
	// Convert PHP structured object into JSON string
	$myJSON = json_encode($myOBJ);
	// Return the result
	return $myJSON;
}
//######################################################################################################
//######################################################################################################
//######################################################################################################
?>
