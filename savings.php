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




<?php PAGE_Open("Savings", ""); ?>

<div class="row">
   <div class="col-lg-12">
      <div class="panel panel-default">
         <div class="panel-heading">
            <span>Risparmio Giornaliero</span>
         </div>
         <div class="panel-body">
            <div id="daily-saving-chart" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
         </div>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-lg-12">
      <div class="panel panel-default">
         <div class="panel-heading">
            <span>Risparmio Mensile</span>
         </div>
         <div class="panel-body">
            <div id="monthly-saving-chart" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
         </div>
      </div>
   </div>
</div>

<?php PAGE_Close("savings"); ?>

<?php
// Open MySQL database connection
$sqlCONN = mysqli_connect($DB_Hostname,$DB_Username,$DB_Password, $DB_Schema);
if ($sqlCONN->connect_errno) {
  die("Could not connect to database: " . $sqlCONN->connect_error . "\n");
}
// Get daily savings data
$DDgraphDATES = "";
$DDgraphMONEYimp = "";
$DDgraphMONEYprod = "";
$DDgraphIMPORT = "";
$DDgraphPROD = "";
$DDtotIMPORT = 0;
$DDtotPROD = 0;
$sqlCMD = "SELECT LEFT(timestamp,8) AS 'timestamp',CAST((SUM(IF(status_code='L',ac_output_active_power * 0.01667,0))/1000) AS DECIMAL(7,2)) AS 'importazione_kwh',CAST((SUM(IF(status_code<>'L',ac_output_active_power * 0.01667,0))/1000) AS DECIMAL(7,2)) AS 'produzione_kwh' FROM inverter_data WHERE timestamp>=date_format(now(), '%Y%m000000') GROUP BY 1 ORDER BY 1 ASC";
$sqlRESULT = $sqlCONN->query($sqlCMD);
while ($sqlROW = $sqlRESULT->fetch_assoc()) {
   // Insert array separators
   if ($DDgraphDATES != "") { $DDgraphDATES = $DDgraphDATES.", "; }
   if ($DDgraphMONEYimp != "") { $DDgraphMONEYimp = $DDgraphMONEYimp.", "; }
   if ($DDgraphMONEYprod != "") { $DDgraphMONEYprod = $DDgraphMONEYprod.", "; }
   if ($DDgraphIMPORT != "") { $DDgraphIMPORT = $DDgraphIMPORT.", "; }
   if ($DDgraphPROD != "") { $DDgraphPROD = $DDgraphPROD.", "; }
   // Store dates into array
   $DDgraphDATES = $DDgraphDATES."'".substr($sqlROW["timestamp"], 6, 2)."/".substr($sqlROW["timestamp"], 4, 2)."/".substr($sqlROW["timestamp"], 0, 4)."'";
   // Store CONSUMO
   $DDtotIMPORT = $DDtotIMPORT + number_format($sqlROW["importazione_kwh"], 2, '.', '');
   $DDgraphIMPORT = $DDgraphIMPORT.number_format($sqlROW["importazione_kwh"], 2, '.', '');
   // Store PRODUZIONE
   $DDtotPROD = $DDtotPROD + number_format($sqlROW["produzione_kwh"], 2, '.', '');
   $DDgraphPROD = $DDgraphPROD.number_format($sqlROW["produzione_kwh"], 2, '.', '');
   // Store SPESA in EURO
   $DDtmpPREZZO = 0;
   if ($DDtotIMPORT < 1500) { $DDtmpPREZZO = $sqlROW["importazione_kwh"] * 0.25; }
   if (($DDtotIMPORT >= 1500)and($DDtotIMPORT < 2100)) { $DDtmpPREZZO = $sqlROW["importazione_kwh"] * 0.21; }
   if ($DDtotIMPORT >= 2100) { $DDtmpPREZZO = $sqlROW["importazione_kwh"] * 0.19; }
   $DDgraphMONEYimp = $DDgraphMONEYimp.(-1 * number_format($DDtmpPREZZO, 2, '.', ''));
   // Store RISPARMIO in EURO
   $DDtmpPREZZO = 0;
   if ($DDtotPROD < 1500) { $DDtmpPREZZO = $sqlROW["produzione_kwh"] * 0.25; }
   if (($DDtotPROD >= 1500)and($DDtotPROD < 2100)) { $DDtmpPREZZO = $sqlROW["produzione_kwh"] * 0.21; }
   if ($DDtotPROD >= 2100) { $DDtmpPREZZO = $sqlROW["produzione_kwh"] * 0.19; }
   $DDgraphMONEYprod = $DDgraphMONEYprod.number_format($DDtmpPREZZO, 2, '.', '');
}
$sqlRESULT->close();
// Get monthly savings data
$graphDATES = "";
$graphMONEYimp = "";
$graphMONEYprod = "";
$graphIMPORT = "";
$graphPROD = "";
$totIMPORT = 0;
$totPROD = 0;
$sqlCMD = "SELECT LEFT(timestamp,6) AS 'timestamp',CAST((SUM(IF(status_code='L',ac_output_active_power * 0.01667,0))/1000) AS DECIMAL(7,2)) AS 'importazione_kwh',CAST((SUM(IF(status_code<>'L',ac_output_active_power * 0.01667,0))/1000) AS DECIMAL(7,2)) AS 'produzione_kwh' FROM inverter_data WHERE timestamp>=date_format(now(), '%Y00000000') GROUP BY 1 ORDER BY 1 ASC";
$sqlRESULT = $sqlCONN->query($sqlCMD);
while ($sqlROW = $sqlRESULT->fetch_assoc()) {
   // Insert array separators
   if ($graphDATES != "") { $graphDATES = $graphDATES.", "; }
   if ($graphMONEYimp != "") { $graphMONEYimp = $graphMONEYimp.", "; }
   if ($graphMONEYprod != "") { $graphMONEYprod = $graphMONEYprod.", "; }
   if ($graphIMPORT != "") { $graphIMPORT = $graphIMPORT.", "; }
   if ($graphPROD != "") { $graphPROD = $graphPROD.", "; }
   // Store dates into array
   if (substr($sqlROW["timestamp"], 4, 2) == "01") { $graphDATES = $graphDATES."'Gennaio ".substr($sqlROW["timestamp"], 0, 4)."'"; }
   if (substr($sqlROW["timestamp"], 4, 2) == "02") { $graphDATES = $graphDATES."'Febbraio ".substr($sqlROW["timestamp"], 0, 4)."'"; }
   if (substr($sqlROW["timestamp"], 4, 2) == "03") { $graphDATES = $graphDATES."'Marzo ".substr($sqlROW["timestamp"], 0, 4)."'"; }
   if (substr($sqlROW["timestamp"], 4, 2) == "04") { $graphDATES = $graphDATES."'Aprile ".substr($sqlROW["timestamp"], 0, 4)."'"; }
   if (substr($sqlROW["timestamp"], 4, 2) == "05") { $graphDATES = $graphDATES."'Maggio ".substr($sqlROW["timestamp"], 0, 4)."'"; }
   if (substr($sqlROW["timestamp"], 4, 2) == "06") { $graphDATES = $graphDATES."'Giugno ".substr($sqlROW["timestamp"], 0, 4)."'"; }
   if (substr($sqlROW["timestamp"], 4, 2) == "07") { $graphDATES = $graphDATES."'Luglio ".substr($sqlROW["timestamp"], 0, 4)."'"; }
   if (substr($sqlROW["timestamp"], 4, 2) == "08") { $graphDATES = $graphDATES."'Agosto ".substr($sqlROW["timestamp"], 0, 4)."'"; }
   if (substr($sqlROW["timestamp"], 4, 2) == "09") { $graphDATES = $graphDATES."'Settembre ".substr($sqlROW["timestamp"], 0, 4)."'"; }
   if (substr($sqlROW["timestamp"], 4, 2) == "10") { $graphDATES = $graphDATES."'Ottobre ".substr($sqlROW["timestamp"], 0, 4)."'"; }
   if (substr($sqlROW["timestamp"], 4, 2) == "11") { $graphDATES = $graphDATES."'Novembre ".substr($sqlROW["timestamp"], 0, 4)."'"; }
   if (substr($sqlROW["timestamp"], 4, 2) == "12") { $graphDATES = $graphDATES."'Dicembre ".substr($sqlROW["timestamp"], 0, 4)."'"; }
   // Store CONSUMO
   $totIMPORT = $totIMPORT + number_format($sqlROW["importazione_kwh"], 2, '.', '');
   $graphIMPORT = $graphIMPORT.number_format($sqlROW["importazione_kwh"], 2, '.', '');
   // Store PRODUZIONE
   $totPROD = $totPROD + number_format($sqlROW["produzione_kwh"], 2, '.', '');
   $graphPROD = $graphPROD.number_format($sqlROW["produzione_kwh"], 2, '.', '');
   // Store SPESA in EURO
   $tmpPREZZO = 0;
   if ($totIMPORT < 1500) { $tmpPREZZO = $sqlROW["importazione_kwh"] * 0.25; }
   if (($totIMPORT >= 1500)and($totIMPORT < 2100)) { $tmpPREZZO = $sqlROW["importazione_kwh"] * 0.21; }
   if ($totIMPORT >= 2100) { $tmpPREZZO = $sqlROW["importazione_kwh"] * 0.19; }
   $graphMONEYimp = $graphMONEYimp.(-1 * number_format($tmpPREZZO, 2, '.', ''));
   // Store RISPARMIO in EURO
   $tmpPREZZO = 0;
   if ($totPROD < 1500) { $tmpPREZZO = $sqlROW["produzione_kwh"] * 0.25; }
   if (($totPROD >= 1500)and($totPROD < 2100)) { $tmpPREZZO = $sqlROW["produzione_kwh"] * 0.21; }
   if ($totPROD >= 2100) { $tmpPREZZO = $sqlROW["produzione_kwh"] * 0.19; }
   $graphMONEYprod = $graphMONEYprod.number_format($tmpPREZZO, 2, '.', '');
}
$sqlRESULT->close();
// Close MySQL connection
mysqli_close($sqlCONN);
?>

<!-- ADDITIONAL JAVA CODE GOES HERE -->
<script>
   var DDvlDATE = [<?php echo $DDgraphDATES; ?>];
   var DDvlMONEYSimp = [<?php echo $DDgraphMONEYimp; ?>];
   var DDvlMONEYSprod = [<?php echo $DDgraphMONEYprod; ?>];
   var DDvlIMPORTkwh = [<?php echo $DDgraphIMPORT; ?>];
   var DDvlPRODkwh = [<?php echo $DDgraphPROD; ?>];
   var vlDATE = [<?php echo $graphDATES; ?>];
   var vlMONEYSimp = [<?php echo $graphMONEYimp; ?>];
   var vlMONEYSprod = [<?php echo $graphMONEYprod; ?>];
   var vlIMPORTkwh = [<?php echo $graphIMPORT; ?>];
   var vlPRODkwh = [<?php echo $graphPROD; ?>];
</script>

<script>
$(document).ready(function () {
    $(function () {
        $('#daily-saving-chart').highcharts({
			chart: { type: 'column' },
            colors: ['#D9534F','#5CB85C'],
			title: { text: '' },
            xAxis: { categories: DDvlDATE },
            yAxis: { title: {text: ''},  labels: { format: '{value} €' } },
			credits: { enabled: false },
			plotOptions: { series: { marker: { enabled: false } }, column: {grouping: false, shadow: false, borderWidth: 0} },
            series: [{ name: '', data: DDvlMONEYSimp },{ name: '', data: DDvlMONEYSprod }],
            tooltip: { shared: true, useHTML: true, formatter: function () { return tooltipFormatterDD(this); } },
			legend: { enabled: false }
        });
    });
});
$(document).ready(function () {
    $(function () {
        $('#monthly-saving-chart').highcharts({
			chart: { type: 'column' },
            colors: ['#D9534F','#5CB85C'],
			title: { text: '' },
            xAxis: { categories: vlDATE , labels: { formatter: function () { return this.value.substring(0,3)+' '+this.value.substring(this.value.length-4); } } },
            yAxis: { title: {text: ''},  labels: { format: '{value} €' } },
			credits: { enabled: false },
			plotOptions: { series: { marker: { enabled: false } }, column: {grouping: false, shadow: false, borderWidth: 0} },
            series: [{ name: '', data: vlMONEYSimp },{ name: '', data: vlMONEYSprod }],
            tooltip: { shared: true, useHTML: true, formatter: function () { return tooltipFormatterMM(this); } },
			legend: { enabled: false }
        });
    });
});

function tooltipFormatterDD(tooltip) {
   var ret = "";
   var index = tooltip.points[0].point.index;
   // Create the header with reference to the time interval
   ret = "<span style='font-size:12px'><b>" + tooltip.x + "</b></span><br>";
   // Symbol text
   ret += 'Produzione:<b>&nbsp;' + DDvlPRODkwh[index] + ' kW/h</b><br>';
   ret += 'Importazione:<b>&nbsp;' + DDvlIMPORTkwh[index] + ' kW/h</b><br>';
   ret += 'Spesa:<b style="color:#D9534F;">&nbsp;' + (-1 * DDvlMONEYSimp[index]).toFixed(2) + ' €</b><br>';
   ret += 'Risparmio:<b style="color:#5CB85C;">&nbsp;' + DDvlMONEYSprod[index].toFixed(2) + ' €</b><br>';
   // Return the results
   return ret;
};
function tooltipFormatterMM(tooltip) {
   var ret = "";
   var index = tooltip.points[0].point.index;
   // Create the header with reference to the time interval
   ret = "<span style='font-size:12px'><b>" + tooltip.x + "</b></span><br>";
   // Symbol text
   ret += 'Produzione:<b>&nbsp;' + vlPRODkwh[index] + ' kW/h</b><br>';
   ret += 'Importazione:<b>&nbsp;' + vlIMPORTkwh[index] + ' kW/h</b><br>';
   ret += 'Spesa:<b style="color:#D9534F;">&nbsp;' + (-1 * vlMONEYSimp[index]).toFixed(2) + ' €</b><br>';
   ret += 'Risparmio:<b style="color:#5CB85C;">&nbsp;' + vlMONEYSprod[index].toFixed(2) + ' €</b><br>';
   // Return the results
   return ret;
};
</script>
<!-- ADDITIONAL JAVA CODE GOES HERE -->



<!-- END PAGE CONTENTS AND CODE -->
</body>
</html>
