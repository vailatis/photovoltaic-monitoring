<!DOCTYPE html>
<head>
<?php
// Include needed external modules
$webROOT = realpath($_SERVER["DOCUMENT_ROOT"]);
require("$webROOT/_library/_config.php");
require("$webROOT/_library/_masterPage.php");
require("$webROOT/_library/_functions.php");
?>
<!-- BEGIN PAGE CONTENTS AND CODE -->




<?php PAGE_Open("Energia Giornaliera", ""); ?>


        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <span>Produzione/Accumulo Solare</span>
                    </div>
                    <div class="panel-body">
                        <div id="daily-produzione" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <span>Consumo/Importazione Energia</span>
                    </div>
                    <div class="panel-body">
                        <div id="daily-consumo" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <span>Sorgente Alimentazione</span>
                    </div>
                    <div class="panel-body">
                        <div id="daily-sorgente" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                    </div>
                </div>
            </div>
        </div>



<?php PAGE_Close("daily_energycharts"); ?>


<?php
// Open MySQL database connection
$sqlCONN = mysqli_connect($DB_Hostname,$DB_Username,$DB_Password, $DB_Schema);
if ($sqlCONN->connect_errno) {
  die("Could not connect to database: " . $sqlCONN->connect_error . "\n");
}
// Get today power data
$dataPRODOTTO = "";
$dataACCUMULO = "";
$dataCONSUMO = "";
$dataIMPORTATO = "";
$sqlCMD = "SELECT left(timestamp, 10) as 'timestamp',sum(consumo_wattora) as 'consumo_wattora',sum(accumulato_wattora) as 'accumulato_wattora',sum(produzione_pv_wattora) as 'produzione_pv_wattora',sum(importazione_wattora) as 'importazione_wattora' FROM vw_inverter_power WHERE timestamp >= date_format(now(),'%Y%m%d0000') GROUP BY 1 ORDER BY timestamp ASC;";
$sqlRESULT = $sqlCONN->query($sqlCMD);
while ($sqlROW = $sqlRESULT->fetch_assoc()) {
   // Get query data
   $rawDATETIME = $sqlROW["timestamp"]."00";
   $rawPRODOTTO = $sqlROW["produzione_pv_wattora"];
   $rawACCUMULO = $sqlROW["accumulato_wattora"];
   $rawCONSUMO = $sqlROW["consumo_wattora"];
   $rawIMPORTATO = $sqlROW["importazione_wattora"];
   // Make calculations
   $rawDATETIME = substr($rawDATETIME, 0, 4)."-".substr($rawDATETIME, 4, 2)."-".substr($rawDATETIME, 6, 2)." ".substr($rawDATETIME, 8, 2).":".substr($rawDATETIME, 10, 2);
   // Store retrived data into JAVASCRIPT arrays
   if ($dataPRODOTTO != "") { $dataPRODOTTO = $dataPRODOTTO.", "; }
   if ($dataACCUMULO != "") { $dataACCUMULO = $dataACCUMULO.", "; }
   if ($dataCONSUMO != "") { $dataCONSUMO = $dataCONSUMO.", "; }
   if ($dataIMPORTATO != "") { $dataIMPORTATO = $dataIMPORTATO.", "; }
   $dataPRODOTTO = $dataPRODOTTO."[".dbDate2Java($rawDATETIME).", ".$rawPRODOTTO."]";
   $dataACCUMULO = $dataACCUMULO."[".dbDate2Java($rawDATETIME).", ".$rawACCUMULO."]";
   $dataCONSUMO = $dataCONSUMO."[".dbDate2Java($rawDATETIME).", ".$rawCONSUMO."]";
   $dataIMPORTATO = $dataIMPORTATO."[".dbDate2Java($rawDATETIME).", ".$rawIMPORTATO."]";
}
$sqlRESULT->close();
// Get today consumption and sources
$dataSORGENTE = "";
$dataCONSUMATO = "";
$oldSORGENTE = "";
$sqlCMD = "SELECT LEFT(timestamp,11) AS 'timestamp',MAX(status_code) AS 'status_code',max(battery_discharge_current) AS 'battery_discharge_current',CAST(SUM(ac_output_active_power * 0.01667) AS DECIMAL(9,2)) AS 'ac_output_active_power' FROM inverter_data WHERE timestamp >= date_format(now(),'%Y%m%d0000') GROUP BY 1 ORDER BY 1 ASC";
$sqlRESULT = $sqlCONN->query($sqlCMD);
while ($sqlROW = $sqlRESULT->fetch_assoc()) {
	// Get query data
   $rawDATETIME = $sqlROW["timestamp"]."0";
   $rawSORGENTE = $sqlROW["status_code"];
   $rawCONSUMATO = $sqlROW["ac_output_active_power"];
   $rawSCARICABATT = $sqlROW["battery_discharge_current"];
   // Make calculations
   $rawDATETIME = substr($rawDATETIME, 0, 4)."-".substr($rawDATETIME, 4, 2)."-".substr($rawDATETIME, 6, 2)." ".substr($rawDATETIME, 8, 2).":".substr($rawDATETIME, 10, 2);
	switch ($rawSORGENTE) {
		case "L":
         // Sorgente: RETE
			$rawSORGENTE = "#D94FD7";
			break;
		case "B":
         // Sorgente: BATTERIE
			$rawSORGENTE = "#337AB7";
			break;
		case "G":
         // Sorgente: FOTOVOLTAICO
			$rawSORGENTE = "#5CB85C";
         if ($rawSCARICABATT > 0) { 
            // Sorgente: FOTOVOLTAICO + BATTERIE
            $rawSORGENTE = "#50BAD9";
         }
			break;
		default:
			$rawSORGENTE = "#333333";
	}
   // Store retrived data into JAVASCRIPT arrays
   if ($dataCONSUMATO != "") { $dataCONSUMATO = $dataCONSUMATO.", "; }
   if ($dataSORGENTE != "") { $dataSORGENTE = $dataSORGENTE.", "; }
   $dataCONSUMATO = $dataCONSUMATO."[".dbDate2Java($rawDATETIME).", ".$rawCONSUMATO."]";
   $dataSORGENTE = $dataSORGENTE."{value: ".dbDate2Java($rawDATETIME).", color: '".$rawSORGENTE."'}";
}
$sqlRESULT->close();
// Close MySQL connection
mysqli_close($sqlCONN);
?>

<!-- ADDITIONAL JAVA CODE GOES HERE -->
<script>
   var data_produzione = [<?php echo $dataPRODOTTO; ?>];
   var data_accumulo = [<?php echo $dataACCUMULO; ?>];
   var data_consumo = [<?php echo $dataCONSUMO; ?>];
   var data_importato = [<?php echo $dataIMPORTATO; ?>];
   var data_sorgente = [<?php echo $dataSORGENTE; ?>];
   var data_consumato = [<?php echo $dataCONSUMATO; ?>];
</script>
    
<script>
$(document).ready(function () {
   // Define the DAILY-PRODUZIONE graph object and its properties
   $('#daily-produzione').highcharts({
      chart: {type: 'column'},
      title: {text: ''},
      colors: ['#5CB85C', '#337AB7'],
      xAxis: {type: 'datetime'},
      yAxis: {min: 0, title: {text: 'Watt/Ora'}},
      credits: { enabled: false },
      tooltip: {
         headerFormat: '<span style="font-size:12px"><b>{point.key}</b></span><table>',
         pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td><td style="padding:0;text-align:right;"><b>{point.y:.0f} Wh</b></td></tr>',
         footerFormat: '</table>',
         shared: true,
         useHTML: true
      },
      plotOptions: { series: { marker: { enabled: false } }, column: {grouping: false, shadow: false, borderWidth: 0} },
      series: [{name: 'Energia Prodotta', data: data_produzione},{name: 'Energia Accumulata', data: data_accumulo}]
   });
   // Define the DAILY-CONSUMO graph object and its properties
   $('#daily-consumo').highcharts({
      chart: {type: 'column'},
      title: {text: ''},
      colors: ['#F0AD4E', '#D9534F'],
      xAxis: {type: 'datetime'},
      yAxis: {min: 0, title: {text: 'Watt/Ora'}},
      credits: { enabled: false },
      tooltip: {
         headerFormat: '<span style="font-size:12px"><b>{point.key}</b></span><table>',
         pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td><td style="padding:0;text-align:right;"><b>{point.y:.0f} Wh</b></td></tr>',
         footerFormat: '</table>',
         shared: true,
         useHTML: true
      },
      plotOptions: { series: { marker: { enabled: false } }, column: {grouping: false, shadow: false, borderWidth: 0} },
      series: [{name: 'Potenza Consumata', data: data_consumo},{name: 'Potenza Importata', data: data_importato}]
   });
   // Define the DAILY-SORGENTE graph object and its properties
   $('#daily-sorgente').highcharts({
      chart: {type: 'areaspline'},
      title: {text: ''},
      colors: ['#D94FD7', '#D9534F'],
      xAxis: {type: 'datetime'},
      yAxis: {min: 0, title: {text: 'Watt/Ora'}},
      credits: { enabled: false },
      tooltip: {
         shared: true,
         useHTML: true,
         formatter: function () { return tooltipFormatter(this); }
      },
      plotOptions: { 
         areaspline: { 
            zoneAxis: 'x', zones: data_sorgente ,
            events: { legendItemClick:function () { return false; } }
         },
         series: { marker: { enabled: false } }
      },
      series: [{name:'Fotovoltaico', data:data_consumato, color:'#5CB85C'}, {name:'Fotovoltaico + Batterie', color:'#50BAD9'}, {name:'Batterie', color:'#337AB7'}, {name:'ENEL', color:'#D94FD7'}]
   });
});
//---------------------------------------------------------------------
function tooltipFormatter(tooltip) {
   var ret = "";
   var index = tooltip.points[0].point.index;
   // Create the header with reference to the time interval
   ret = "<span style='font-size:12px'><b>" + Highcharts.dateFormat('%A, %e %b, %H:%M', tooltip.x) + "</b></span><br>";
   ret += "<table>";
   // Add all series
   Highcharts.each(tooltip.points, function (point) {
      var series = point.series;
      ret += "<tr><td style='padding:0'>Consumo: </td><td style='padding:0'><b>" + Math.round(Highcharts.pick(point.point.value, point.y)) + " Wh</b></td></tr>";
      switch(point.point.color) {
         case "#5CB85C":
            ret += "<tr><td style='padding:0'>Sorgente: </td><td style='color:" + point.point.color + ";padding:0'><b>Fotovoltaico</b></td></tr>";
         break;
         case "#50BAD9":
            ret += "<tr><td style='padding:0'>Sorgente: </td><td style='color:" + point.point.color + ";padding:0'><b>Fotovoltaico + Batterie</b></td></tr>";
         break;
         case "#337AB7":
            ret += "<tr><td style='padding:0'>Sorgente: </td><td style='color:" + point.point.color + ";padding:0'><b>Batterie</b></td></tr>";
         break;
         case "#D94FD7":
            ret += "<tr><td style='padding:0'>Sorgente: </td><td style='color:" + point.point.color + ";padding:0'><b>ENEL</b></td></tr>";
         break;
         default:
            ret += "<tr><td style='padding:0'>Sorgente: </td><td style='padding:0'><b>Sconosciuto</b></td></tr>";
      }
   });
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
