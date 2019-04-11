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
if ($sqlCONN->connect_errno) {
  die("Could not connect to database: " . $sqlCONN->connect_error . "\n");
}
// Query database events table for errors
$sqlCMD = "SELECT * FROM vw_inverter_events ORDER BY timestamp_start DESC;";
$sqlRESULT = $sqlCONN->query($sqlCMD);
$responseTABLE = "";
while ($sqlROW = $sqlRESULT->fetch_assoc()) {
   // Get query data
   $datetimeSTART = $sqlROW["timestamp_start"];
   $datetimeEND = $sqlROW["timestamp_end"];
   $currBITMASK = $sqlROW["status_bitmask"];
   $eventDESCRIPTION = "";
   $eventFAULT = "0";
   $eventFAULT = $sqlROW["fault_InverterFault"];
   // Check the type of alert
   if ($sqlROW["fault_BusOver"] == "1") { $eventDESCRIPTION = $eventDESCRIPTION."<li>Bus overvoltage</li>"; }
   if ($sqlROW["fault_BusUnder"] == "1") { $eventDESCRIPTION = $eventDESCRIPTION."<li>Bus undervoltage</li>"; }
   if ($sqlROW["fault_BusSoftFail"] == "1") { $eventDESCRIPTION = $eventDESCRIPTION."<li>Bus soft failure</li>"; }
   if ($sqlROW["warning_LineFail"] == "1") { $eventDESCRIPTION = $eventDESCRIPTION."<li>Line failure</li>"; }
   if ($sqlROW["warning_OPVShort"] == "1") { $eventDESCRIPTION = $eventDESCRIPTION."<li>OPV Short</li>"; }
   if ($sqlROW["fault_InverterVoltageTooLow"] == "1") { $eventDESCRIPTION = $eventDESCRIPTION."<li>Inverter voltage too low</li>"; }
   if ($sqlROW["fault_InverterVoltageTooHigh"] == "1") { $eventDESCRIPTION = $eventDESCRIPTION."<li>Inverter voltage too high</li>"; }
   if ($sqlROW["faultwarning_OverTemperature"] == "1") { $eventDESCRIPTION = $eventDESCRIPTION."<li>Over temperature</li>"; }
   if ($sqlROW["faultwarning_FanLocked"] == "1") { $eventDESCRIPTION = $eventDESCRIPTION."<li>Fan locked</li>"; }
   if ($sqlROW["faultwarning_BatteryVoltageHigh"] == "1") { $eventDESCRIPTION = $eventDESCRIPTION."<li>Battery voltage high</li>"; }
   if ($sqlROW["warning_BatteryLowAlarm"] == "1") { $eventDESCRIPTION = $eventDESCRIPTION."<li>Battery low alarm</li>"; }
   if ($sqlROW["warning_BatteryUnderShutdown"] == "1") { $eventDESCRIPTION = $eventDESCRIPTION."<li>Battery under shutdown</li>"; }
   if ($sqlROW["faultwarning_OverLoad"] == "1") { $eventDESCRIPTION = $eventDESCRIPTION."<li>Overload</li>"; }
   if ($sqlROW["warning_EepromFault"] == "1") { $eventDESCRIPTION = $eventDESCRIPTION."<li>EEprom fault</li>"; }
   if ($sqlROW["fault_InverterOverCurrent"] == "1") { $eventDESCRIPTION = $eventDESCRIPTION."<li>Inverter over current</li>"; }
   if ($sqlROW["fault_InverterSoftFail"] == "1") { $eventDESCRIPTION = $eventDESCRIPTION."<li>Inverter soft fail</li>"; }
   if ($sqlROW["fault_SelfTestFail"] == "1") { $eventDESCRIPTION = $eventDESCRIPTION."<li>Self test failure</li>"; }
   if ($sqlROW["fault_OPDCVoltageOver"] == "1") { $eventDESCRIPTION = $eventDESCRIPTION."<li>OP DC voltage over</li>"; }
   if ($sqlROW["fault_BatOpen"] == "1") { $eventDESCRIPTION = $eventDESCRIPTION."<li>Battery disconnected</li>"; }
   if ($sqlROW["fault_CurrentSensorFail"] == "1") { $eventDESCRIPTION = $eventDESCRIPTION."<li>Current sensor fault</li>"; }
   if ($sqlROW["fault_BatteryShort"] == "1") { $eventDESCRIPTION = $eventDESCRIPTION."<li>Battery shortcircuit</li>"; }
   if ($sqlROW["warning_PowerLimit"] == "1") { $eventDESCRIPTION = $eventDESCRIPTION."<li>Power limit</li>"; }
   if ($sqlROW["warning_PVVoltageHigh"] == "1") { $eventDESCRIPTION = $eventDESCRIPTION."<li>PV voltage high</li>"; }
   if ($sqlROW["warning_MPPTOverloadFault"] == "1") { $eventDESCRIPTION = $eventDESCRIPTION."<li>MPPT overload fault</li>"; }
   if ($sqlROW["warning_MPPTOverloadWarning"] == "1") { $eventDESCRIPTION = $eventDESCRIPTION."<li>MPPT overload warning</li>"; }
   if ($sqlROW["warning_BatteryTooLowToCharge"] == "1") { $eventDESCRIPTION = $eventDESCRIPTION."<li>Battery too low to charge</li>"; }
   // Insert the data into response table
   $responseTABLE = $responseTABLE."                     <tr>";
   $responseTABLE = $responseTABLE."                        <td class='text-left'>".eventENTRY($eventFAULT,$eventDESCRIPTION)."</td>";
   $responseTABLE = $responseTABLE."                        <td class='text-center'>".printDBdate($datetimeSTART)."</td>";
   $responseTABLE = $responseTABLE."                        <td class='text-center'>".printDBdate($datetimeEND)."</td>";
   $responseTABLE = $responseTABLE."                        <td class='text-center'>".printDURATION($datetimeSTART, $datetimeEND)."</td>";
   $responseTABLE = $responseTABLE."                     </tr>";
}
$sqlRESULT->close();
// Check if there are data to display or not
if ($responseTABLE == "") { $responseTABLE = "<tr><td colspan='4'>Nessun evento da visualizzare.</td></tr>"; }
// Close MySQL connection
mysqli_close($sqlCONN);
//--------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------
function eventENTRY($flgLEVEL,$stsLEVEL) {
   $res = "<img src='/_images/ICON_StatusWarning.png' valign='absmiddle'";
   if ($flgLEVEL == "1") { $res = "<img src='/_images/ICON_StatusFault.png' valign='absmiddle'>"; }
   return $res.$stsLEVEL;
}
//--------------------------------------------------------------------------------------------------------------------------
function printDURATION($dateSTART, $dateEND) {
   $res = "";
   if ($dateSTART == "" || $dateEND == "") {
      return "<p title='Durata: n/a'>n/a</p>";
   } else{
      $tmSTART = DateTime::createFromFormat('YndHis', $dateSTART."00");
      $tmEND = DateTime::createFromFormat('YndHis', $dateEND."00");
      $tmINTERVAL = $tmSTART->diff($tmEND, true);
      $vlDD = $tmINTERVAL->format("%a");
      $vlHH = $tmINTERVAL->format("%H");
      $vlMM = $tmINTERVAL->format("%I");
      $stTIMING = "".$vmMM."m";
      $stTIMINGLONG = "".$vmMM." minuti.";
      return "<p title='Durata: ".$tmINTERVAL->format('%a giorni, %h ore, %i minuti')."'>".$tmINTERVAL->format('%ag %Hh:%Im')."</p>";
   }
}
//--------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------
?>
<!-- BEGIN PAGE CONTENTS AND CODE -->




<?php PAGE_Open("Events", ""); ?>

<div class="row">
   <div class="col-lg-12">
      <div class="panel panel-default">
         <div class="panel-heading">
            <span>Ultimi eventi</span>
         </div>
         <div class="panel-body">
            <!-- /.table-responsive -->
            <div class="table-responsive">
               <table class="table table-striped">
                  <thead>
                     <tr>
                        <th class="text-center">Condizione</th>
                        <th class="text-center" style="width:170px;">Ora inizio</th>
                        <th class="text-center" style="width:170px;">Ora fine</th>
                        <th class="text-center" style="width:130px;">Durata</th>
                     </tr>
                  </thead>
                  <tbody>
<?php echo "".$responseTABLE; ?>                  
                  </tbody>
               </table>
            </div>
            <!-- /.table-responsive -->
         </div>
      </div>
   </div>
</div>

<?php PAGE_Close("events"); ?>


<!-- ADDITIONAL JAVA CODE GOES HERE -->
<!-- ADDITIONAL JAVA CODE GOES HERE -->



<!-- END PAGE CONTENTS AND CODE -->
</body>
</html>
