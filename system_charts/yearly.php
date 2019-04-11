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




<?php PAGE_Open("Sistema Annuale", ""); ?>


        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <span>Ingresso ENEL</span>
                    </div>
                    <div class="panel-body">
                        <div id="input-grid" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <span>Ingresso Fotovoltaico</span>
                    </div>
                    <div class="panel-body">
                        <div id="input-pv" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <span>Uscita abitazione</span>
                    </div>
                    <div class="panel-body">
                        <div id="outputs" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <span>Batterie</span>
                    </div>
                    <div class="panel-body">
                        <div id="batteries" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                    </div>
                </div>
            </div>
        </div>



<?php PAGE_Close("yearly_sytstemcharts"); ?>


<?php
// Open MySQL database connection
$sqlCONN = mysqli_connect($DB_Hostname,$DB_Username,$DB_Password, $DB_Schema);
if ($sqlCONN->connect_errno) {
  die("Could not connect to database: " . $sqlCONN->connect_error . "\n");
}
// Get INPUT-ENEL data graph
$lstINPUTGRIDvolt = array("-", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null");
$lstINPUTGRIDfreq = array("-", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null");
$lstINPUTPVvolt = array("-", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null");
$lstINPUTPVapms = array("-", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null");
$lstINPUTPVtemp = array("-", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null");
$lstOUTvolt = array("-", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null");
$lstOUTfreq = array("-", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null");
$lstOUTva = array("-", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null");
$lstOUTwatt = array("-", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null");
$lstOUTload = array("-", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null");
$lstOUTbus = array("-", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null");
$lstBATTcap = array("-", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null");
$lstBATTchargA = array("-", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null");
$lstBATTdiscA = array("-", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null");
$lstBATTvolt = array("-", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null");
$lstBATTsccvolt = array("-", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null");
$lstBATTtemp = array("-", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null", "null");
$lstMONTH = array("-", "", "", "", "", "", "", "", "", "", "", "", "");
$idxMONTH = 1;
$sqlCMD = "SELECT left(timestamp,6) as 'timestamp',avg(grid_voltage) as 'grid_voltage',avg(grid_frequency) as 'grid_frequency',max(pv_voltage) as 'pv_voltage',max(pv_current) as 'pv_current',avg(ac_output_voltage) as 'ac_output_voltage',avg(ac_output_frequency) as 'ac_output_frequency',max(ac_output_apparent_power) as 'ac_output_apparent_power',max(ac_output_active_power) as 'ac_output_active_power',max(output_load_percent) as 'output_load_percent',avg(bus_voltage) as 'bus_voltage',avg(battery_capacity) as 'battery_capacity',max(battery_charging_current) as 'battery_charging_current',max(battery_discharge_current) as 'battery_discharge_current',avg(battery_voltage) as 'battery_voltage',avg(battery_voltage_scc) as 'battery_voltage_scc',max(heatsink_temperature) as 'heatsink_temperature',max(diode_junction_temp) as 'diode_junction_temp' FROM inverter_data WHERE timestamp > date_format(now() - INTERVAL 11 MONTH,'%Y%m000000') GROUP BY 1 ORDER BY 1 ASC;";
$sqlRESULT = $sqlCONN->query($sqlCMD);
while ($sqlROW = $sqlRESULT->fetch_assoc()) {
   // Get query data
   $rawDATETIME = $sqlROW["timestamp"]."00";
   $rawINPGRIDvolt = round($sqlROW["grid_voltage"], 1);
   $rawINTGRIDfreq = round($sqlROW["grid_frequency"], 1);
   $rawINPPVvolt = round($sqlROW["pv_voltage"], 1);
   $rawINPPVamps = round($sqlROW["pv_current"], 0);
   $rawINPPVtemp = round($sqlROW["diode_junction_temp"], 1);
   $rawOUTvolt = round($sqlROW["ac_output_voltage"], 1);
   $rawOUTfreq = round($sqlROW["ac_output_frequency"], 1);
   $rawOUTva = round($sqlROW["ac_output_apparent_power"], 0);
   $rawOUTwatt = round($sqlROW["ac_output_active_power"], 0);
   $rawOUTload = round($sqlROW["output_load_percent"], 0);
   $rawOUTbus = round($sqlROW["bus_voltage"], 1);
   $rawBATTcap = round($sqlROW["battery_capacity"], 0);
   $rawBATTchargA = round($sqlROW["battery_charging_current"], 0);
   $rawBATTdiscA = round($sqlROW["battery_discharge_current"], 0);
   $rawBATTvolt = round($sqlROW["battery_voltage"], 1);
   $rawBATTsccvolt = round($sqlROW["battery_voltage_scc"], 1);
   $rawBATTtemp = round($sqlROW["heatsink_temperature"], 0);
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
   $lstINPUTGRIDvolt[$idxMONTH] = $rawINPGRIDvolt;
   $lstINPUTGRIDfreq[$idxMONTH] = $rawINTGRIDfreq;
   $lstINPUTPVvolt[$idxMONTH] = $rawINPPVvolt;
   $lstINPUTPVapms[$idxMONTH] = $rawINPPVamps;
   $lstINPUTPVtemp[$idxMONTH] = $rawINPPVtemp;
   $lstOUTvolt[$idxMONTH] = $rawOUTvolt;
   $lstOUTfreq[$idxMONTH] = $rawOUTfreq;
   $lstOUTva[$idxMONTH] = $rawOUTva;
   $lstOUTwatt[$idxMONTH] = $rawOUTwatt;
   $lstOUTload[$idxMONTH] = $rawOUTload;
   $lstOUTbus[$idxMONTH] = $rawOUTbus;
   $lstBATTcap[$idxMONTH] = $rawBATTcap;
   $lstBATTchargA[$idxMONTH] = $rawBATTchargA;
   $lstBATTdiscA[$idxMONTH] = $rawBATTdiscA;
   $lstBATTvolt[$idxMONTH] = $rawBATTvolt;
   $lstBATTsccvolt[$idxMONTH] = $rawBATTsccvolt;
   $lstBATTtemp[$idxMONTH] = $rawBATTtemp;
   // Increment month counter
   $idxMONTH += 1;
}
$sqlRESULT->close();
// Close MySQL connection
mysqli_close($sqlCONN);
?>

<!-- ADDITIONAL JAVA CODE GOES HERE -->
<script>
   var data_inputgrid_volt = [<?php echo "".$lstINPUTGRIDvolt[1].",".$lstINPUTGRIDvolt[2].",".$lstINPUTGRIDvolt[3].",".$lstINPUTGRIDvolt[4].",".$lstINPUTGRIDvolt[5].",".$lstINPUTGRIDvolt[6].",".$lstINPUTGRIDvolt[7].",".$lstINPUTGRIDvolt[8].",".$lstINPUTGRIDvolt[9].",".$lstINPUTGRIDvolt[10].",".$lstINPUTGRIDvolt[11].",".$lstINPUTGRIDvolt[12]; ?>];
   var data_inputgrid_freq = [<?php echo "".$lstINPUTGRIDfreq[1].",".$lstINPUTGRIDfreq[2].",".$lstINPUTGRIDfreq[3].",".$lstINPUTGRIDfreq[4].",".$lstINPUTGRIDfreq[5].",".$lstINPUTGRIDfreq[6].",".$lstINPUTGRIDfreq[7].",".$lstINPUTGRIDfreq[8].",".$lstINPUTGRIDfreq[9].",".$lstINPUTGRIDfreq[10].",".$lstINPUTGRIDfreq[11].",".$lstINPUTGRIDfreq[12]; ?>];
   var data_inputpv_volt = [<?php echo "".$lstINPUTPVvolt[1].",".$lstINPUTPVvolt[2].",".$lstINPUTPVvolt[3].",".$lstINPUTPVvolt[4].",".$lstINPUTPVvolt[5].",".$lstINPUTPVvolt[6].",".$lstINPUTPVvolt[7].",".$lstINPUTPVvolt[8].",".$lstINPUTPVvolt[9].",".$lstINPUTPVvolt[10].",".$lstINPUTPVvolt[11].",".$lstINPUTPVvolt[12]; ?>];
   var data_inputpv_amps = [<?php echo "".$lstINPUTPVapms[1].",".$lstINPUTPVapms[2].",".$lstINPUTPVapms[3].",".$lstINPUTPVapms[4].",".$lstINPUTPVapms[5].",".$lstINPUTPVapms[6].",".$lstINPUTPVapms[7].",".$lstINPUTPVapms[8].",".$lstINPUTPVapms[9].",".$lstINPUTPVapms[10].",".$lstINPUTPVapms[11].",".$lstINPUTPVapms[12]; ?>];
   var data_inputpv_temp = [<?php echo "".$lstINPUTPVtemp[1].",".$lstINPUTPVtemp[2].",".$lstINPUTPVtemp[3].",".$lstINPUTPVtemp[4].",".$lstINPUTPVtemp[5].",".$lstINPUTPVtemp[6].",".$lstINPUTPVtemp[7].",".$lstINPUTPVtemp[8].",".$lstINPUTPVtemp[9].",".$lstINPUTPVtemp[10].",".$lstINPUTPVtemp[11].",".$lstINPUTPVtemp[12]; ?>];
   var data_output_volt = [<?php echo "".$lstOUTvolt[1].",".$lstOUTvolt[2].",".$lstOUTvolt[3].",".$lstOUTvolt[4].",".$lstOUTvolt[5].",".$lstOUTvolt[6].",".$lstOUTvolt[7].",".$lstOUTvolt[8].",".$lstOUTvolt[9].",".$lstOUTvolt[10].",".$lstOUTvolt[11].",".$lstOUTvolt[12]; ?>];
   var data_output_freq = [<?php echo "".$lstOUTfreq[1].",".$lstOUTfreq[2].",".$lstOUTfreq[3].",".$lstOUTfreq[4].",".$lstOUTfreq[5].",".$lstOUTfreq[6].",".$lstOUTfreq[7].",".$lstOUTfreq[8].",".$lstOUTfreq[9].",".$lstOUTfreq[10].",".$lstOUTfreq[11].",".$lstOUTfreq[12]; ?>];
   var data_output_va = [<?php echo "".$lstOUTva[1].",".$lstOUTva[2].",".$lstOUTva[3].",".$lstOUTva[4].",".$lstOUTva[5].",".$lstOUTva[6].",".$lstOUTva[7].",".$lstOUTva[8].",".$lstOUTva[9].",".$lstOUTva[10].",".$lstOUTva[11].",".$lstOUTva[12]; ?>];
   var data_output_watt = [<?php echo "".$lstOUTwatt[1].",".$lstOUTwatt[2].",".$lstOUTwatt[3].",".$lstOUTwatt[4].",".$lstOUTwatt[5].",".$lstOUTwatt[6].",".$lstOUTwatt[7].",".$lstOUTwatt[8].",".$lstOUTwatt[9].",".$lstOUTwatt[10].",".$lstOUTwatt[11].",".$lstOUTwatt[12]; ?>];
   var data_output_load = [<?php echo "".$lstOUTload[1].",".$lstOUTload[2].",".$lstOUTload[3].",".$lstOUTload[4].",".$lstOUTload[5].",".$lstOUTload[6].",".$lstOUTload[7].",".$lstOUTload[8].",".$lstOUTload[9].",".$lstOUTload[10].",".$lstOUTload[11].",".$lstOUTload[12]; ?>];
   var data_output_bus = [<?php echo "".$lstOUTbus[1].",".$lstOUTbus[2].",".$lstOUTbus[3].",".$lstOUTbus[4].",".$lstOUTbus[5].",".$lstOUTbus[6].",".$lstOUTbus[7].",".$lstOUTbus[8].",".$lstOUTbus[9].",".$lstOUTbus[10].",".$lstOUTbus[11].",".$lstOUTbus[12]; ?>];
   var data_battery_cap = [<?php echo "".$lstBATTcap[1].",".$lstBATTcap[2].",".$lstBATTcap[3].",".$lstBATTcap[4].",".$lstBATTcap[5].",".$lstBATTcap[6].",".$lstBATTcap[7].",".$lstBATTcap[8].",".$lstBATTcap[9].",".$lstBATTcap[10].",".$lstBATTcap[11].",".$lstBATTcap[12]; ?>];
   var data_battery_charg = [<?php echo "".$lstBATTchargA[1].",".$lstBATTchargA[2].",".$lstBATTchargA[3].",".$lstBATTchargA[4].",".$lstBATTchargA[5].",".$lstBATTchargA[6].",".$lstBATTchargA[7].",".$lstBATTchargA[8].",".$lstBATTchargA[9].",".$lstBATTchargA[10].",".$lstBATTchargA[11].",".$lstBATTchargA[12]; ?>];
   var data_battery_disc = [<?php echo "".$lstBATTdiscA[1].",".$lstBATTdiscA[2].",".$lstBATTdiscA[3].",".$lstBATTdiscA[4].",".$lstBATTdiscA[5].",".$lstBATTdiscA[6].",".$lstBATTdiscA[7].",".$lstBATTdiscA[8].",".$lstBATTdiscA[9].",".$lstBATTdiscA[10].",".$lstBATTdiscA[11].",".$lstBATTdiscA[12]; ?>];
   var data_battery_volt = [<?php echo "".$lstBATTvolt[1].",".$lstBATTvolt[2].",".$lstBATTvolt[3].",".$lstBATTvolt[4].",".$lstBATTvolt[5].",".$lstBATTvolt[6].",".$lstBATTvolt[7].",".$lstBATTvolt[8].",".$lstBATTvolt[9].",".$lstBATTvolt[10].",".$lstBATTvolt[11].",".$lstBATTvolt[12]; ?>];
   var data_battery_scc = [<?php echo "".$lstBATTsccvolt[1].",".$lstBATTsccvolt[2].",".$lstBATTsccvolt[3].",".$lstBATTsccvolt[4].",".$lstBATTsccvolt[5].",".$lstBATTsccvolt[6].",".$lstBATTsccvolt[7].",".$lstBATTsccvolt[8].",".$lstBATTsccvolt[9].",".$lstBATTsccvolt[10].",".$lstBATTsccvolt[11].",".$lstBATTsccvolt[12]; ?>];
   var data_battery_temp = [<?php echo "".$lstBATTtemp[1].",".$lstBATTtemp[2].",".$lstBATTtemp[3].",".$lstBATTtemp[4].",".$lstBATTtemp[5].",".$lstBATTtemp[6].",".$lstBATTtemp[7].",".$lstBATTtemp[8].",".$lstBATTtemp[9].",".$lstBATTtemp[10].",".$lstBATTtemp[11].",".$lstBATTtemp[12]; ?>];
   var list_month = [<?php echo "'".$lstMONTH[1]."','".$lstMONTH[2]."','".$lstMONTH[3]."','".$lstMONTH[4]."','".$lstMONTH[5]."','".$lstMONTH[6]."','".$lstMONTH[7]."','".$lstMONTH[8]."','".$lstMONTH[9]."','".$lstMONTH[10]."','".$lstMONTH[11]."','".$lstMONTH[12]."'"; ?>];
</script>
    
<script>
$(document).ready(function () {
   // Define the INGRESSO ENEL graph object and its properties
   $('#input-grid').highcharts({
      chart: {type: 'spline'},
      title: {text: ''},
      colors: ['#337AB7','#5CB85C','#F0AD4E', '#D9534F'],
      xAxis: { categories:list_month, labels: { formatter: function () { return this.value.substring(0,3); } } },
      yAxis: [
         {min: 0, title: {text: ''}, labels: {format: '{value}V', style: { color: '#337AB7'}}}, 
         {min: 0, title: {text: ''}, labels: {format: '{value}Hz', style: { color: '#5CB85C'}}, opposite: true}
      ],
      tooltip: {
         shared: true
      },
      plotOptions: { series: { marker: { enabled: false } } },
      credits: { enabled: false },
      series: [
      	{yAxis: 0, name: 'Tensione', data: data_inputgrid_volt, tooltip: { valueSuffix: 'V' }},
      	{yAxis: 1, name: 'Frequenza', data: data_inputgrid_freq, tooltip: { valueSuffix: 'Hz' }}
      ]
   });
});
$(document).ready(function () {
   // Define the INGRESSO FOTOVOLTAICO graph object and its properties
   $('#input-pv').highcharts({
      chart: {type: 'spline'},
      title: {text: ''},
      colors: ['#F0AD4E', '#D9534F', '#999999'],
      xAxis: { categories:list_month, labels: { formatter: function () { return this.value.substring(0,3); } } },
      yAxis: [
         {min: 0, title: {text: ''}, labels: {format: '{value}V', style: { color: '#F0AD4E'}}},
         {min: 0, title: {text: ''}, labels: {format: '{value}A', style: { color: '#D9534F'}}, opposite: true}
      ],
      tooltip: {
         shared: true
      },
      plotOptions: { series: { marker: { enabled: false } } },
      credits: { enabled: false },
      series: [
      	{yAxis: 0, name: 'Tensione', data: data_inputpv_volt, tooltip: { valueSuffix: 'V'}},
      	{yAxis: 1, name: 'Corrente', data: data_inputpv_amps, tooltip: { valueSuffix: 'A'}},
      	{yAxis: 0, name: 'Diodi Blocco', data: data_inputpv_temp, tooltip: { valueSuffix: '°C'}}
      ]
   });
});
$(document).ready(function () {
   // Define the INGRESSO ENEL graph object and its properties
   $('#outputs').highcharts({
      chart: {type: 'spline'},
      title: {text: ''},
      colors: ['#337AB7','#5CB85C','#F0AD4E', '#D9534F', '#000000', '#bbbbbb'],
      xAxis: { categories:list_month, labels: { formatter: function () { return this.value.substring(0,3); } } },
      yAxis: [
         {min: 0, title: {text: ''}, labels: {format: '{value}V', style: { color: '#337AB7'}}},
         {min: 0, title: {text: ''}, labels: {format: '{value}W', style: { color: '#D9534F'}}, opposite: true}
      ],
      tooltip: {
         shared: true
      },
      plotOptions: { series: { marker: { enabled: false } } },
      credits: { enabled: false },
      series: [
      	{yAxis: 0, name: 'Tensione', data: data_output_volt, tooltip: { valueSuffix: 'V'}},
      	{yAxis: 0, name: 'Frequenza', data: data_output_freq, tooltip: { valueSuffix: 'Hz'}},
      	{yAxis: 1, name: 'VoltAmpere', data: data_output_va, tooltip: { valueSuffix: 'VA'}},
      	{yAxis: 1, name: 'Watt', data: data_output_watt, tooltip: { valueSuffix: 'W'}},
      	{yAxis: 0, name: 'Load', data: data_output_load, tooltip: { valueSuffix: '%'}},
      	{yAxis: 1, name: 'Bus Voltage', data: data_output_bus, tooltip: { valueSuffix: 'V'}}
      ]
   });
});
$(document).ready(function () {
   // Define the INGRESSO ENEL graph object and its properties
   $('#batteries').highcharts({
      chart: {type: 'spline'},
      title: {text: ''},
      colors: ['#337AB7','#5CB85C','#F0AD4E', '#D9534F', '#000000', '#999999'],
      xAxis: { categories:list_month, labels: { formatter: function () { return this.value.substring(0,3); } } },
      yAxis: [
         {min: 0, title: {text: ''}, labels: {format: '{value}V', style: { color: '#337AB7'}}},
         {min: 0, title: {text: ''}, labels: {format: '{value}A', style: { color: '#5CB85C'}}, opposite: true}
      ],
      tooltip: {
         shared: true
      },
      plotOptions: { series: { marker: { enabled: false } } },
      credits: { enabled: false },
      series: [
      	{yAxis: 0, name: 'Capacità Batteria', data: data_battery_cap, tooltip: { valueSuffix: '%'}},
      	{yAxis: 1, name: 'Corrente di Carica', data: data_battery_charg, tooltip: { valueSuffix: 'A'}},
      	{yAxis: 1, name: 'Corrente di Scarica', data: data_battery_disc, tooltip: { valueSuffix: 'A'}},
      	{yAxis: 0, name: 'Voltaggio Batteria', data: data_battery_volt, tooltip: { valueSuffix: 'V'}},
      	{yAxis: 0, name: 'Voltaggio SCC', data: data_battery_scc, tooltip: { valueSuffix: 'V'}},
      	{yAxis: 0, name: 'Temperatura', data: data_battery_temp, tooltip: { valueSuffix: '°C'}}
      ]
   });
});
</script>
<!-- ADDITIONAL JAVA CODE GOES HERE -->



<!-- END PAGE CONTENTS AND CODE -->
</body>
</html>
