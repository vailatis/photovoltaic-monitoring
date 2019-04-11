<!DOCTYPE html>
<head>
<?php
// Include needed external modules
$webROOT = realpath($_SERVER["DOCUMENT_ROOT"]);
require("$webROOT/_library/_config.php");
require("$webROOT/_library/_masterPage.php");
require("$webROOT/_library/_functions.php");
// Get page data parameters, if they exists
$reqDATE = "";
if (isset($_GET['reqdate'])) {
   $reqDATE = $_GET['reqdate'];
}
if (validateDateFormat($reqDATE, "Y-m-d") == false) { $reqDATE = date("Y-m-d"); }
// Open MySQL database connection
$sqlCONN = mysqli_connect($DB_Hostname,$DB_Username,$DB_Password, $DB_Schema);
if ($sqlCONN->connect_errno) {
  die("Could not connect to database: " . $sqlCONN->connect_error . "\n");
}
// Calculate the oldest weather record date available
$sqlCMD = "SELECT timestamp FROM weather_data ORDER BY timestamp ASC LIMIT 1";
$sqlRESULT = $sqlCONN->query($sqlCMD);
$sqlRECORD = $sqlRESULT->fetch_assoc();
$oldestDATA = substr($sqlRECORD["timestamp"], 0, 10);
$sqlRESULT->close();
// Calculate the date in displayable format DD/MM/YYYY
$reqDATEdisplay = substr($reqDATE, -2, 2)."/".substr($reqDATE, 5, 2)."/".substr($reqDATE, 0, 4);
// Calculate previous/next buttons status
$btnPREVstatus = "";
$btnNEXTstatus = "";
if ($reqDATE == $oldestDATA)  { $btnPREVstatus = "disabled"; }
if ($reqDATE == date("Y-m-d")) { $btnNEXTstatus = "disabled"; }
// Calculate previous/next buttons dates
$btnPREVdate = date("Y-m-d", strtotime($reqDATE." -1 day"));
$btnNEXTdate = date("Y-m-d", strtotime($reqDATE." +1 day"));
?>
<!-- BEGIN PAGE CONTENTS AND CODE -->




<?php PAGE_Open("Weather", ""); ?>

<div class="row">
   <div class="col-lg-12">
      <div class="panel panel-default">
         <div class="panel-heading">
            <a href="?reqdate=<?php echo $btnPREVdate ?>" class="btn btn-default <?php echo $btnPREVstatus; ?>"><i class="fa fa-angle-left"></i></a>
            <span>Weather Data for <?php echo $reqDATEdisplay ?></span>
            <a href="?reqdate=<?php echo $btnNEXTdate ?>" class="btn  btn-default <?php echo $btnNEXTstatus; ?>"><i class="fa fa-angle-right"></i></a>
         </div>
         <div class="panel-body">
            <div id="daily-weather-chart" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
         </div>
      </div>
   </div>
</div>

<?php PAGE_Close("weather"); ?>

<?php
// Get weather data
$sqlCMD = "SELECT pressure,humidity,temperature,weather_id,weather_text,timestamp FROM `".$DB_Schema."`.`weather_data` WHERE (timestamp like '".$reqDATE."%') ORDER BY timestamp ASC";
$sqlRESULT = $sqlCONN->query($sqlCMD);
$graphARRAYTEMP = "";
$graphARRAYHUMI = "";
$graphARRAYPRES = "";
$graphARRAYCOND = "";
while ($sqlROW = $sqlRESULT->fetch_assoc()) {
   // Store temperature values into array for javascript output
   if ($graphARRAYTEMP != "") { $graphARRAYTEMP = $graphARRAYTEMP.", "; }
   if ($graphARRAYHUMI != "") { $graphARRAYHUMI = $graphARRAYHUMI.", "; }
   if ($graphARRAYPRES != "") { $graphARRAYPRES = $graphARRAYPRES.", "; }
   $graphARRAYTEMP = $graphARRAYTEMP."[".dbDate2Java($sqlROW["timestamp"]).", ".$sqlROW["temperature"]."]";
   $graphARRAYHUMI = $graphARRAYHUMI."[".dbDate2Java($sqlROW["timestamp"]).", ".$sqlROW["humidity"]."]";
   $graphARRAYPRES = $graphARRAYPRES."[".dbDate2Java($sqlROW["timestamp"]).", ".$sqlROW["pressure"]."]";
   // Store weather conditions values into array for javascript output
   $WEATHER_ID = $sqlROW["weather_id"];
   $WEATHER_Icon = "wi-na";
   if (in_array($WEATHER_ID, array(1,1))) { $WEATHER_Icon = "wi-day-sunny"; }
   if (in_array($WEATHER_ID, array(2,2))) { $WEATHER_Icon = "wi-day-cloudy"; }
   if (in_array($WEATHER_ID, array(3,3))) { $WEATHER_Icon = "wi-cloud"; }
   if (in_array($WEATHER_ID, array(4,4))) { $WEATHER_Icon = "wi-cloudy"; }
   if (in_array($WEATHER_ID, array(9,9))) { $WEATHER_Icon = "wi-showers"; }
   if (in_array($WEATHER_ID, array(10,10))) { $WEATHER_Icon = "wi-rain"; }
   if (in_array($WEATHER_ID, array(11,11))) { $WEATHER_Icon = "wi-thunderstorm"; }
   if (in_array($WEATHER_ID, array(13,13))) { $WEATHER_Icon = "wi-snow"; }
   if (in_array($WEATHER_ID, array(50,50))) { $WEATHER_Icon = "wi-fog"; }
   if ($graphARRAYCOND != "") { $graphARRAYCOND = $graphARRAYCOND.", "; }
   $graphARRAYCOND = $graphARRAYCOND."['".$WEATHER_Icon."', '".$sqlROW["weather_text"]."']";
}
$sqlRESULT->close();
// Close MySQL connection
mysqli_close($sqlCONN);
?>

<!-- ADDITIONAL JAVA CODE GOES HERE -->
<script>
   var weatherTemperature = [<?php echo $graphARRAYTEMP; ?>];
   var weatherHumidity = [<?php echo $graphARRAYHUMI; ?>];
   var weatherPressure = [<?php echo $graphARRAYPRES; ?>];
   var weatherConditions = [<?php echo $graphARRAYCOND; ?>];
</script>

<script>
$(document).ready(function () {
    $(function () {
        $('#daily-weather-chart').highcharts({
            chart: {
                type: 'spline'
            },
            title: {
                text: 'Temperatura'
            },
            colors: ['#153E7E', '#B4C7DA', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'],
            xAxis: {
                type: 'datetime'
            },
            yAxis: {
                title: {
                    text: '°C'
                }
            },
            tooltip: {
                shared: true,
                useHTML: true,
                formatter: function () { return tooltipFormatter(this); }
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0,
                    pointWidth: 3
                }
            },
            credits: { enabled: false },
            series: [
                {
                    name: 'Temperatura',
                    data: weatherTemperature
                },
            ]
        });
    });
});

function tooltipFormatter(tooltip) {
   var ret = "";
   var index = tooltip.points[0].point.index;
   // Create the header with reference to the time interval
   ret = "<span style='font-size:10px'>" + Highcharts.dateFormat('%A, %e %b, %H:%M', tooltip.x) + "</span><br>";
   // Symbol text
   ret += '<i class="wi ' + weatherConditions[index][0] + ' fa-lg"></i><b>&nbsp;' + weatherConditions[index][1] + '</b>';
   ret += '<table>';
   // Series values
   ret += "<tr>";
   ret += "<td style='color:#153E7E;padding:0'>Temperatura: </td>";
   ret += "<td style='padding:0'><b>" + weatherTemperature[index][1] + " °C</b><br></td>";
   ret += "</tr>";
   ret += "<tr>";
   ret += "<td style='color:#14AB32;padding:0'>Umidità: </td>";
   ret += "<td style='padding:0'><b>" + weatherHumidity[index][1] + "%</b><br></td>";
   ret += "</tr>";
   ret += "<tr>";
   ret += "<td style='color:#ED561B;padding:0'>Pressione: </td>";
   ret += "<td style='padding:0'><b>" + weatherPressure[index][1] + " hPa</b><br></td>";
   ret += "</tr>";
   // Close the datatable
   ret += "</table>";
   // Return the results
   return ret;
};

</script>
<!-- ADDITIONAL JAVA CODE GOES HERE -->



<!-- END PAGE CONTENTS AND CODE -->
</body>
</html>
