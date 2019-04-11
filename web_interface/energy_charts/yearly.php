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




<?php PAGE_Open("Energia Annuale", ""); ?>

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <span>Produzione/Accumulo Solare</span>
                    </div>
                    <div class="panel-body">
                        <div id="yearly-produzione" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
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
                        <div id="yearly-consumo" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
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
                        <div id="yearly-sorgenti" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                    </div>
                </div>
            </div>
        </div>

<?php PAGE_Close("yearly_energycharts"); ?>

<?php
// Open MySQL database connection
$sqlCONN = mysqli_connect($DB_Hostname,$DB_Username,$DB_Password, $DB_Schema);
if ($sqlCONN->connect_errno) {
  die("Could not connect to database: " . $sqlCONN->connect_error . "\n");
}
// Reset data containers
$lstPROD = array("-", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0");
$lstACC = array("-", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0");
$lstCONS = array("-", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0");
$lstIMP = array("-", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0");
$lstMONTH = array("-", "", "", "", "", "", "", "", "", "", "", "", "");
// Get yearly power data
$dataPRODOTTO = "";
$dataACCUMULO = "";
$dataCONSUMO = "";
$dataIMPORTATO = "";
$idxMONTH = 1;
$sqlCMD = "SELECT LEFT(timestamp,6) AS 'timestamp',SUM(consumo_wh) AS 'consumo_wh',SUM(acc_wh) AS 'acc_wh',SUM(prod_pv_wh) AS 'prod_pv_wh',SUM(import_wh) AS 'import_wh' FROM vw_inverter_power_daily WHERE timestamp >= date_format(now() - INTERVAL 11 MONTH,'%Y%m000000') GROUP BY 1 ORDER BY timestamp ASC;";
$sqlRESULT = $sqlCONN->query($sqlCMD);
while ($sqlROW = $sqlRESULT->fetch_assoc()) {
   // Get query data
   $rawDATETIME = $sqlROW["timestamp"];
   $rawPRODOTTO = number_format(($sqlROW["prod_pv_wh"] / 1000), 2, ".", "");
   $rawACCUMULO = number_format(($sqlROW["acc_wh"] / 1000), 2, ".", "");
   $rawCONSUMO = number_format(($sqlROW["consumo_wh"] / 1000), 2, ".", "");
   $rawIMPORTATO = number_format(($sqlROW["import_wh"] / 1000), 2, ".", "");
   // Make calculations
   $dataMONTH = intval(substr($rawDATETIME, 4, 2));
   // Process months
   if ($dataMONTH ==  1) { $lstMONTH[$idxMONTH] = "Gennaio ".$dataMONTH = intval(substr($rawDATETIME, 0, 4)); }
   if ($dataMONTH ==  2) { $lstMONTH[$idxMONTH] = "Febbraio ".$dataMONTH = intval(substr($rawDATETIME, 0, 4)); }
   if ($dataMONTH ==  3) { $lstMONTH[$idxMONTH] = "Marzo ".$dataMONTH = intval(substr($rawDATETIME, 0, 4)); }
   if ($dataMONTH ==  4) { $lstMONTH[$idxMONTH] = "Aprile ".$dataMONTH = intval(substr($rawDATETIME, 0, 4)); }
   if ($dataMONTH ==  5) { $lstMONTH[$idxMONTH] = "Maggio ".$dataMONTH = intval(substr($rawDATETIME, 0, 4)); }
   if ($dataMONTH ==  6) { $lstMONTH[$idxMONTH] = "Giugno ".$dataMONTH = intval(substr($rawDATETIME, 0, 4)); }
   if ($dataMONTH ==  7) { $lstMONTH[$idxMONTH] = "Luglio ".$dataMONTH = intval(substr($rawDATETIME, 0, 4)); }
   if ($dataMONTH ==  8) { $lstMONTH[$idxMONTH] = "Agosto ".$dataMONTH = intval(substr($rawDATETIME, 0, 4)); }
   if ($dataMONTH ==  9) { $lstMONTH[$idxMONTH] = "Settembre ".$dataMONTH = intval(substr($rawDATETIME, 0, 4)); }
   if ($dataMONTH == 10) { $lstMONTH[$idxMONTH] = "Ottobre ".$dataMONTH = intval(substr($rawDATETIME, 0, 4)); }
   if ($dataMONTH == 11) { $lstMONTH[$idxMONTH] = "Novembre ".$dataMONTH = intval(substr($rawDATETIME, 0, 4)); }
   if ($dataMONTH == 12) { $lstMONTH[$idxMONTH] = "Dicembre ".$dataMONTH = intval(substr($rawDATETIME, 0, 4)); }
   // Store retrived data into JAVASCRIPT arrays
   $lstPROD[$idxMONTH] = $rawPRODOTTO;
   $lstACC[$idxMONTH] = $rawACCUMULO;
   $lstCONS[$idxMONTH] = $rawCONSUMO;
   $lstIMP[$idxMONTH] = $rawIMPORTATO;
   // Increment month counter
   $idxMONTH += 1;
}
$sqlRESULT->close();
// Get today consumption and sources
$tmpSOLARE = array("-", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0");
$tmpSOLBATT = array("-", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0");
$tmpBATTERIE = array("-", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0");
$tmpRETE = array("-", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0");
$idxMONTH = 0;
$prevDATE = "";
$sqlCMD = "SELECT LEFT(timestamp,6) AS 'timestamp',IF (battery_discharge_current > 0 and status_code='G', 'H', status_code) AS 'status_code',COUNT(*) AS 'totalMinutes' FROM inverter_data WHERE timestamp >= date_format(now() - INTERVAL 11 MONTH,'%Y%m000000') GROUP BY 1,2 ORDER BY 1 ASC";
$sqlRESULT = $sqlCONN->query($sqlCMD);
while ($sqlROW = $sqlRESULT->fetch_assoc()) {
   // Get query data
   $rawDATETIME = $sqlROW["timestamp"]."0000";
   $rawSORGENTE = $sqlROW["status_code"];
   $rawPERCENTUALE = $sqlROW["totalMinutes"];
   // Make calculations
   if ($prevDATE != $rawDATETIME) { $prevDATE = $rawDATETIME; $idxMONTH += 1; }
   if ($rawSORGENTE == "G") { $tmpSOLARE[$idxMONTH] = "".$rawPERCENTUALE; }
   if ($rawSORGENTE == "H") { $tmpSOLBATT[$idxMONTH] = "".$rawPERCENTUALE; }
   if ($rawSORGENTE == "B") { $tmpBATTERIE[$idxMONTH] = "".$rawPERCENTUALE; }
   if ($rawSORGENTE == "L") { $tmpRETE[$idxMONTH] = "".$rawPERCENTUALE; }
}
$sqlRESULT->close();
$sorgenteSOLARE = "";
$sorgenteSOLBATT = "";
$sorgenteBATTERIE = "";
$sorgenteRETE = "";
for ($III=1; $III<=12; $III++) {
   if ($III > 1) { $sorgenteDATA .= ", "; $sorgenteSOLARE .= ", "; $sorgenteSOLBATT .= ", "; $sorgenteBATTERIE .= ", "; $sorgenteRETE .= ", ";}
   $vlTOT = $tmpSOLARE[$III] + $tmpSOLBATT[$III] + $tmpBATTERIE[$III] + $tmpRETE[$III];
   if ($vlTOT > 0) {
      $tmpSOL = round(($tmpSOLARE[$III] * 100)/$vlTOT);
      $tmpSB = round(($tmpSOLBATT[$III] * 100)/$vlTOT);
      $tmpBAT = round(($tmpBATTERIE[$III] * 100)/$vlTOT);
      $tmpRET = 100 - ($tmpSOL + $tmpSB + $tmpBAT);
   } else {
      $tmpSOL = 0;
      $tmpSB = 0;
      $tmpBAT = 0;
      $tmpRET = 0;
   }
   $sorgenteSOLARE .= $tmpSOL;
   $sorgenteSOLBATT .= $tmpSB;
   $sorgenteBATTERIE .= $tmpBAT;
   $sorgenteRETE .= $tmpRET;
}
// Close MySQL connection
mysqli_close($sqlCONN);
// Generate the JavaScript array data
$dataPRODOTTO = "".$lstPROD[1].",".$lstPROD[2].",".$lstPROD[3].",".$lstPROD[4].",".$lstPROD[5].",".$lstPROD[6].",".$lstPROD[7].",".$lstPROD[8].",".$lstPROD[9].",".$lstPROD[10].",".$lstPROD[11].",".$lstPROD[12];
$dataACCUMULO = "".$lstACC[1].",".$lstACC[2].",".$lstACC[3].",".$lstACC[4].",".$lstACC[5].",".$lstACC[6].",".$lstACC[7].",".$lstACC[8].",".$lstACC[9].",".$lstACC[10].",".$lstACC[11].",".$lstACC[12];
$dataCONSUMO = "".$lstCONS[1].",".$lstCONS[2].",".$lstCONS[3].",".$lstCONS[4].",".$lstCONS[5].",".$lstCONS[6].",".$lstCONS[7].",".$lstCONS[8].",".$lstCONS[9].",".$lstCONS[10].",".$lstCONS[11].",".$lstCONS[12];
$dataIMPORTATO = "".$lstIMP[1].",".$lstIMP[2].",".$lstIMP[3].",".$lstIMP[4].",".$lstIMP[5].",".$lstIMP[6].",".$lstIMP[7].",".$lstIMP[8].",".$lstIMP[9].",".$lstIMP[10].",".$lstIMP[11].",".$lstIMP[12];
$dataMESI = "'".$lstMONTH[1]."','".$lstMONTH[2]."','".$lstMONTH[3]."','".$lstMONTH[4]."','".$lstMONTH[5]."','".$lstMONTH[6]."','".$lstMONTH[7]."','".$lstMONTH[8]."','".$lstMONTH[9]."','".$lstMONTH[10]."','".$lstMONTH[11]."','".$lstMONTH[12]."'";
?>

<!-- ADDITIONAL JAVA CODE GOES HERE -->
<script>
   var data_produzione = [<?php echo $dataPRODOTTO; ?>];
   var data_accumulo = [<?php echo $dataACCUMULO; ?>];
   var data_consumo = [<?php echo $dataCONSUMO; ?>];
   var data_importato = [<?php echo $dataIMPORTATO; ?>];
   var list_month = [<?php echo $dataMESI; ?>];
   var sorgente_solare = [<?php echo $sorgenteSOLARE; ?>];
   var sorgente_solbatt = [<?php echo $sorgenteSOLBATT; ?>];
   var sorgente_batterie = [<?php echo $sorgenteBATTERIE; ?>];
   var sorgente_rete = [<?php echo $sorgenteRETE; ?>];
</script>
    
<script>
$(document).ready(function () {
   // Define the YEARLY-PRODUZIONE graph object and its properties
   $('#yearly-produzione').highcharts({
      chart: {type: 'column'},
      title: {text: ''},
      colors: ['#5CB85C', '#337AB7'],
      xAxis: { categories:list_month, labels: { formatter: function () { return this.value.substring(0,3); } } },
      yAxis: {min: 0, title: {text: 'Chilowatt/Ora'}},
      credits: { enabled: false },
      tooltip: {
         headerFormat: '<span style="font-size:12px"><b>{point.key}</b></span><table>',
         pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td><td style="padding:0;text-align:right;"><b>{point.y:.0f} KWh</b></td></tr>',
         footerFormat: '</table>',
         shared: true,
         useHTML: true
      },
      plotOptions: { series: { marker: { enabled: false } }, column: {grouping: false, shadow: false, borderWidth: 0} },
      series: [{name: 'Energia Prodotta', data: data_produzione},{name: 'Energia Accumulata', data: data_accumulo}]
   });
   // Define the YEARLY-CONSUMO graph object and its properties
   $('#yearly-consumo').highcharts({
      chart: {type: 'column'},
      title: {text: ''},
      colors: ['#F0AD4E', '#D9534F'],
      xAxis: { categories:list_month, labels: { formatter: function () { return this.value.substring(0,3); } } },
      yAxis: {min: 0, title: {text: 'Chilowatt/Ora'}},
      credits: { enabled: false },
      tooltip: {
         headerFormat: '<span style="font-size:12px"><b>{point.key}</b></span><table>',
         pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td><td style="padding:0;text-align:right;"><b>{point.y:.0f} KWh</b></td></tr>',
         footerFormat: '</table>',
         shared: true,
         useHTML: true
      },
      plotOptions: { series: { marker: { enabled: false } }, column: {grouping: false, shadow: false, borderWidth: 0} },
      series: [{name: 'Potenza Consumata', data: data_consumo, pointPadding: 0, pointPlacement: 0},{name: 'Potenza Importata', data: data_importato, pointPadding: 0, pointPlacement: 0}]
   });
   // Define the WEEKLY-SORGENTI graph object and its properties
   $('#yearly-sorgenti').highcharts({
      chart: {type: 'column'},
      title: {text: ''},
      colors: ['#5CB85C', '#50BAD9', '#337AB7','#D94FD7'],
      xAxis: { categories:list_month, labels: { formatter: function () { return this.value.substring(0,3); } } },
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
