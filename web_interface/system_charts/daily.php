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




<?php PAGE_Open("Sistema Giornaliero", ""); ?>


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



<?php PAGE_Close("daily_sytstemcharts"); ?>


<?php
// Open MySQL database connection
$sqlCONN = mysqli_connect($DB_Hostname,$DB_Username,$DB_Password, $DB_Schema);
if ($sqlCONN->connect_errno) {
  die("Could not connect to database: " . $sqlCONN->connect_error . "\n");
}
// Get INPUT-ENEL data graph
$dataINPUTGRIDvolt = "";
$dataINPUTGRIDfreq = "";
$dataINPUTPVvolt = "";
$dataINPUTPVapms = "";
$dataINPUTPVtemp = "";
$dataOUTvolt = "";
$dataOUTfreq = "";
$dataOUTva = "";
$dataOUTwatt = "";
$dataOUTload = "";
$dataOUTbus = "";
$dataBATTcap = "";
$dataBATTchargA = "";
$dataBATTdiscA = "";
$dataBATTvolt = "";
$dataBATTsccvolt = "";
$dataBATTtemp = "";
$sqlCMD = "SELECT * FROM inverter_data WHERE timestamp >= date_format(now(),'%Y%m%d0000') ORDER BY timestamp ASC";
$sqlRESULT = $sqlCONN->query($sqlCMD);
while ($sqlROW = $sqlRESULT->fetch_assoc()) {
   // Get query data
   $rawDATETIME = $sqlROW["timestamp"];
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
   $rawDATETIME = substr($rawDATETIME, 0, 4)."-".substr($rawDATETIME, 4, 2)."-".substr($rawDATETIME, 6, 2)." ".substr($rawDATETIME, 8, 2).":".substr($rawDATETIME, 10, 2);
   // Store retrived data into JAVASCRIPT arrays
   if ($dataINPUTGRIDvolt != "") { $dataINPUTGRIDvolt = $dataINPUTGRIDvolt.", "; }
   if ($dataINPUTGRIDfreq != "") { $dataINPUTGRIDfreq = $dataINPUTGRIDfreq.", "; }
   if ($dataINPUTPVvolt != "") { $dataINPUTPVvolt = $dataINPUTPVvolt.", "; }
   if ($dataINPUTPVapms != "") { $dataINPUTPVapms = $dataINPUTPVapms.", "; }
   if ($dataINPUTPVtemp != "") { $dataINPUTPVtemp = $dataINPUTPVtemp.", "; }
   if ($dataOUTvolt != "") { $dataOUTvolt = $dataOUTvolt.", "; }
   if ($dataOUTfreq != "") { $dataOUTfreq = $dataOUTfreq.", "; }
   if ($dataOUTva != "") { $dataOUTva = $dataOUTva.", "; }
   if ($dataOUTwatt != "") { $dataOUTwatt = $dataOUTwatt.", "; }
   if ($dataOUTload != "") { $dataOUTload = $dataOUTload.", "; }
   if ($dataOUTbus != "") { $dataOUTbus = $dataOUTbus.", "; }
   if ($dataBATTcap != "") { $dataBATTcap = $dataBATTcap.", "; }
   if ($dataBATTchargA != "") { $dataBATTchargA = $dataBATTchargA.", "; }
   if ($dataBATTdiscA != "") { $dataBATTdiscA = $dataBATTdiscA.", "; }
   if ($dataBATTvolt != "") { $dataBATTvolt = $dataBATTvolt.", "; }
   if ($dataBATTsccvolt != "") { $dataBATTsccvolt = $dataBATTsccvolt.", "; }
   if ($dataBATTtemp != "") { $dataBATTtemp = $dataBATTtemp.", "; }
   $dataINPUTGRIDvolt = $dataINPUTGRIDvolt."[".dbDate2Java($rawDATETIME).", ".$rawINPGRIDvolt."]";
   $dataINPUTGRIDfreq = $dataINPUTGRIDfreq."[".dbDate2Java($rawDATETIME).", ".$rawINTGRIDfreq."]";
   $dataINPUTPVvolt = $dataINPUTPVvolt."[".dbDate2Java($rawDATETIME).", ".$rawINPPVvolt."]";
   $dataINPUTPVapms = $dataINPUTPVapms."[".dbDate2Java($rawDATETIME).", ".$rawINPPVamps."]";
   $dataINPUTPVtemp = $dataINPUTPVtemp."[".dbDate2Java($rawDATETIME).", ".$rawINPPVtemp."]";
   $dataOUTvolt = $dataOUTvolt."[".dbDate2Java($rawDATETIME).", ".$rawOUTvolt."]";
   $dataOUTfreq = $dataOUTfreq."[".dbDate2Java($rawDATETIME).", ".$rawOUTfreq."]";
   $dataOUTva = $dataOUTva."[".dbDate2Java($rawDATETIME).", ".$rawOUTva."]";
   $dataOUTwatt = $dataOUTwatt."[".dbDate2Java($rawDATETIME).", ".$rawOUTwatt."]";
   $dataOUTload = $dataOUTload."[".dbDate2Java($rawDATETIME).", ".$rawOUTload."]";
   $dataOUTbus = $dataOUTbus."[".dbDate2Java($rawDATETIME).", ".$rawOUTbus."]";
   $dataBATTcap = $dataBATTcap."[".dbDate2Java($rawDATETIME).", ".$rawBATTcap."]";
   $dataBATTchargA = $dataBATTchargA."[".dbDate2Java($rawDATETIME).", ".$rawBATTchargA."]";
   $dataBATTdiscA = $dataBATTdiscA."[".dbDate2Java($rawDATETIME).", ".$rawBATTdiscA."]";
   $dataBATTvolt = $dataBATTvolt."[".dbDate2Java($rawDATETIME).", ".$rawBATTvolt."]";
   $dataBATTsccvolt = $dataBATTsccvolt."[".dbDate2Java($rawDATETIME).", ".$rawBATTsccvolt."]";
   $dataBATTtemp = $dataBATTtemp."[".dbDate2Java($rawDATETIME).", ".$rawBATTtemp."]";
}
$sqlRESULT->close();
// Close MySQL connection
mysqli_close($sqlCONN);
?>

<!-- ADDITIONAL JAVA CODE GOES HERE -->
<script>
   var data_inputgrid_volt = [<?php echo $dataINPUTGRIDvolt; ?>];
   var data_inputgrid_freq = [<?php echo $dataINPUTGRIDfreq; ?>];
   var data_inputpv_volt = [<?php echo $dataINPUTPVvolt; ?>];
   var data_inputpv_amps = [<?php echo $dataINPUTPVapms; ?>];
   var data_inputpv_temp = [<?php echo $dataINPUTPVtemp; ?>];
   var data_output_volt = [<?php echo $dataOUTvolt; ?>];
   var data_output_freq = [<?php echo $dataOUTfreq; ?>];
   var data_output_va = [<?php echo $dataOUTva; ?>];
   var data_output_watt = [<?php echo $dataOUTwatt; ?>];
   var data_output_load = [<?php echo $dataOUTload; ?>];
   var data_output_bus = [<?php echo $dataOUTbus; ?>];
   var data_battery_cap = [<?php echo $dataBATTcap; ?>];
   var data_battery_charg = [<?php echo $dataBATTchargA; ?>];
   var data_battery_disc = [<?php echo $dataBATTdiscA; ?>];
   var data_battery_volt = [<?php echo $dataBATTvolt; ?>];
   var data_battery_scc = [<?php echo $dataBATTsccvolt; ?>];
   var data_battery_temp = [<?php echo $dataBATTtemp; ?>];
</script>
    
<script>
$(document).ready(function () {
   // Define the INGRESSO ENEL graph object and its properties
   $('#input-grid').highcharts({
      chart: {type: 'spline'},
      title: {text: ''},
      colors: ['#337AB7','#5CB85C','#F0AD4E', '#D9534F'],
      xAxis: {type: 'datetime'},
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
      xAxis: {type: 'datetime'},
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
      xAxis: {type: 'datetime'},
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
      colors: ['#337AB7','#5CB85C','#F0AD4E', '#D9534F', '#000000', '#777777'],
      xAxis: {type: 'datetime'},
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
//-------------------------------------------------------------------------------
</script>
<!-- ADDITIONAL JAVA CODE GOES HERE -->




<!-- END PAGE CONTENTS AND CODE -->
</body>
</html>
