<!DOCTYPE html>
<head>
<?php
// Include needed external modules
$webROOT = realpath($_SERVER["DOCUMENT_ROOT"]);
require("$webROOT/_library/_config.php");
require("$webROOT/_library/_masterPage.php");
require("$webROOT/_library/_functions.php");

// Open MySQL database connection
$sqlCONN = mysqli_connect($DB_Hostname,$DB_Username,$DB_Password, $DB_Schema);
if (!$sqlCONN) {
  die("Could not connect to database: " . mysql_error() . "\n");
}

// Convert arrays into JavaScript array format for data GRAPH
$graphCURRENT = "";
$graphPASTminmax = "";
$graphPASTavg = "";
$graphPREDICTION = "";
for ($i = 1; $i<= 12; $i++) {
   if ($i > 1) { 
      $graphCURRENT = $graphCURRENT . ", ";
      $graphPASTminmax = $graphPASTminmax . ", ";
      $graphPASTavg = $graphPASTavg . ", ";
      $graphPREDICTION = $graphPREDICTION . ", ";
   }
   $graphCURRENT = $graphCURRENT . $productionCURRENT[$i];
   $graphPASTminmax = $graphPASTminmax . "[" . $productionPASTmin[$i] . "," . $productionPASTmax[$i] . "]";
   $graphPASTavg = $graphPASTavg . $productionPASTavg[$i];
   $graphPREDICTION = $graphPREDICTION . $productionPREDICTION[$i];
}

// Close the database connection
mysqli_close($sqlCONN);

?>
<!-- BEGIN PAGE CONTENTS AND CODE -->



<?php PAGE_Open("Dashboard", "myInit()"); ?>

<div class="row">
<?php PAGE_Panel("fa fa-sun-o","JSON_productionInstant","Produzione fotovoltaica attuale","panel-darkgreen","/energy_charts/daily.php","Picco giornaliero","JSON_productionPeak"); ?>
<?php PAGE_Panel("fa fa-battery-full","JSON_storageToday","Accumulo odierno","panel-green","/energy_charts/monthly.php","Accumulo mensile","JSON_storageMonth"); ?>
</div>
<div class="row">
<?php PAGE_Panel("fa fa-flash","JSON_consumedToday","Consumo odierno","panel-yellow","/energy_charts/monthly.php","Consumo mensile","JSON_consumedMonth"); ?>
<?php PAGE_Panel("fa fa-download","JSON_importToday","Importazione odierna","panel-red","/energy_charts/monthly.php","Importazione mensile","JSON_importMonth"); ?>
</div>
<div class="row">
<?php PAGE_Panel("fa fa-money","JSON_savingsToday","Attuale risparmio giornaliero","panel-brown","/savings.php","Risparmio mensile","JSON_savingsMonth"); ?>
<?php PAGE_Panel("fa fa-envira","JSON_co2Day","Emissioni CO<small><sub>2</sub></small> odierne","panel-gray","#","Emissioni CO<small><sub>2</sub></small> mensili","JSON_co2Month"); ?>
</div>
<div class="row">
<?php PAGE_Panel("fa fa-thermometer","JSON_weatherTEMP","Temperatura attuale","","/weather.php","Condizioni metereologiche","JSON_weatherICON"); ?>
<?php PAGE_Panel("fa fa-clock-o","JSON_systemAGE","Età del sistema","panel-blue","#","Ultimo aggiornamento","JSON_timeStamp"); ?>
</div>

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading"><i class="fa fa-bar-chart-o fa-fw"></i> Panoramica Mensile</div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div id="monthly-overview-chart"></div>
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->

            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading" ID="JSON_inverterStatus">Stato Inverter: n/a</div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <table class="table">
                            <tbody>
                            <tr style="background-color: #f0f0f0;">
                                <td colspan="2"><strong><i class="fa fa-sun-o fa-fw"></i> Stringhe</strong></td>
                                <td colspan="2"><strong><i class="fa fa-flash fa-fw"></i> Rete</strong></td>
                            </tr>
                            <tr>
                                <td>Tensione</td>
                                <td class="text-right" style="border-right:1px #e0e0e0 solid;" ID="JSON_pvVoltage">n/a</td>
                                <td>Tensione</td>
                                <td class="text-right" ID="JSON_gridVoltage">n/a</td>
                            </tr>
                            <tr>
                                <td>Corrente</td>
                                <td class="text-right" style="border-right:1px #e0e0e0 solid;" ID="JSON_pvCurrent">n/a</td>
                                <td>Frequenza</td>
                                <td class="text-right" ID="JSON_gridFrequency">n/a</td>
                            </tr>
                            <tr>
                                <td>Diodi Blocco</td>
                                <td class="text-right" style="border-right:1px #e0e0e0 solid;" ID="JSON_diodeJunctionTemp">n/a</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
									 
                            <!-- Second section table rows -->
                            <tr style="background-color: #f0f0f0;">
                                <td colspan="2"><strong><i class="fa fa-battery-full fa-fw"></i> Batterie</strong></td>
                                <td colspan="2"><strong><i class="fa fa-home fa-fw"></i> Abitazione</strong></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border-right:1px #e0e0e0 solid;" ID="JSON_batteryStatus">n/a</td>
                                <td colspan="2" ID="JSON_inverterMode">n/a</td>
                            </tr>
                            <tr>
                                <td>Tensione</td>
                                <td class="text-right" style="border-right:1px #e0e0e0 solid;" ID="JSON_batteryVoltage">n/a</td>
                                <td>Tensione</td>
                                <td class="text-right" ID="JSON_outputVoltage">n/a</td>
                            </tr>
                            <tr>
                                <td>Tensione SCC</td>
                                <td class="text-right" style="border-right:1px #e0e0e0 solid;" ID="JSON_batteryVSCC">n/a</td>
                                <td>Frequenza</td>
                                <td class="text-right" ID="JSON_outputFrequency">n/a</td>
                            </tr>
                            <tr>
                                <td>Corrente di Carica</td>
                                <td class="text-right" style="border-right:1px #e0e0e0 solid;" ID="JSON_batteryChargeCurrent">n/a</td>
                                <td>Potenza Apparente</td>
                                <td class="text-right" ID="JSON_outputPowerVA">n/a</td>
                            </tr>
                            <tr>
                                <td>Corrente di Scarica</td>
                                <td class="text-right" style="border-right:1px #e0e0e0 solid;" ID="JSON_batteryDisachargeCurrent">n/a</td>
                                <td>Potenza Attiva</td>
                                <td class="text-right" ID="JSON_outputPowerW">n/a</td>
                            </tr>
                            <tr>
                                <td>Capacità</td>
                                <td class="text-right" style="border-right:1px #e0e0e0 solid;">
                                   <div class="progress"><div class="progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:0%;" ID="JSON_batteryCapacity">n/a</div></div>
                                </td>
                                <td>Carico inverter</td>
                                
                                <td class="text-right">
                                   <div class="progress"><div class="progress-bar<?php echo "".$INVERTER_LoadColor; ?>" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:%;" ID="JSON_inverterLoad">n/a</div></div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
            <!-- /.col-lg-3 -->
        </div>
        <!-- /.row -->
    </div>

<?php PAGE_Close("dashboard"); ?>

<!-- ADDITIONAL JAVA CODE GOES HERE -->
<script>
/*--------------------------------------------------------------------------------------------------------------*/	
var gco2_kwh = 433.2;
/*--------------------------------------------------------------------------------------------------------------*/	
var average_years_series = ['null','null','null','null','null','null','null','null','null','null','null','null'];
var min_max_years_series = [['null','null'],['null','null'],['null','null'],['null','null'],['null','null'],['null','null'],['null','null'],['null','null'],['null','null'],['null','null'],['null','null'],['null','null']];
var current_year_series = ['null','null','null','null','null','null','null','null','null','null','null','null'];
var current_month_pred = ['null','null','null','null','null','null','null','null','null','null','null','null'];
/*--------------------------------------------------------------------------------------------------------------*/	
$(document).ready(function () {
	// Configure AJAX to not use cached data
	$.ajaxSetup({ cache: false });
   // Initialize the HighCharts object chart
});
/*--------------------------------------------------------------------------------------------------------------*/	
function UpdatePage(){
	pageTitleStatus.innerHTML = "<img src='/_images/ajax-loader-small.gif'>&nbsp;Lettura dati...";
   var statusCode = 0;
   var statusText = "";
	$.getJSON(
		'/api_solar.php?type=data&reqid=' + (new Date()).getTime(),
		function(data){
			// Update STATUS BAR indicator
         statusCode = data.statusCode;
         statusText = "" + data.statusText;
         JSON_timeStamp.innerHTML = "" + data.timeStamp;
         JSON_weatherTEMP.innerHTML = "" + data.weatherTEMP + " °C";
         JSON_weatherICON.innerHTML = "<i class='wi " + data.weatherICON + " fa-2x' title='" + data.weatherTEXT + "'></i>";
         JSON_systemAGE.innerHTML = "" + data.systemAGE;
         JSON_pvVoltage.innerHTML = "" + data.pvVoltage + " V";
         JSON_pvCurrent.innerHTML = "" + data.pvCurrent + " A";
         JSON_productionInstant.innerHTML = "" + data.productionInstant + " Watt";
         JSON_productionPeak.innerHTML = "" + data.productionPeak + " Watt";
         JSON_storageToday.innerHTML = data.storageToday + " kWh";
         JSON_storageMonth.innerHTML = data.storageMonth + " kWh";
         JSON_consumedToday.innerHTML = data.consumedToday + " kWh";
         JSON_consumedMonth.innerHTML = data.consumedMonth + " kWh";
         JSON_importToday.innerHTML = data.importToday + " kWh";
         JSON_importMonth.innerHTML = data.importMonth + " kWh";
         JSON_savingsToday.innerHTML = data.savingsToday + " €";
         JSON_savingsMonth.innerHTML = data.savingsMonth + " €";
         JSON_gridVoltage.innerHTML = "" + data.gridVoltage + " V";
         JSON_gridFrequency.innerHTML = "" + data.gridFrequency + " Hz";
         JSON_batteryStatus.innerHTML = data.batteryStatus;
         JSON_batteryVoltage.innerHTML = data.batteryVoltage + " V";
         JSON_batteryVSCC.innerHTML = data.batteryVSCC + " V";
         JSON_batteryChargeCurrent.innerHTML = data.batteryChargeCurrent + " A";
         JSON_batteryDisachargeCurrent.innerHTML = data.batteryDisachargeCurrent + " A";
         JSON_batteryCapacity.innerHTML = data.batteryCapacity + "%";
         JSON_batteryCapacity.style.width = data.batteryCapacity + "%";
			JSON_diodeJunctionTemp.innerHTML = data.diodeJunctionTemp + " °C";
         switch (data.inverterSTATUS) {
            case "W":
               JSON_inverterStatus.innerHTML = "Stato Inverter: <img src='/_images/ICON_StatusWarning.png' TITLE='Allarme'>&nbsp;Allarme";
               break;
            case "F":
               JSON_inverterStatus.innerHTML = "Stato Inverter: <img src='/_images/ICON_StatusFault.png' TITLE='Guasto'>&nbsp;Guasto";
               break;
            default:   
               JSON_inverterStatus.innerHTML = "Stato Inverter: <img src='/_images/ICON_StatusNormal.png' TITLE='Normale'>&nbsp;Normale";
         }
         JSON_inverterMode.innerHTML = "" + data.inverterMode;
         JSON_inverterLoad.innerHTML = "" + data.inverterLoad + "%";
         JSON_inverterLoad.style.width = data.inverterLoad + "%";
         var tmpClass = "progress-bar-blue";
         if ( data.inverterLoad > 25 ) { tmpClass = "progress-bar-green"; }
         if ( data.inverterLoad > 75 ) { tmpClass = "progress-bar-yellow"; }
         if ( data.inverterLoad > 90 ) { tmpClass = "progress-bar-red"; }
         JSON_inverterLoad.className = tmpClass;
         JSON_outputVoltage.innerHTML = data.outputVoltage + " V";
         JSON_outputFrequency.innerHTML = data.outputFrequency + " Hz";
         JSON_outputPowerVA.innerHTML = data.outputPowerVA + " VA";
         JSON_outputPowerW.innerHTML = data.outputPowerW + " W";
         // Calculate and display the CO2 emissions
         JSON_co2Day.innerHTML = Number(Math.round(((data.importToday * gco2_kwh) / 1000)+"e2")+"e-2") + " Kg";
         JSON_co2Month.innerHTML = Number(Math.round(((data.importMonth * gco2_kwh) / 1000)+"e2")+"e-2") + " Kg";
	   }
	);
   // Empty status area, if no errors found
   if ( statusCode == 0 ) { 
      pageTitleStatus.innerHTML = "";
   } else {
      pageTitleStatus.innerHTML = statusText;
   }
}	
/*--------------------------------------------------------------------------------------------------------------*/	
function UpdateGraph(){
	pageTitleStatus.innerHTML = "<img src='/_images/ajax-loader-small.gif'>&nbsp;Lettura grafico...";
   var statusCode = 0;
   var statusText = "";
	$.getJSON(
      '/api_solar.php?type=graph&reqid=' + (new Date()).getTime(),
		function(data){
			// Update STATU BAR indicator
         statusCode = data.statusCode;
			statusText = "" + data.statusText;
         // Reset the data array containers
         lst_average_years_series = "";
         lst_min_max_years_series = "";
         lst_current_year_series = "";
         lst_current_month_pred = "";
         // Cycle over the response data array
         for (var i=0; i<data.graph.length; i++) {
            // Store each month datas
             if (i != 0) { 
                lst_average_years_series += ",";
                lst_min_max_years_series += ",";
                lst_current_year_series += ",";
                lst_current_month_pred += ",";
         	 }
            lst_average_years_series += data.graph[i].avgYears;
            lst_min_max_years_series += "[" + data.graph[i].minYears + "," + data.graph[i].maxYears + "]";
            lst_current_year_series += data.graph[i].curYears;
            lst_current_month_pred += data.graph[i].predMonth;
         }
         average_years_series = eval("["+lst_average_years_series+"]");
         min_max_years_series = eval("["+lst_min_max_years_series+"]");
         current_year_series = eval("["+lst_current_year_series+"]");
         current_month_pred = eval("["+lst_current_month_pred+"]");
         // Redraw the graph with loaded data
         $('#monthly-overview-chart').highcharts({
              chart: {},
              title: { text: 'Produzione Energia del ' + new Date().getFullYear() },
              colors: ['#B4C7DA', '#428BCA', '#153E7E', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'],
              xAxis: { 
                  categories: ['Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre'], 
                  labels: { formatter: function () { return this.value.substring(0,3); } }  
              },
              yAxis: [ {
                  labels: { format: '{value} kWh', style: { color: Highcharts.getOptions().colors[1] } },
                  title: { text: '', style: { color: Highcharts.getOptions().colors[1] } }
              } ],
              tooltip: {shared: true, headerFormat: '<b>{point.x} '+ new Date().getFullYear() + '</b><br>', pointFormat: '{series.name}: {point.y} <br>'},
              plotOptions: { column: {grouping: false, shadow: false, borderWidth: 0} },
              credits: { enabled: false },
              series: [
                  {
                      name: 'Previsione',
                      type: 'column',
                      data: current_month_pred,
                      tooltip: {valueSuffix: ' kWh', formatter: function () {if (this.y != null) {return this.y;} else return false;} }
                  },
                  {
                      name: 'Produzione',
                      type: 'column',
                      yAxis: 0,
                      data: current_year_series,
                      tooltip: {valueSuffix: ' kWh', formatter: function () {if (this.y != null) {return this.y;} else return false;} }
                  },
                  {
                      name: 'Media anni passati',
                      type: 'line',
                      yAxis: 0,
                      data: average_years_series,
                      tooltip: {valueSuffix: ' kWh', formatter: function () {if (this.y != null) {return this.y;} else return false;} }
                  },
                  {
                      name: 'Min/Max anni passati',
                      type: 'errorbar',
                      data: min_max_years_series,
                      tooltip: {pointFormat: '(Intervallo anni precedenti: {point.low}-{point.high} kWh)<br/>'}
                  }
              ]
          });

         }
	);
   // Empty status area, if no errors found
   if ( statusCode == 0 ) { 
      pageTitleStatus.innerHTML = "";
   } else {
      pageTitleStatus.innerHTML = statusText;
   }
}	
/*--------------------------------------------------------------------------------------------------------------*/	
function myInit() {
	// Execute the update of Data displayed on the page
	UpdatePage();
   // Execute the update of Graph displayed on the page after 2 second
   UpdateGraph();
	// Schedule automatic page Data refresh every 30 seconds
	setInterval(function(){UpdatePage()},30000);
	// Schedule automatic page Graph refresh every 10 minutes
	setInterval(function(){UpdateGraph()},600000);
}
/*--------------------------------------------------------------------------------------------------------------*/	
/*--------------------------------------------------------------------------------------------------------------*/	
/*--------------------------------------------------------------------------------------------------------------*/	
/*--------------------------------------------------------------------------------------------------------------*/	
/*--------------------------------------------------------------------------------------------------------------*/	
</script>


<!-- END PAGE CONTENTS AND CODE -->
</body>
</html>
