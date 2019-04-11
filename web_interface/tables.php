<!DOCTYPE html>
<head>
<?php
// Include needed external modules
$webROOT = realpath($_SERVER["DOCUMENT_ROOT"]);
require("$webROOT/_library/_config.php");
require("$webROOT/_library/_masterPage.php");
require("$webROOT/_library/_functions.php");

// Get last 30 days statistics
$sqlCONN = mysqli_connect($DB_Hostname, $DB_Username, $DB_Password, $DB_Schema);
if ($sqlCONN->connect_errno) {
   die("Could not connect to database: " . $sqlCONN->connect_error . "\n");
}
$sqlCMD = "SELECT timestamp,prod_pv_wh+prod_batt_wh AS prod_wh,acc_wh,consumo_wh,import_wh FROM vw_inverter_power_daily ORDER BY timestamp DESC LIMIT 30;";
//$sqlCMD = "SELECT LEFT(timestamp,8) AS 'timestamp',CAST(SUM(pv_current*pv_voltage)*0.01667 AS DECIMAL (9, 2)) AS 'prod_wh',CAST(SUM(battery_charging_current*battery_voltage)*0.01667 AS DECIMAL (9, 2)) AS 'acc_wh',CAST(SUM(ac_output_active_power)*0.01667 AS DECIMAL (9, 2)) AS 'consumo_wh', CAST(SUM(ac_output_active_power-(pv_voltage*pv_current)-(battery_voltage*battery_discharge_current))*0.01667 AS DECIMAL (9, 2)) AS 'import_wh',max(ac_output_active_power) AS 'peak_w'  FROM inverter_data GROUP BY 1 ORDER BY timestamp DESC LIMIT 30;";
$listDATE = array("'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'");
$listPROD = array("'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'");
$listACC = array("'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'");
$listCONSUMO = array("'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'");
$listIMPORT = array("'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'", "'n/a'");
$cnt = 0;
$sqlRESULT = $sqlCONN->query($sqlCMD);
while ($sqlROW = $sqlRESULT->fetch_assoc()) {
   // Increment counter
   $cnt += 1;
   // Extract query data row
   $vlDATE = $sqlROW["timestamp"];
   $vlPROD = number_format($sqlROW["prod_wh"] / 1000, 2, ".", "");
   $vlACC = number_format($sqlROW["acc_wh"] / 1000, 2, ".", "");
   $vlCONSUMO = number_format($sqlROW["consumo_wh"] / 1000, 2, ".", "");
   $vlIMPORT = number_format($sqlROW["import_wh"] / 1000, 2, ".", "");
   // Fix the timestamp format
   $vlDATE = substr($vlDATE, -2, 2)."/".substr($vlDATE, 4, 2)."/".substr($vlDATE, 0, 4);
   // Store query data row into arrays
   $listDATE[$cnt] = $vlDATE;
   $listPROD[$cnt] = $vlPROD;
   $listACC[$cnt] = $vlACC;
   $listCONSUMO[$cnt] = $vlCONSUMO;
   $listIMPORT[$cnt] = $vlIMPORT;
}
$sqlRESULT->close();
// Close MySQL connection
mysqli_close($sqlCONN);
?>
<!-- BEGIN PAGE CONTENTS AND CODE -->



<?php PAGE_Open("Tabelle", ""); ?>
    
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Sommario ultimi 30 giorni</div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th class="text-right">Data</th>
                                    <th class="text-right">Prodotto kWh</th>
                                    <th class="text-right">Accumulato kWh</th>
                                    <th class="text-right">Importato kWh</th>
                                    <th class="text-right">Consumato kWh</th>
                                </tr>
                                </thead>
                                <tbody>
<?php for ($i = 1; $i<= 30; $i++) { ?>
                                    <tr>
                                        <td class="text-right" style="color:#000000;"><?php echo "".$listDATE[$i]; ?></td>
                                        <td class="text-right" style="color:#5cb85c;"><?php echo "".$listPROD[$i]; ?></td>
                                        <td class="text-right" style="color:#377bb5;"><?php echo "".$listACC[$i]; ?></td>
                                        <td class="text-right" style="color:#f0ad4e;"><?php echo "".$listIMPORT[$i]; ?></td>
                                        <td class="text-right" style="color:#d9534f;"><?php echo "".$listCONSUMO[$i]; ?></td>
                                    </tr>
<?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
        </div>




<?php PAGE_Close("tables"); ?>

<!-- ADDITIONAL JAVA CODE GOES HERE -->
<!-- ADDITIONAL JAVA CODE GOES HERE -->



<!-- END PAGE CONTENTS AND CODE -->
</body>
</html>
