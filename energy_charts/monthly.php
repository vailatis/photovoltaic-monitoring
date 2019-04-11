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




<?php PAGE_Open("Energia Mensile", ""); ?>

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <span>Produzione/Accumulo Solare</span>
                    </div>
                    <div class="panel-body">
                        <div id="monthly-produzione" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
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
                        <div id="monthly-consumo" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <span>Utilizzo Sorgenti di Alimentazione</span>
                    </div>
                    <div class="panel-body">
                        <div id="monthly-sorgenti" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                    </div>
                </div>
            </div>
        </div>

<?php PAGE_Close("monthly_energycharts"); ?>

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
$sqlCMD = "SELECT timestamp,consumo_wh,acc_wh,prod_pv_wh,import_wh FROM vw_inverter_power_daily WHERE timestamp >= date_format(now() - INTERVAL 1 MONTH,'%Y%m%d') GROUP BY 1 ORDER BY timestamp ASC;";
$sqlRESULT = $sqlCONN->query($sqlCMD);
while ($sqlROW = $sqlRESULT->fetch_assoc()) {
   // Get query data
   $rawDATETIME = $sqlROW["timestamp"]."0000";
   $rawPRODOTTO = number_format(($sqlROW["prod_pv_wh"] / 1000), 1, ".", "");
   $rawACCUMULO = number_format(($sqlROW["acc_wh"] / 1000), 1, ".", "");
   $rawCONSUMO = number_format(($sqlROW["consumo_wh"] / 1000), 1, ".", "");
   $rawIMPORTATO = number_format(($sqlROW["import_wh"] / 1000), 1, ".", "");
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
$tmpDATA = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
$tmpSOLARE = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
$tmpSOLBATT = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
$tmpBATTERIE = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
$tmpRETE = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
$CNT = -1;
$prevDATE = "";
$sqlCMD = "SELECT LEFT(timestamp,8) AS 'timestamp',IF (battery_discharge_current > 0 and status_code='G', 'H', status_code) AS 'status_code',COUNT(*) AS 'totalMinutes' FROM inverter_data WHERE timestamp >= date_format(now() - INTERVAL 1 MONTH,'%Y%m%d0000') GROUP BY 1,2 ORDER BY 1 ASC";
$sqlRESULT = $sqlCONN->query($sqlCMD);
while ($sqlROW = $sqlRESULT->fetch_assoc()) {
   // Get query data
   $rawDATETIME = $sqlROW["timestamp"]."0000";
   $rawSORGENTE = $sqlROW["status_code"];
   $rawPERCENTUALE = $sqlROW["totalMinutes"];
   // Make calculations
   $rawDATETIME = substr($rawDATETIME, 0, 4)."-".substr($rawDATETIME, 4, 2)."-".substr($rawDATETIME, 6, 2)." ".substr($rawDATETIME, 8, 2).":".substr($rawDATETIME, 10, 2);
   if ($prevDATE != $rawDATETIME) { $prevDATE = $rawDATETIME; $CNT += 1; }
   $tmpDATA[$CNT] = "".$rawDATETIME;
   if ($rawSORGENTE == "G") { $tmpSOLARE[$CNT] = "".$rawPERCENTUALE; }
   if ($rawSORGENTE == "H") { $tmpSOLBATT[$CNT] = "".$rawPERCENTUALE; }
   if ($rawSORGENTE == "B") { $tmpBATTERIE[$CNT] = "".$rawPERCENTUALE; }
   if ($rawSORGENTE == "L") { $tmpRETE[$CNT] = "".$rawPERCENTUALE; }
}
$sqlRESULT->close();
$sorgenteSOLARE = "";
$sorgenteSOLBATT = "";
$sorgenteBATTERIE = "";
$sorgenteRETE = "";
for ($III = 0; $III < $CNT; $III++) {
   if ($III > 0) { $sorgenteDATA .= ", "; $sorgenteSOLARE .= ", "; $sorgenteSOLBATT .= ", "; $sorgenteBATTERIE .= ", "; $sorgenteRETE .= ", ";}
   $vlTOT = $tmpSOLARE[$III] + $tmpSOLBATT[$III] + $tmpBATTERIE[$III] + $tmpRETE[$III];
   $tmpSOL = round(($tmpSOLARE[$III] * 100)/$vlTOT);
   $tmpSB = round(($tmpSOLBATT[$III] * 100)/$vlTOT);
   $tmpBAT = round(($tmpBATTERIE[$III] * 100)/$vlTOT);
   $tmpRET = 100 - ($tmpSOL + $tmpSB + $tmpBAT);
   $sorgenteSOLARE .= "[".dbDate2Java($tmpDATA[$III]).", ".$tmpSOL."]";
   $sorgenteSOLBATT .= "[".dbDate2Java($tmpDATA[$III]).", ".$tmpSB."]";
   $sorgenteBATTERIE .= "[".dbDate2Java($tmpDATA[$III]).", ".$tmpBAT."]";
   $sorgenteRETE .= "[".dbDate2Java($tmpDATA[$III]).", ".$tmpRET."]";
}
// Close MySQL connection
mysqli_close($sqlCONN);
?>

<!-- ADDITIONAL JAVA CODE GOES HERE -->
<script>
   var data_produzione = [<?php echo $dataPRODOTTO; ?>];
   var data_accumulo = [<?php echo $dataACCUMULO; ?>];
   var data_consumo = [<?php echo $dataCONSUMO; ?>];
   var data_importato = [<?php echo $dataIMPORTATO; ?>];
   var sorgente_solare = [<?php echo $sorgenteSOLARE; ?>];
   var sorgente_solbatt = [<?php echo $sorgenteSOLBATT; ?>];
   var sorgente_batterie = [<?php echo $sorgenteBATTERIE; ?>];
   var sorgente_rete = [<?php echo $sorgenteRETE; ?>];
</script>
    
<script>
$(document).ready(function () {
   // Define the MONTHLY-PRODUZIONE graph object and its properties
   $('#monthly-produzione').highcharts({
      chart: {type: 'column'},
      title: {text: ''},
      colors: ['#5CB85C', '#337AB7'],
      xAxis: {type: 'datetime'},
      yAxis: {min: 0, title: {text: 'Chilowatt/Ora'}},
      credits: { enabled: false },
      tooltip: {
         headerFormat: '<span style="font-size:12px"><b>{point.key}</b></span><table>',
         pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td><td style="padding:0;text-align:right;"><b>{point.y:.1f} KWh</b></td></tr>',
         footerFormat: '</table>',
         shared: true,
         useHTML: true
      },
      plotOptions: { series: { marker: { enabled: false } }, column: {grouping: false, shadow: false, borderWidth: 0} },
      series: [{name: 'Energia Prodotta', data: data_produzione},{name: 'Energia Accumulata', data: data_accumulo}]
   });
   // Define the MONTHLY-CONSUMO graph object and its properties
   $('#monthly-consumo').highcharts({
      chart: {type: 'column'},
      title: {text: ''},
      colors: ['#F0AD4E', '#D9534F'],
      xAxis: {type: 'datetime'},
      yAxis: {min: 0, title: {text: 'Chilowatt/Ora'}},
      credits: { enabled: false },
      tooltip: {
         headerFormat: '<span style="font-size:12px"><b>{point.key}</b></span><table>',
         pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td><td style="padding:0;text-align:right;"><b>{point.y:.1f} KWh</b></td></tr>',
         footerFormat: '</table>',
         shared: true,
         useHTML: true
      },
      plotOptions: { series: { marker: { enabled: false } }, column: {grouping: false, shadow: false, borderWidth: 0} },
      series: [{name: 'Potenza Consumata', data: data_consumo},{name: 'Potenza Importata', data: data_importato}]
   });
   // Define the WEEKLY-SORGENTI graph object and its properties
   $('#monthly-sorgenti').highcharts({
      chart: {type: 'column'},
      title: {text: ''},
      colors: ['#5CB85C', '#50BAD9', '#337AB7','#D94FD7'],
      xAxis: {type: 'datetime'},
      yAxis: {min: 0, title: {text: 'Percentuale di utilizzo nella giornata'}},
      credits: { enabled: false },
      tooltip: {
         headerFormat: '<span style="font-size:12px"><b>{point.key}</b></span><table>',
         pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td><td style="padding:0;text-align:right;"><b>{point.y:.0f}%</b></td></tr>',
         footerFormat: '</table>',
         shared: true,
         useHTML: true
      },
      plotOptions: { column: { stacking: 'normal', shadow: false, borderWidth: 0 } },
      series: [
         {name: 'Solare', data: sorgente_solare},
         {name: 'Solare + Batterie', data: sorgente_solbatt},
         {name: 'Batterie', data: sorgente_batterie},
         {name: 'Rete', data: sorgente_rete}
      ]
   });
});
    </script>
<!-- ADDITIONAL JAVA CODE GOES HERE -->



<!-- END PAGE CONTENTS AND CODE -->
</body>
</html>
