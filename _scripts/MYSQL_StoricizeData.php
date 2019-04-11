<?php

include "/var/www/_library/_config.php";

$sqlCONN = mysqli_connect($DB_Hostname,$DB_Username,$DB_Password, $DB_Schema);
if ($sqlCONN->connect_errno) {
   echo "Could not connect to database.\n";
	exit();
}

// Delete WEATHER data older than 2 months
echo "Cleanup weather data older than 2 months.\n";
$sqlCMD = "DELETE FROM weather_data WHERE (timestamp <= DATE_FORMAT((NOW() + INTERVAL -(2) MONTH),'%Y%m%d'))";
$sqlRESULT = $sqlCONN->query($sqlCMD);


// Close MySQL connection
mysqli_close($sqlCONN);

// ------------------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------------------

?>

