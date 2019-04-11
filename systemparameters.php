<!DOCTYPE html>
<head>
<link href="/_css/iosSwitch.css" rel="stylesheet">
<?php
// Include needed external modules
$webROOT = realpath($_SERVER["DOCUMENT_ROOT"]);
require("$webROOT/_library/_config.php");
require("$webROOT/_library/_masterPage.php");
require("$webROOT/_library/_functions.php");
// Detect the client IP address requesting the page
$clientIP = "";
$clientIP = $_SERVER["HTTP_X_FORWARDED_FOR"];
if ($clientIP == "") { $clientIP = $_SERVER["REMOTE_ADDR"]; }
// Check by client IP if we are serving an internal request or not
$cfgENABLE = "disabled";
if (substr($clientIP, 0, 10) == "192.168.1.") { $cfgENABLE = ""; }
if (substr($clientIP, 0, 10) == "172.18.10.") { $cfgENABLE = ""; }
// Open MySQL database connection
$sqlCONN = mysqli_connect($DB_Hostname,$DB_Username,$DB_Password, $DB_Schema);
if ($sqlCONN->connect_errno) {
  die("Could not connect to database: " . $sqlCONN->connect_error . "\n");
}
// Get page data parameters, if they exists and execute requested actions
$reqACTION = "";
if ((isset($_POST['action']))and($cfgENABLE == "")) { $reqACTION = $_POST['action']; }
switch ($reqACTION) {
	case "SETTINGS":
		// Store setting change into database
		$currDATE = date("YmdHis");
		if (isset($_POST['S01'])) { $sqlCMD = "INSERT INTO inverter_commands (timestamp,ref_table,command) VALUES ('".$currDATE."','setting.CSP','PCP".$_POST['S01']."');";      $sqlCONN->query($sqlCMD); }
		if (isset($_POST['S02'])) { $sqlCMD = "INSERT INTO inverter_commands (timestamp,ref_table,command) VALUES ('".$currDATE."','setting.OSP','POP".$_POST['S02']."');";      $sqlCONN->query($sqlCMD); }
		if (isset($_POST['S03'])) { $sqlCMD = "INSERT INTO inverter_commands (timestamp,ref_table,command) VALUES ('".$currDATE."','setting.IVR','PGR".$_POST['S03']."');";      $sqlCONN->query($sqlCMD); }
		if (isset($_POST['S04'])) { $sqlCMD = "INSERT INTO inverter_commands (timestamp,ref_table,command) VALUES ('".$currDATE."','setting.BT','PBT".$_POST['S04']."');";       $sqlCONN->query($sqlCMD); }
		if (isset($_POST['S05'])) { $sqlCMD = "INSERT INTO inverter_commands (timestamp,ref_table,command) VALUES ('".$currDATE."','setting.ACORF','F".$_POST['S05']."');";      $sqlCONN->query($sqlCMD); }
		if (isset($_POST['S06'])) { $sqlCMD = "INSERT INTO inverter_commands (timestamp,ref_table,command) VALUES ('".$currDATE."','setting.BRCV','PBCV".$_POST['S06']."');";    $sqlCONN->query($sqlCMD); }
		if (isset($_POST['S07'])) { $sqlCMD = "INSERT INTO inverter_commands (timestamp,ref_table,command) VALUES ('".$currDATE."','setting.MAC','MCHGC".$_POST['S07']."');";    $sqlCONN->query($sqlCMD); }
		if (isset($_POST['S08'])) { $sqlCMD = "INSERT INTO inverter_commands (timestamp,ref_table,command) VALUES ('".$currDATE."','setting.MACCC','MUCHGC".$_POST['S08']."');"; $sqlCONN->query($sqlCMD); }
		if (isset($_POST['S09'])) { $sqlCMD = "INSERT INTO inverter_commands (timestamp,ref_table,command) VALUES ('".$currDATE."','setting.BRDV','PBDV".$_POST['S09']."');";    $sqlCONN->query($sqlCMD); }
		if (isset($_POST['S10'])) { $sqlCMD = "INSERT INTO inverter_commands (timestamp,ref_table,command) VALUES ('".$currDATE."','setting.BBV','PCVV".$_POST['S10']."');";     $sqlCONN->query($sqlCMD); }
		if (isset($_POST['S11'])) { $sqlCMD = "INSERT INTO inverter_commands (timestamp,ref_table,command) VALUES ('".$currDATE."','setting.BFV','PBFT".$_POST['S11']."');";     $sqlCONN->query($sqlCMD); }
		if (isset($_POST['S12'])) { $sqlCMD = "INSERT INTO inverter_commands (timestamp,ref_table,command) VALUES ('".$currDATE."','setting.BUV','PSDV".$_POST['S12']."');";     $sqlCONN->query($sqlCMD); }
		if (isset($_POST['S13'])) { $sqlCMD = "INSERT INTO inverter_commands (timestamp,ref_table,command) VALUES ('".$currDATE."','setting.PVPB','PSPB".$_POST['S13']."');";    $sqlCONN->query($sqlCMD); }
		print "---SETTINGS UPDATED---";
		exit(0);
	break;
	case "PARAMETERS":
		// Store parameter change into database
		$currDATE = date("YmdHis");
		$flgBUZZER          = (isset($_POST['P1']) ? ($_POST['P1']=="1" ? "PEa" : "PDa") : "");
		$flgOVERLOADBYPASS  = (isset($_POST['P2']) ? ($_POST['P2']=="1" ? "PEb" : "PDb") : "");
		$flgPOWERSAVE       = (isset($_POST['P3']) ? ($_POST['P3']=="1" ? "PEj" : "PDj") : "");
		$flgDISPLAYDEFPAGE  = (isset($_POST['P4']) ? ($_POST['P4']=="1" ? "PEk" : "PDk") : "");
		$flgOVERLOADRESTART = (isset($_POST['P5']) ? ($_POST['P5']=="1" ? "PEu" : "PDu") : "");
		$flgOVERTEMPRESTART = (isset($_POST['P6']) ? ($_POST['P6']=="1" ? "PEv" : "PDv") : "");
		$flgBACKLIGHT       = (isset($_POST['P7']) ? ($_POST['P7']=="1" ? "PEx" : "PDx") : "");
		$flgALARMPRIMSOURCE = (isset($_POST['P8']) ? ($_POST['P8']=="1" ? "PEy" : "PDy") : "");
		$flgFAULTRECORD     = (isset($_POST['P9']) ? ($_POST['P9']=="1" ? "PEz" : "PDz") : "");
		if ($flgBUZZER          != "") { $sqlCMD = "INSERT INTO inverter_commands (timestamp,ref_table,command) VALUES ('".$currDATE."','flag.A','".$flgBUZZER."');";          $sqlCONN->query($sqlCMD); }
		if ($flgOVERLOADBYPASS  != "") { $sqlCMD = "INSERT INTO inverter_commands (timestamp,ref_table,command) VALUES ('".$currDATE."','flag.B','".$flgOVERLOADBYPASS."');";  $sqlCONN->query($sqlCMD); }
		if ($flgPOWERSAVE       != "") { $sqlCMD = "INSERT INTO inverter_commands (timestamp,ref_table,command) VALUES ('".$currDATE."','flag.J','".$flgPOWERSAVE."');";       $sqlCONN->query($sqlCMD); }
		if ($flgDISPLAYDEFPAGE  != "") { $sqlCMD = "INSERT INTO inverter_commands (timestamp,ref_table,command) VALUES ('".$currDATE."','flag.K','".$flgDISPLAYDEFPAGE."');";  $sqlCONN->query($sqlCMD); }
		if ($flgOVERLOADRESTART != "") { $sqlCMD = "INSERT INTO inverter_commands (timestamp,ref_table,command) VALUES ('".$currDATE."','flag.U','".$flgOVERLOADRESTART."');"; $sqlCONN->query($sqlCMD); }
		if ($flgOVERTEMPRESTART != "") { $sqlCMD = "INSERT INTO inverter_commands (timestamp,ref_table,command) VALUES ('".$currDATE."','flag.V','".$flgOVERTEMPRESTART."');"; $sqlCONN->query($sqlCMD); }
		if ($flgBACKLIGHT       != "") { $sqlCMD = "INSERT INTO inverter_commands (timestamp,ref_table,command) VALUES ('".$currDATE."','flag.X','".$flgBACKLIGHT."');";       $sqlCONN->query($sqlCMD); }
		if ($flgALARMPRIMSOURCE != "") { $sqlCMD = "INSERT INTO inverter_commands (timestamp,ref_table,command) VALUES ('".$currDATE."','flag.Y','".$flgALARMPRIMSOURCE."');"; $sqlCONN->query($sqlCMD); }
		if ($flgFAULTRECORD     != "") { $sqlCMD = "INSERT INTO inverter_commands (timestamp,ref_table,command) VALUES ('".$currDATE."','flag.Z','".$flgFAULTRECORD."');";     $sqlCONN->query($sqlCMD); }
		print "---FLAGS UPDATED---";
		exit(0);
	break;
	default:
		// Unknown or invalid request type
		// Do nothing
}
// Get parameters from SETTINGS table
$sqlCMD = "SELECT * FROM inverter_settings ORDER BY timestamp DESC LIMIT 1";
$sqlRESULT = $sqlCONN->query($sqlCMD);
$sqlRECORD = $sqlRESULT->fetch_assoc();
$parGRV = $sqlRECORD["GridRatingVoltage"];
$parGRC = $sqlRECORD["GridRatingCurrent"];
$parACORV = $sqlRECORD["ACOutputRatingVolt"];
$parACORF = $sqlRECORD["ACOutputRatingFreq"];
$parACORC = $sqlRECORD["ACOutputRatingCurrent"];
$parACORA = $sqlRECORD["ACOutputRatingApparentPower"];
$parACORW = $sqlRECORD["ACOutputRatingActivePower"];
$parBRV = $sqlRECORD["BatteryRatingVoltage"];
$parBRCV = $sqlRECORD["BatteryReChargeVoltage"];
$parBUV = $sqlRECORD["BatteryUnderVoltage"];
$parBBV = $sqlRECORD["BatteryBulkVoltage"];
$parBFV = $sqlRECORD["BatteryFloatVoltage"];
$parBT = $sqlRECORD["BatteryType"];
$parMACCC = $sqlRECORD["MaxACChargingCurrent"];
$parMAC = $sqlRECORD["MaxChargingCurrent"];
$parIVR = $sqlRECORD["InputVoltageRange"];
$parOSP = $sqlRECORD["OutputSourcePriority"];
$parCSP = $sqlRECORD["ChargerSourcePriority"];
$parPMN = $sqlRECORD["ParallelMaxNum"];
$parMT = $sqlRECORD["MachineType"];
$parT = $sqlRECORD["Topology"];
$parOM = $sqlRECORD["OutputMode"];
$parBRDV = $sqlRECORD["BatteryReDischargeVoltage"];
$parPVOKCFP = $sqlRECORD["PVOKConditionForParallel"];
$parPVPB = $sqlRECORD["PVPowerBalance"];
$sqlRESULT->close();
// Get parameters from FLAGS table
$sqlCMD = "SELECT * FROM inverter_flags ORDER BY timestamp DESC LIMIT 1";
$sqlRESULT = $sqlCONN->query($sqlCMD);
$sqlRECORD = $sqlRESULT->fetch_assoc();
$flgBUZZER = $sqlRECORD["a_buzzer"];
$flgOVERLOADBYPASS = $sqlRECORD["b_overloadbypass"];
$flgPOWERSAVE = $sqlRECORD["j_powersaving"];
$flgDISPLAYDEFPAGE = $sqlRECORD["k_displaydefaultpage"];
$flgOVERLOADRESTART = $sqlRECORD["u_overloadrestart"];
$flgOVERTEMPRESTART = $sqlRECORD["v_overtemprestart"];
$flgBACKLIGHT = $sqlRECORD["x_backlighton"];
$flgALARMPRIMSOURCE = $sqlRECORD["y_alarmprimarysource"];
$flgFAULTRECORD = $sqlRECORD["z_faultcoderecord"];
// Get values from FIRMWARE/SOFTWARE table
$sqlCMD = "SELECT * FROM inverter_fwsn ORDER BY timestamp DESC LIMIT 1";
$sqlRESULT = $sqlCONN->query($sqlCMD);
$sqlRECORD = $sqlRESULT->fetch_assoc();
$fwsnSERIALNUMBER = $sqlRECORD["serialNumber"];
$fwsnFIRMWARE1 = $sqlRECORD["firmware1"];
$fwsnFIRMWARE2 = $sqlRECORD["firmware2"];
$fwsnPROTOCOL = $sqlRECORD["protocol"];
// Adapt settings code to strings
switch ($parMT) {
   case "00":
      $parMT = "Grid Tie";
      break;
   case "01":
      $parMT = "Off Grid";
      break;
   case "10":
      $parMT = "Hybrid";
      break;
   default:
      $parMT = "Unknown";
}
switch ($parT) {
   case "0":
      $parT = "Transformerless";
      break;
   case "1":
      $parT = "Transformer";
      break;
   default:
      $parT = "Unknown";
}
switch ($parPVOKCFP) {
   case "0":
      $parPVOKCFP = "At least one inverter";
      break;
   case "1":
      $parPVOKCFP = "All inverters";
      break;
   default:
      $parPVOKCFP = "Unknown";
}
switch ($parOM) {
   case "00":
      $parOM = "Single machine output";
      break;
   case "01":
      $parOM = "Parallel output";
      break;
   case "02":
      $parOM = "Phase 1 of 3 Phase output";
      break;
   case "03":
      $parOM = "Phase 2 of 3 Phase output";
      break;
   case "04":
      $parOM = "Phase 3 of 3 Phase output";
      break;
   default:
      $parOM = "Unknown";
}
$sqlRESULT->close();
// Get the list of pending commands to be executed
$chgCSP   = "0";
$chgOSP   = "0";
$chgIVR   = "0";
$chgBT    = "0";
$chgACORF = "0";
$chgBRCV  = "0";
$chgMAC   = "0";
$chgMACCC = "0";
$chgBRDV  = "0";
$chgBBV   = "0";
$chgBFV   = "0";
$chgBUV   = "0";
$chgPVPB  = "0";
$chgBUZZER          = "0";
$chgOVERLOADBYPASS  = "0";
$chgPOWERSAVE       = "0";
$chgDISPLAYDEFPAGE  = "0";
$chgOVERLOADRESTART = "0";
$chgOVERTEMPRESTART = "0";
$chgBACKLIGHT       = "0";
$chgALARMPRIMSOURCE = "0";
$chgFAULTRECORD     = "0";
$sqlCMD = "SELECT ref_table,status FROM inverter_commands WHERE status<=2 ORDER BY timestamp ASC";
$sqlRESULT = $sqlCONN->query($sqlCMD);
while ($sqlROW = $sqlRESULT->fetch_assoc()) {
	// Check which setting is pending to be executed on inverter
	if ($sqlROW["ref_table"] == "setting.CSP") { $chgCSP = "1".$sqlROW["status"]; }
	if ($sqlROW["ref_table"] == "setting.OSP") { $chgOSP = "1".$sqlROW["status"]; }
	if ($sqlROW["ref_table"] == "setting.IVR") {$chgIVR = "1".$sqlROW["status"]; }
	if ($sqlROW["ref_table"] == "setting.BT") { $chgBT = "1".$sqlROW["status"]; }
	if ($sqlROW["ref_table"] == "setting.ACORF") { $chgACORF = "1".$sqlROW["status"]; }
	if ($sqlROW["ref_table"] == "setting.BRCV") { $chgBRCV = "1".$sqlROW["status"]; }
	if ($sqlROW["ref_table"] == "setting.MAC") { $chgMAC = "1".$sqlROW["status"]; }
	if ($sqlROW["ref_table"] == "setting.MACCC") { $chgMACCC = "1".$sqlROW["status"]; }
	if ($sqlROW["ref_table"] == "setting.BRDV") { $chgBRDV = "1".$sqlROW["status"]; }
	if ($sqlROW["ref_table"] == "setting.BBV") { $chgBBV = "1".$sqlROW["status"]; }
	if ($sqlROW["ref_table"] == "setting.BFV") { $chgBFV = "1".$sqlROW["status"]; }
	if ($sqlROW["ref_table"] == "setting.BUV") { $chgBUV = "1".$sqlROW["status"]; }
	if ($sqlROW["ref_table"] == "setting.PVPB") { $chgPVPB = "1".$sqlROW["status"]; }
	if ($sqlROW["ref_table"] == "flag.A") { $chgBUZZER = "1".$sqlROW["status"]; }
	if ($sqlROW["ref_table"] == "flag.B") { $chgOVERLOADBYPASS = "1".$sqlROW["status"]; }
	if ($sqlROW["ref_table"] == "flag.J") { $chgPOWERSAVE = "1".$sqlROW["status"]; }
	if ($sqlROW["ref_table"] == "flag.K") { $chgDISPLAYDEFPAGE = "1".$sqlROW["status"]; }
	if ($sqlROW["ref_table"] == "flag.U") { $chgOVERLOADRESTART = "1".$sqlROW["status"]; }
	if ($sqlROW["ref_table"] == "flag.V") { $chgOVERTEMPRESTART = "1".$sqlROW["status"]; }
	if ($sqlROW["ref_table"] == "flag.X") { $chgBACKLIGHT = "1".$sqlROW["status"]; }
	if ($sqlROW["ref_table"] == "flag.Y") { $chgALARMPRIMSOURCE = "1".$sqlROW["status"]; }
	if ($sqlROW["ref_table"] == "flag.Z") { $chgFAULTRECORD = "1".$sqlROW["status"]; }
}
$sqlRESULT->close();
?>

<!-- BEGIN PAGE CONTENTS AND CODE -->
<SCRIPT LANGUAGE="JavaScript">
//-------------------------------------------------------------------------
// Page settings
var newSETT = "0";
var currSETT_01 = "<?php print "0".$parCSP; ?>";
var currSETT_02 = "<?php print "0".$parOSP; ?>";
var currSETT_03 = "<?php print "0".$parIVR; ?>";
var currSETT_04 = "<?php print "0".$parBT; ?>";
var currSETT_05 = "<?php print round($parACORF); ?>";
var currSETT_06 = "<?php print $parBRCV; ?>";
var currSETT_07 = "<?php print "0".$parMAC; ?>";
var currSETT_08 = "<?php print ($parMACCC=="2"?"002":"0".$parMACCC); ?>";
var currSETT_09 = "<?php print $parBRDV; ?>";
var currSETT_10 = "<?php print $parBBV; ?>";
var currSETT_11 = "<?php print $parBFV; ?>";
var currSETT_12 = "<?php print $parBUV; ?>";
var currSETT_13 = "<?php print $parPVPB; ?>";
var newSETT_01 = "<?php print "0".$parCSP; ?>";
var newSETT_02 = "<?php print "0".$parOSP; ?>";
var newSETT_03 = "<?php print "0".$parIVR; ?>";
var newSETT_04 = "<?php print "0".$parBT; ?>";
var newSETT_05 = "<?php print round($parACORF); ?>";
var newSETT_06 = "<?php print $parBRCV; ?>";
var newSETT_07 = "<?php print "0".$parMAC; ?>";
var newSETT_08 = "<?php print ($parMACCC=="2"?"002":"0".$parMACCC); ?>";
var newSETT_09 = "<?php print $parBRDV; ?>";
var newSETT_10 = "<?php print $parBBV; ?>";
var newSETT_11 = "<?php print $parBFV; ?>";
var newSETT_12 = "<?php print $parBUV; ?>";
var newSETT_13 = "<?php print $parPVPB; ?>";
//-------------------------------------------------------------------------
// Page parameters
var newPARAM = "0";
var currPARAM_1 = "<?php print $flgBUZZER; ?>";
var currPARAM_2 = "<?php print $flgOVERLOADBYPASS; ?>";
var currPARAM_3 = "<?php print $flgPOWERSAVE; ?>";
var currPARAM_4 = "<?php print $flgDISPLAYDEFPAGE; ?>";
var currPARAM_5 = "<?php print $flgOVERLOADRESTART; ?>";
var currPARAM_6 = "<?php print $flgOVERTEMPRESTART; ?>";
var currPARAM_7 = "<?php print $flgBACKLIGHT; ?>";
var currPARAM_8 = "<?php print $flgALARMPRIMSOURCE; ?>";
var currPARAM_9 = "<?php print $flgFAULTRECORD; ?>";
var newPARAM_1 = "<?php print $flgBUZZER; ?>";
var newPARAM_2 = "<?php print $flgOVERLOADBYPASS; ?>";
var newPARAM_3 = "<?php print $flgPOWERSAVE; ?>";
var newPARAM_4 = "<?php print $flgDISPLAYDEFPAGE; ?>";
var newPARAM_5 = "<?php print $flgOVERLOADRESTART; ?>";
var newPARAM_6 = "<?php print $flgOVERTEMPRESTART; ?>";
var newPARAM_7 = "<?php print $flgBACKLIGHT; ?>";
var newPARAM_8 = "<?php print $flgALARMPRIMSOURCE; ?>";
var newPARAM_9 = "<?php print $flgFAULTRECORD; ?>";
//-------------------------------------------------------------------------
</SCRIPT>

<?php PAGE_Open("Parametri del Sistema", ""); ?>

IP Client: <B><?php print $clientIP; ?></B><BR>
<BR>
<div class="row">
   <div class="col-lg-12">
      <div class="panel panel-default">
         <div class="panel-heading"><span>Informazioni Apparato</div>
         <div class="panel-body">
            <table class="table">
               <tbody>
                  <!-- FIXED PARAMETERS READ-ONLY -->
                  <tr>
                     <td style="border-top:0px;">Grid Rating Voltage</td>
							<?php if ($cfgENABLE == "") { ?>
                     <td style="border-top:0px;" class="text-right"><b><?php print "".$parGRV."&nbsp;"."V"; ?></b></td>
                     <td style="border-top:0px; width:120px;" class="text-left" COLSPAN="2">&nbsp;</td>
							<?php } else { ?>
                     <td style="border-top:0px;" class="text-right" COLSPAN="3"><b><?php print "".$parGRV."&nbsp;"."V"; ?></b></td>
							<?php } ?>
                  </tr>
                  <tr>
                     <td>Grid Rating Current</td>
							<?php if ($cfgENABLE == "") { ?>
                     <td class="text-right"><b><?php print "".$parGRC."&nbsp;"."A"; ?></b></td>
                     <td style="width:120px;" class="text-left" COLSPAN="2">&nbsp;</td>
							<?php } else { ?>
                     <td class="text-right" COLSPAN="3"><b><?php print "".$parGRC."&nbsp;"."A"; ?></b></td>
							<?php } ?>
                  </tr>
                  <tr>
                     <td>AC Output Rating Voltage</td>
							<?php if ($cfgENABLE == "") { ?>
                     <td class="text-right"><b><?php print "".$parACORV."&nbsp;"."V"; ?></b></td>
                     <td style="width:120px;" class="text-left" COLSPAN="2">&nbsp;</td>
							<?php } else { ?>
                     <td class="text-right" COLSPAN="3"><b><?php print "".$parACORV."&nbsp;"."V"; ?></b></td>
							<?php } ?>
                  </tr>
                  <tr>
                     <td>AC Output Rating Current</td>
							<?php if ($cfgENABLE == "") { ?>
                     <td class="text-right"><b><?php print "".$parACORC."&nbsp;"."A"; ?></b></td>
                     <td style="width:120px;" class="text-left" COLSPAN="2">&nbsp;</td>
							<?php } else { ?>
                     <td class="text-right" COLSPAN="3"><b><?php print "".$parACORC."&nbsp;"."A"; ?></b></td>
							<?php } ?>
                  </tr>
                  <tr>
                     <td>AC Output Rating Apparent Power</td>
							<?php if ($cfgENABLE == "") { ?>
                     <td class="text-right"><b><?php print "".$parACORA."&nbsp;"."VA"; ?></b></td>
                     <td style="width:120px;" class="text-left" COLSPAN="2">&nbsp;</td>
							<?php } else { ?>
                     <td class="text-right" COLSPAN="3"><b><?php print "".$parACORA."&nbsp;"."VA"; ?></b></td>
							<?php } ?>
                  </tr>
                  <tr>
                     <td>AC Output Rating Active Power</td>
							<?php if ($cfgENABLE == "") { ?>
                     <td class="text-right"><b><?php print "".$parACORW."&nbsp;"."W"; ?></b></td>
                     <td style="width:120px;" class="text-left" COLSPAN="2">&nbsp;</td>
							<?php } else { ?>
                     <td class="text-right" COLSPAN="3"><b><?php print "".$parACORW."&nbsp;"."W"; ?></b></td>
							<?php } ?>
                  </tr>
                  <tr>
                     <td>Battery Rating Voltage</td>
							<?php if ($cfgENABLE == "") { ?>
                     <td class="text-right"><b><?php print "".$parBRV."&nbsp;"."V"; ?></b></td>
                     <td style="width:120px;" class="text-left" COLSPAN="2">&nbsp;</td>
							<?php } else { ?>
                     <td class="text-right" COLSPAN="3"><b><?php print "".$parBRV."&nbsp;"."V"; ?></b></td>
							<?php } ?>
                  </tr>
                  <tr>
                     <td>Parallel Max Number</td>
							<?php if ($cfgENABLE == "") { ?>
                     <td class="text-right"><b><?php print "".$parPMN; ?></b></td>
                     <td style="width:120px;" class="text-left" COLSPAN="2">&nbsp;</td>
							<?php } else { ?>
                     <td class="text-right" COLSPAN="3"><b><?php print "".$parPMN; ?></b></td>
							<?php } ?>
                  </tr>
                  <tr>
                     <td>Machine Type</td>
							<?php if ($cfgENABLE == "") { ?>
                     <td class="text-right"><b><?php print "".$parMT; ?></b></td>
                     <td style="width:120px;" class="text-left" COLSPAN="2">&nbsp;</td>
							<?php } else { ?>
                     <td class="text-right" COLSPAN="3"><b><?php print "".$parMT; ?></b></td>
							<?php } ?>
                  </tr>
                  <tr>
                     <td>Topology</td>
							<?php if ($cfgENABLE == "") { ?>
                     <td class="text-right"><b><?php print "".$parT; ?></b></td>
                     <td style="width:120px;" class="text-left" COLSPAN="2">&nbsp;</td>
							<?php } else { ?>
                     <td class="text-right" COLSPAN="3"><b><?php print "".$parT; ?></b></td>
							<?php } ?>
                  </tr>
                  <tr>
                     <td>Output Mode</td>
							<?php if ($cfgENABLE == "") { ?>
                     <td class="text-right"><b><?php print "".$parOM; ?></b></td>
                     <td style="width:120px;" class="text-left" COLSPAN="2"><B ID="lblSETT_22">&nbsp;</B></td>
							<?php } else { ?>
                     <td class="text-right" COLSPAN="3"><b><?php print "".$parOM; ?></b></td>
							<?php } ?>
                  </tr>
                  <tr>
                     <td>PV Ok Condition For Parallel</td>
							<?php if ($cfgENABLE == "") { ?>
                     <td class="text-right"><b><?php print "".$parPVOKCFP; ?></b></td>
                     <td style="width:120px;" class="text-left" COLSPAN="2">&nbsp;</td>
							<?php } else { ?>
                     <td class="text-right" COLSPAN="3"><b><?php print "".$parPVOKCFP; ?></b></td>
							<?php } ?>
                  </tr>
                  

                  <tr>
                     <td>Inverter Serial Number</td>
							<?php if ($cfgENABLE == "") { ?>
                     <td class="text-right"><b><?php print "".$fwsnSERIALNUMBER; ?></b></td>
                     <td style="width:120px;" class="text-left" COLSPAN="2">&nbsp;</td>
							<?php } else { ?>
                     <td class="text-right" COLSPAN="3"><b><?php print "".$fwsnSERIALNUMBER; ?></b></td>
							<?php } ?>
                  </tr>
                  <tr>
                     <td>Inverter Primary CPU Firmware</td>
							<?php if ($cfgENABLE == "") { ?>
                     <td class="text-right"><b><?php print "".$fwsnFIRMWARE1; ?></b></td>
                     <td style="width:120px;" class="text-left" COLSPAN="2">&nbsp;</td>
							<?php } else { ?>
                     <td class="text-right" COLSPAN="3"><b><?php print "".$fwsnFIRMWARE1; ?></b></td>
							<?php } ?>
                  </tr>
                  <tr>
                     <td>Inverter Secondary CPU Firmware</td>
							<?php if ($cfgENABLE == "") { ?>
                     <td class="text-right"><b><?php print "".$fwsnFIRMWARE2; ?></b></td>
                     <td style="width:120px;" class="text-left" COLSPAN="2">&nbsp;</td>
							<?php } else { ?>
                     <td class="text-right" COLSPAN="3"><b><?php print "".$fwsnFIRMWARE2; ?></b></td>
							<?php } ?>
                  </tr>
                  <tr>
                     <td>Inverter Serial Protocol</td>
							<?php if ($cfgENABLE == "") { ?>
                     <td class="text-right"><b><?php print "".$fwsnPROTOCOL; ?></b></td>
                     <td style="width:120px;" class="text-left" COLSPAN="2">&nbsp;</td>
							<?php } else { ?>
                     <td class="text-right" COLSPAN="3"><b><?php print "".$fwsnPROTOCOL; ?></b></td>
							<?php } ?>
                  </tr>
               </tbody>
            </table>
         </div>
      </div>
   </div>
   <div class="col-lg-12">
      <div class="panel panel-default">
         <div class="panel-heading"><span>Parametri</span></div>
         <div class="panel-body">
            <table class="table">
               <tbody>
                  <!-- CONFIGURABLE PARAMETERS -->
                  <tr>
                     <td style="border-top:0px;">Charger Source Priority</td>
							<?php if ($cfgENABLE == "") { ?>
                     <td style="border-top:0px;" class="text-right">
							<?php switch ($chgCSP) {
								case "10":
								   print "<B style='color:#ff5533;'>Changing</B>";
									break;
								case "11":
									print "<B style='color:#33ff55;'>Change Success</B>";
									break;
								case "12":
									print "<B style='color:#ff0000;'>Change Failed</B>";
									break;
								default:	
                           print "<select onchange=\"return doSelect(this,'SETT_01')\">";
                           print "<option value=\"00\" ".(($parCSP == "0")?"selected":"")." >Utility First</option>";
                           print "<option value=\"01\" ".(($parCSP == "1")?"selected":"")." >Solar First</option>";
                           print "<option value=\"02\" ".(($parCSP == "2")?"selected":"")." >Solar + Utility</option>";
                           print "<option value=\"03\" ".(($parCSP == "3")?"selected":"")." >Only Solar</option>";
									print "</select>";
							} ?>
                     </td>
                     <td style="border-top:0px; width:80px;" class="text-left" COLSPAN="2"><B ID="lblSETT_01">&nbsp;</B></td>
							<?php } else { ?>
                     <td style="border-top:0px;" class="text-right" COLSPAN="3"><B>
								<?php print "".($parCSP == "0" ? "Utility First" : ""); ?>
								<?php print "".($parCSP == "1" ? "Solar First" : ""); ?>
								<?php print "".($parCSP == "2" ? "Solar + Utility" : ""); ?>
								<?php print "".($parCSP == "3" ? "Only Solar" : ""); ?>
							</b></td>
							<?php } ?>
                  </tr>
                  <tr>
                     <td>Output Source Priority</td>
							<?php if ($cfgENABLE == "") { ?>
                     <td class="text-right">
							<?php switch ($chgOSP) {
								case "10":
								   print "<B style='color:#ff5533;'>Changing</B>";
									break;
								case "11":
									print "<B style='color:#33ff55;'>Change Success</B>";
									break;
								case "12":
									print "<B style='color:#ff0000;'>Change Failed</B>";
									break;
								default:	
                           print "<select onchange=\"return doSelect(this,'SETT_02')\">";
                           print "<option value=\"00\" ".(($parOSP == "0")?"selected":"")." >Utility First</option>";
                           print "<option value=\"01\" ".(($parOSP == "1")?"selected":"")." >Solar First</option>";
                           print "<option value=\"02\" ".(($parOSP == "2")?"selected":"")." >SBU First</option>";
									print "</select>";
							} ?>
                     </td>
                     <td class="text-left" COLSPAN="2"><B ID="lblSETT_02">&nbsp;</B></td>
							<?php } else { ?>
                     <td class="text-right" COLSPAN="3"><B>
								<?php print "".($parOSP == "0" ? "Utility First" : ""); ?>
								<?php print "".($parOSP == "1" ? "Solar First" : ""); ?>
								<?php print "".($parOSP == "2" ? "SBU First" : ""); ?>
							</b></td>
							<?php } ?>
                  </tr>
                  <tr>
                     <td>AC Input Voltage Range</td>
							<?php if ($cfgENABLE == "") { ?>
                     <td class="text-right">
							<?php switch ($chgIVR) {
								case "10":
								   print "<B style='color:#ff5533;'>Changing</B>";
									break;
								case "11":
									print "<B style='color:#33ff55;'>Change Success</B>";
									break;
								case "12":
									print "<B style='color:#ff0000;'>Change Failed</B>";
									break;
								default:	
                           print "<select onchange=\"return doSelect(this,'SETT_03')\">";
                           print "<option value=\"00\" ".($parIVR == "0"?"selected":"")." >Appliance</option>";
                           print "<option value=\"01\" ".($parIVR == "1"?"selected":"")." >UPS</option>";
									print "</select>";
							} ?>
                     </td>
                     <td class="text-left" COLSPAN="2"><B ID="lblSETT_03">&nbsp;</B></td>
							<?php } else { ?>
                     <td class="text-right" COLSPAN="3"><B>
								<?php print "".($parIVR == "0" ? "Appliance" : "UPS"); ?>
							</b></td>
							<?php } ?>

                  </tr>
                  <tr>
                     <td>Battery Type</td>
							<?php if ($cfgENABLE == "") { ?>
                     <td class="text-right">
							<?php switch ($chgBT) {
								case "10":
								   print "<B style='color:#ff5533;'>Changing</B>";
									break;
								case "11":
									print "<B style='color:#33ff55;'>Change Success</B>";
									break;
								case "12":
									print "<B style='color:#ff0000;'>Change Failed</B>";
									break;
								default:	
                           print "<select onchange=\"return doSelect(this,'SETT_04')\">";
                           print "<option value=\"00\" ".($parBT == "0" ? "selected" : "")." >AGM</option>";
                           print "<option value=\"01\" ".($parBT == "1" ? "selected" : "")." >Flooded</option>";
                           print "<option value=\"02\" ".($parBT == "2" ? "selected" : "")." >User</option>";
									print "</select>";
							} ?>
                     </td>
                     <td class="text-left" COLSPAN="2"><B ID="lblSETT_04">&nbsp;</B></td>
							<?php } else { ?>
                     <td class="text-right" COLSPAN="3"><B>
								<?php print "".($parBT == "0" ? "AGM" : ""); ?>
								<?php print "".($parBT == "1" ? "Flooded" : ""); ?>
								<?php print "".($parBT == "2" ? "User" : ""); ?>
							</b></td>
							<?php } ?>
                  </tr>
                  <tr>
                     <td>AC Output Rating Frequency</td>
							<?php if ($cfgENABLE == "") { ?>
                     <td class="text-right">
							<?php switch ($chgACORF) {
								case "10":
								   print "<B style='color:#ff5533;'>Changing</B>";
									break;
								case "11":
									print "<B style='color:#33ff55;'>Change Success</B>";
									break;
								case "12":
									print "<B style='color:#ff0000;'>Change Failed</B>";
									break;
								default:	
                           print "<select onchange=\"return doSelect(this,'SETT_05')\">";
                           print "<option value=\"50\" ".($parACORF == "50" ? "selected" : "")." >50 Hz</option>";
                           print "<option value=\"60\" ".($parACORF == "60" ? "selected" : "")." >60 Hz</option>";
									print "</select>";
							} ?>
                     </td>
                     <td class="text-left" COLSPAN="2"><B ID="lblSETT_05">&nbsp;</B></td>
							<?php } else { ?>
                     <td class="text-right" COLSPAN="3"><B>
                           <?php print "".($parACORF == "50" ? "50 Hz" : "60 Hz"); ?>
							</b></td>
							<?php } ?>
                  </tr>
                  <tr>
                     <td>Battery ReCharge Voltage</td>
							<?php if ($cfgENABLE == "") { ?>
                     <td class="text-right">
							<?php switch ($chgBRCV) {
								case "10":
								   print "<B style='color:#ff5533;'>Changing</B>";
									break;
								case "11":
									print "<B style='color:#33ff55;'>Change Success</B>";
									break;
								case "12":
									print "<B style='color:#ff0000;'>Change Failed</B>";
									break;
								default:	
                           print "<select onchange=\"return doSelect(this,'SETT_06')\">";
                           print "<option value=\"44.0\" ".($parBRCV == "44.0" ? "selected" : "")." >44.0 V</option>";
                           print "<option value=\"45.0\" ".($parBRCV == "45.0" ? "selected" : "")." >45.0 V</option>";
                           print "<option value=\"46.0\" ".($parBRCV == "46.0" ? "selected" : "")." >46.0 V</option>";
                           print "<option value=\"47.0\" ".($parBRCV == "47.0" ? "selected" : "")." >47.0 V</option>";
                           print "<option value=\"48.0\" ".($parBRCV == "48.0" ? "selected" : "")." >48.0 V</option>";
                           print "<option value=\"49.0\" ".($parBRCV == "49.0" ? "selected" : "")." >49.0 V</option>";
                           print "<option value=\"50.0\" ".($parBRCV == "50.0" ? "selected" : "")." >50.0 V</option>";
                           print "<option value=\"51.0\" ".($parBRCV == "51.0" ? "selected" : "")." >51.0 V</option>";
									print "</select>";
							} ?>
                     </td>
                     <td class="text-left" COLSPAN="2"><B ID="lblSETT_06">&nbsp;</B></td>
							<?php } else { ?>
                     <td class="text-right" COLSPAN="3"><B>
                           <?php print "".$parBRCV." V"; ?>
							</b></td>
							<?php } ?>
                  </tr>
                  <tr>
                     <td>Max Charging Current</td>
							<?php if ($cfgENABLE == "") { ?>
                     <td class="text-right">
							<?php switch ($chgMAC) {
								case "10":
								   print "<B style='color:#ff5533;'>Changing</B>";
									break;
								case "11":
									print "<B style='color:#33ff55;'>Change Success</B>";
									break;
								case "12":
									print "<B style='color:#ff0000;'>Change Failed</B>";
									break;
								default:	
                           print "<select onchange=\"return doSelect(this,'SETT_07')\">";
                           print "<option value=\"010\" ".($parMAC == "10" ? "selected" : "")." >10 A</option>";
                           print "<option value=\"020\" ".($parMAC == "20" ? "selected" : "")." >20 A</option>";
                           print "<option value=\"030\" ".($parMAC == "30" ? "selected" : "")." >30 A</option>";
                           print "<option value=\"040\" ".($parMAC == "40" ? "selected" : "")." >40 A</option>";
                           print "<option value=\"050\" ".($parMAC == "50" ? "selected" : "")." >50 A</option>";
                           print "<option value=\"060\" ".($parMAC == "60" ? "selected" : "")." >60 A</option>";
                           print "<option value=\"070\" ".($parMAC == "70" ? "selected" : "")." >70 A</option>";
                           print "<option value=\"080\" ".($parMAC == "80" ? "selected" : "")." >80 A</option>";
                           print "<option value=\"090\" ".($parMAC == "90" ? "selected" : "")." >90 A</option>";
									print "</select>";
							} ?>
                     </td>
                     <td class="text-left" COLSPAN="2"><B ID="lblSETT_07">&nbsp;</B></td>
							<?php } else { ?>
                     <td class="text-right" COLSPAN="3"><B>
								<?php print "".$parMAC." A"; ?>
							</b></td>
							<?php } ?>
                  </tr>
                  <tr>
                     <td>Max AC Charging Current</td>
							<?php if ($cfgENABLE == "") { ?>
                     <td class="text-right">
							<?php switch ($chgMACCC) {
								case "10":
								   print "<B style='color:#ff5533;'>Changing</B>";
									break;
								case "11":
									print "<B style='color:#33ff55;'>Change Success</B>";
									break;
								case "12":
									print "<B style='color:#ff0000;'>Change Failed</B>";
									break;
								default:	
                           print "<select onchange=\"return doSelect(this,'SETT_08')\">";
                           print "<option value=\"002\" ".($parMACCC == "2" ? "selected" : "")." >2 A</option>";
                           print "<option value=\"010\" ".($parMACCC == "10" ? "selected" : "")." >10 A</option>";
                           print "<option value=\"020\" ".($parMACCC == "20" ? "selected" : "")." >20 A</option>";
                           print "<option value=\"030\" ".($parMACCC == "30" ? "selected" : "")." >30 A</option>";
                           print "<option value=\"040\" ".($parMACCC == "40" ? "selected" : "")." >40 A</option>";
                           print "<option value=\"050\" ".($parMACCC == "50" ? "selected" : "")." >50 A</option>";
                           print "<option value=\"060\" ".($parMACCC == "60" ? "selected" : "")." >60 A</option>";
									print "</select>";
							} ?>
                     </td>
                     <td class="text-left" COLSPAN="2"><B ID="lblSETT_08">&nbsp;</B></td>
							<?php } else { ?>
                     <td class="text-right" COLSPAN="3"><B>
								<?php print "".$parMACCC." A"; ?>
							</b></td>
							<?php } ?>
                  </tr>
                  <tr>
                     <td>Battery ReDischarge Voltage</td>
							<?php if ($cfgENABLE == "") { ?>
                     <td class="text-right">
							<?php switch ($chgBRDV) {
								case "10":
								   print "<B style='color:#ff5533;'>Changing</B>";
									break;
								case "11":
									print "<B style='color:#33ff55;'>Change Success</B>";
									break;
								case "12":
									print "<B style='color:#ff0000;'>Change Failed</B>";
									break;
								default:	
                           print "<select onchange=\"return doSelect(this,'SETT_09')\">";
                           print "<option value=\"00.0\" ".($parBRDV == "FULL" ? "selected" : "")." >Full</option>";
                           print "<option value=\"48.0\" ".($parBRDV == "48.0" ? "selected" : "")." >48.0 V</option>";
                           print "<option value=\"49.0\" ".($parBRDV == "49.0" ? "selected" : "")." >49.0 V</option>";
                           print "<option value=\"50.0\" ".($parBRDV == "50.0" ? "selected" : "")." >50.0 V</option>";
                           print "<option value=\"51.0\" ".($parBRDV == "51.0" ? "selected" : "")." >51.0 V</option>";
                           print "<option value=\"52.0\" ".($parBRDV == "52.0" ? "selected" : "")." >52.0 V</option>";
                           print "<option value=\"53.0\" ".($parBRDV == "53.0" ? "selected" : "")." >53.0 V</option>";
                           print "<option value=\"54.0\" ".($parBRDV == "54.0" ? "selected" : "")." >54.0 V</option>";
                           print "<option value=\"55.0\" ".($parBRDV == "55.0" ? "selected" : "")." >55.0 V</option>";
                           print "<option value=\"56.0\" ".($parBRDV == "56.0" ? "selected" : "")." >56.0 V</option>";
                           print "<option value=\"57.0\" ".($parBRDV == "57.0" ? "selected" : "")." >57.0 V</option>";
                           print "<option value=\"58.0\" ".($parBRDV == "58.0" ? "selected" : "")." >58.0 V</option>";
									print "</select>";
							} ?>
                     </td>
                     <td class="text-left" COLSPAN="2"><B ID="lblSETT_09">&nbsp;</B></td>
							<?php } else { ?>
                     <td class="text-right" COLSPAN="3"><B>
								<?php print "".$parBRDV." V"; ?>
							</b></td>
							<?php } ?>
                  </tr>
                  <tr>
                     <td>Battery Bulk Voltage</td>
							<?php if (($cfgENABLE == "")and($parBT == "2")) { ?>
                     <td class="text-right">
							<?php switch ($chgBBV) {
								case "10":
								   print "<B style='color:#ff5533;'>Changing</B>";
									break;
								case "11":
									print "<B style='color:#33ff55;'>Change Success</B>";
									break;
								case "12":
									print "<B style='color:#ff0000;'>Change Failed</B>";
									break;
								default:	
                           print "<select onchange=\"return doSelect(this,'SETT_10')\">";
									for ($iii=48.0; $iii<58.5; $iii+=0.1) {
										$tmpST = number_format($iii, 1, ".", "");
										print "<option value=\"".$tmpST."\" ".($parBRDV == $tmpST ? "selected" : "")." >".$tmpST." V</option>";
									}
									print "</select>";
							} ?>
                     </td>
                     <td class="text-left" COLSPAN="2"><B ID="lblSETT_10">&nbsp;</B></td>
							<?php } else { ?>
                     <td class="text-right" COLSPAN="3"><B><?php print "".$parBBV." V"; ?></b></td>
							<?php } ?>
                  </tr>
                  <tr>
                     <td>Battery Float Voltage</td>
							<?php if (($cfgENABLE == "")and($parBT == "2")) { ?>
                     <td class="text-right">
							<?php switch ($chgBFV) {
								case "10":
								   print "<B style='color:#ff5533;'>Changing</B>";
									break;
								case "11":
									print "<B style='color:#33ff55;'>Change Success</B>";
									break;
								case "12":
									print "<B style='color:#ff0000;'>Change Failed</B>";
									break;
								default:	
                           print "<select onchange=\"return doSelect(this,'SETT_11')\">";
									for ($iii=48.0; $iii<58.5; $iii+=0.1) {
										$tmpST = number_format($iii, 1, ".", "");
										print "<option value=\"".$tmpST."\" ".($parBFV == $tmpST ? "selected" : "")." >".$tmpST." V</option>";
									}
									print "</select>";
							} ?>
                     </td>
                     <td class="text-left" COLSPAN="2"><B ID="lblSETT_11">&nbsp;</B></td>
							<?php } else { ?>
                     <td class="text-right" COLSPAN="3"><B><?php print "".$parBFV." V"; ?></b></td>
							<?php } ?>
                  </tr>
                  <tr>
                     <td>Battery Under Voltage</td>
							<?php if (($cfgENABLE == "")and($parBT == "2")) { ?>
                     <td class="text-right">
							<?php switch ($chgBUV) {
								case "10":
								   print "<B style='color:#ff5533;'>Changing</B>";
									break;
								case "11":
									print "<B style='color:#33ff55;'>Change Success</B>";
									break;
								case "12":
									print "<B style='color:#ff0000;'>Change Failed</B>";
									break;
								default:	
                           print "<select onchange=\"return doSelect(this,'SETT_12')\">";
									for ($iii=40.0; $iii<=48.0; $iii+=0.1) {
										$tmpST = number_format($iii, 1, ".", "");
										print "<option value=\"".$tmpST."\" ".($parBUV == $tmpST ? "selected" : "")." >".$tmpST." V</option>";
									}
									print "</select>";
							} ?>
                     </td>
                     <td class="text-left" COLSPAN="2"><B ID="lblSETT_12">&nbsp;</B></td>
							<?php } else { ?>
                     <td class="text-right" COLSPAN="3"><B><?php print "".$parBUV." V"; ?></b></td>
							<?php } ?>
                  </tr>
                  <tr>
                     <td>PV Power Balance</td>
							<?php if ($cfgENABLE == "") { ?>
                     <td class="text-right">
							<?php switch ($chgPVPB) {
								case "10":
								   print "<B style='color:#ff5533;'>Changing</B>";
									break;
								case "11":
									print "<B style='color:#33ff55;'>Change Success</B>";
									break;
								case "12":
									print "<B style='color:#ff0000;'>Change Failed</B>";
									break;
								default:	
	                        print "<label class=\"ios-switch\"><input type=\"checkbox\" ".(($parPVPB == "1")?"checked":"")." onClick=\"return doSwitch(this, 'SETT_13');\"><i></i></label>";
							} ?>
                     </td>
                     <td class="text-left" COLSPAN="2"><B ID="lblSETT_13">&nbsp;</B></td>
							<?php } else { ?>
                     <td class="text-right" COLSPAN="3"><B>
								<?php print ($parPVPB == "1" ? "On" : "Off"); ?>
							</b></td>
							<?php } ?>
                  </tr>
            </tbody>
            </table>
				<?php if ($cfgENABLE == "") { ?>
            <center>
            <a href="javascript:return false;" onClick="return btnSubmitSettings(this)" ID="objSUBMITsettings" disabled class="btn btn-default" <?php print $cfgENABLE; ?>><i class="fa fa-save"></i> Salva</a>
            </center>
				<?php } ?>
         </div>
      </div>
   </div>
   <div class="col-lg-12">
      <div class="panel panel-default">
         <div class="panel-heading"><span>Flags</span></div>
         <div class="panel-body">
            <table class="table">
               <tbody>
                  <tr>
                     <td style="border-top:0px;">Silenzia buzzer/Buzzer apertura</td>
							<?php if ($cfgENABLE == "") { ?>
							<?php switch ($chgBUZZER) {
								case "10":
								   print "<td style=\"border-top:0px;\" COLSPAN=\"3\"><B style='color:#ff5533;'>Changing</B></td>";
									break;
								case "11":
									print "<td style=\"border-top:0px;\" COLSPAN=\"3\"><B style='color:#33ff55;'>Change Success</B></td>";
									break;
								case "12":
									print "<td style=\"border-top:0px;\" COLSPAN=\"3\"><B style='color:#ff0000;'>Change Failed</B></td>";
									break;
								default:	
									print "<td style=\"border-top:0px;\" class=\"text-right\">";
									print "<label class=\"ios-switch\"><input type=\"checkbox\" ".(($flgBUZZER == "1")?"checked":"")." onClick=\"return doSwitch(this, 'PARAM_1');\"><i></i></label>";
									print "</td>";
									print "<td style=\"border-top:0px; width:80px;\" class=\"text-left\" COLSPAN=\"2\"><B ID=\"lblPARAM_1\">".(($flgBUZZER == "1")?"On":"Off")."</B></td>";
							} ?>
							<?php } else { ?>
                     <td style="border-top:0px;" class="text-right" COLSPAN="3"><B>
								 <?php print ($flgBUZZER == "1" ? "On" : "Off"); ?>
							</b></td>
							<?php } ?>
                  </tr>
                  <tr>
                     <td>Bypass quando in Sovraccarico</td>
							<?php if ($cfgENABLE == "") { ?>
							<?php switch ($chgOVERLOADBYPASS) {
								case "10":
								   print "<td COLSPAN=\"3\"><B style='color:#ff5533;'>Changing</B></td>";
									break;
								case "11":
									print "<td COLSPAN=\"3\"><B style='color:#33ff55;'>Change Success</B></td>";
									break;
								case "12":
									print "<td COLSPAN=\"3\"><B style='color:#ff0000;'>Change Failed</B></td>";
									break;
								default:	
									print "<td class=\"text-right\">";
									print "<label class=\"ios-switch\"><input type=\"checkbox\" ".(($flgOVERLOADBYPASS == "1")?"checked":"")." onClick=\"return doSwitch(this, 'PARAM_2');\"><i></i></label>";
									print "</td>";
									print "<td style=\"width:80px;\" class=\"text-left\" COLSPAN=\"2\"><B ID=\"lblPARAM_2\">".(($flgOVERLOADBYPASS == "1")?"On":"Off")."</B></td>";
							} ?>
							<?php } else { ?>
                     <td class="text-right" COLSPAN="3"><B>
								<?php print ($flgOVERLOADBYPASS == "1" ? "On" : "Off"); ?>
							</b></td>
							<?php } ?>
                  </tr>
                  <tr>
                     <td>Risparmio Energetico</td>
							<?php if ($cfgENABLE == "") { ?>
							<?php switch ($chgPOWERSAVE) {
								case "10":
								   print "<td COLSPAN=\"3\"><B style='color:#ff5533;'>Changing</B></td>";
									break;
								case "11":
									print "<td COLSPAN=\"3\"><B style='color:#33ff55;'>Change Success</B></td>";
									break;
								case "12":
									print "<td COLSPAN=\"3\"><B style='color:#ff0000;'>Change Failed</B></td>";
									break;
								default:	
									print "<td class=\"text-right\">";
									print "<label class=\"ios-switch\"><input type=\"checkbox\" ".(($flgPOWERSAVE == "1")?"checked":"")." onClick=\"return doSwitch(this, 'PARAM_3');\"><i></i></label>";
									print "</td>";
									print "<td style=\"width:80px;\" class=\"text-left\" COLSPAN=\"2\"><B ID=\"lblPARAM_3\">".(($flgPOWERSAVE == "1")?"On":"Off")."</B></td>";
							} ?>
							<?php } else { ?>
                     <td class="text-right" COLSPAN="3"><B>
								<?php print ($flgPOWERSAVE == "1" ? "On" : "Off"); ?>
							</b></td>
							<?php } ?>
                  </tr>
                  <tr>
                     <td>Ritorno del Display alla pagina di default dopo 1 minuto</td>
							<?php if ($cfgENABLE == "") { ?>
							<?php switch ($chgDISPLAYDEFPAGE) {
								case "10":
								   print "<td COLSPAN=\"3\"><B style='color:#ff5533;'>Changing</B></td>";
									break;
								case "11":
									print "<td COLSPAN=\"3\"><B style='color:#33ff55;'>Change Success</B></td>";
									break;
								case "12":
									print "<td COLSPAN=\"3\"><B style='color:#ff0000;'>Change Failed</B></td>";
									break;
								default:	
									print "<td class=\"text-right\">";
									print "<label class=\"ios-switch\"><input type=\"checkbox\" ".(($flgDISPLAYDEFPAGE == "1")?"checked":"")." onClick=\"return doSwitch(this, 'PARAM_4');\"><i></i></label>";
									print "</td>";
									print "<td style=\"width:80px;\" class=\"text-left\" COLSPAN=\"2\"><B ID=\"lblPARAM_4\">".(($flgDISPLAYDEFPAGE == "1")?"On":"Off")."</B></td>";
							} ?>
							<?php } else { ?>
                     <td class="text-right" COLSPAN="3"><B>
								<?php print ($flgDISPLAYDEFPAGE == "1" ? "On" : "Off"); ?>
							</b></td>
							<?php } ?>
                  </tr>
                  <tr>
                     <td>Riavvio se Sovraccarico</td>
							<?php if ($cfgENABLE == "") { ?>
							<?php switch ($chgOVERLOADRESTART) {
								case "10":
								   print "<td COLSPAN=\"3\"><B style='color:#ff5533;'>Changing</B></td>";
									break;
								case "11":
									print "<td COLSPAN=\"3\"><B style='color:#33ff55;'>Change Success</B></td>";
									break;
								case "12":
									print "<td COLSPAN=\"3\"><B style='color:#ff0000;'>Change Failed</B></td>";
									break;
								default:	
									print "<td class=\"text-right\">";
									print "<label class=\"ios-switch\"><input type=\"checkbox\" ".(($flgOVERLOADRESTART == "1")?"checked":"")." onClick=\"return doSwitch(this, 'PARAM_5');\"><i></i></label>";
									print "</td>";
									print "<td style=\"width:80px;\" class=\"text-left\" COLSPAN=\"2\"><B ID=\"lblPARAM_5\">".(($flgOVERLOADRESTART == "1")?"On":"Off")."</B></td>";
							} ?>
							<?php } else { ?>
                     <td class="text-right" COLSPAN="3"><B>
							<?php print ($flgOVERLOADRESTART == "1" ? "On" : "Off"); ?>
							</b></td>
							<?php } ?>
                  </tr>
                  <tr>
                     <td>Riavvio su Surriscaldato</td>
							<?php if ($cfgENABLE == "") { ?>
							<?php switch ($chgOVERTEMPRESTART) {
								case "10":
								   print "<td COLSPAN=\"3\"><B style='color:#ff5533;'>Changing</B></td>";
									break;
								case "11":
									print "<td COLSPAN=\"3\"><B style='color:#33ff55;'>Change Success</B></td>";
									break;
								case "12":
									print "<td COLSPAN=\"3\"><B style='color:#ff0000;'>Change Failed</B></td>";
									break;
								default:	
									print "<td class=\"text-right\">";
									print "<label class=\"ios-switch\"><input type=\"checkbox\" ".(($flgOVERTEMPRESTART == "1")?"checked":"")." onClick=\"return doSwitch(this, 'PARAM_6');\"><i></i></label>";
									print "</td>";
									print "<td style=\"width:80px;\" class=\"text-left\" COLSPAN=\"2\"><B ID=\"lblPARAM_6\">".(($flgOVERTEMPRESTART == "1")?"On":"Off")."</B></td>";
							} ?>
							<?php } else { ?>
                     <td class="text-right" COLSPAN="3"><B>
								<?php print ($flgOVERTEMPRESTART == "1" ? "On" : "Off"); ?>
							</b></td>
							<?php } ?>
                  </tr>
                  <tr>
                     <td>Retroilluminazione Display</td>
							<?php if ($cfgENABLE == "") { ?>
							<?php switch ($chgBACKLIGHT) {
								case "10":
								   print "<td COLSPAN=\"3\"><B style='color:#ff5533;'>Changing</B></td>";
									break;
								case "11":
									print "<td COLSPAN=\"3\"><B style='color:#33ff55;'>Change Success</B></td>";
									break;
								case "12":
									print "<td COLSPAN=\"3\"><B style='color:#ff0000;'>Change Failed</B></td>";
									break;
								default:	
									print "<td class=\"text-right\">";
									print "<label class=\"ios-switch\"><input type=\"checkbox\" ".(($flgBACKLIGHT == "1")?"checked":"")." onClick=\"return doSwitch(this, 'PARAM_7');\"><i></i></label>";
									print "</td>";
									print "<td style=\"width:80px;\" class=\"text-left\" COLSPAN=\"2\"><B ID=\"lblPARAM_7\">".(($flgBACKLIGHT == "1")?"On":"Off")."</B></td>";
							} ?>
							<?php } else { ?>
                     <td class="text-right" COLSPAN="3"><B>
								<?php print ($flgBACKLIGHT == "1" ? "On" : "Off"); ?>
							</b></td>
							<?php } ?>
                  </tr>
                  <tr>
                     <td>Allarme quando l'alimentazione primaria è assente</td>
							<?php if ($cfgENABLE == "") { ?>
							<?php switch ($chgALARMPRIMSOURCE) {
								case "10":
								   print "<td COLSPAN=\"3\"><B style='color:#ff5533;'>Changing</B></td>";
									break;
								case "11":
									print "<td COLSPAN=\"3\"><B style='color:#33ff55;'>Change Success</B></td>";
									break;
								case "12":
									print "<td COLSPAN=\"3\"><B style='color:#ff0000;'>Change Failed</B></td>";
									break;
								default:	
									print "<td class=\"text-right\">";
									print "<label class=\"ios-switch\"><input type=\"checkbox\" ".(($flgALARMPRIMSOURCE == "1")?"checked":"")." onClick=\"return doSwitch(this, 'PARAM_8');\"><i></i></label>";
									print "</td>";
									print "<td style=\"width:80px;\" class=\"text-left\" COLSPAN=\"2\"><B ID=\"lblPARAM_8\">".(($flgALARMPRIMSOURCE == "1")?"On":"Off")."</B></td>";
							} ?>
							<?php } else { ?>
                     <td class="text-right" COLSPAN="3"><B>
								<?php print ($flgALARMPRIMSOURCE == "1" ? "On" : "Off"); ?>
							</b></td>
							<?php } ?>
                  </tr>
                  <tr>
                     <td>Memorizza Codici di Errore</td>
							<?php if ($cfgENABLE == "") { ?>
							<?php switch ($chgFAULTRECORD) {
								case "10":
								   print "<td COLSPAN=\"3\"><B style='color:#ff5533;'>Changing</B></td>";
									break;
								case "11":
									print "<td COLSPAN=\"3\"><B style='color:#33ff55;'>Change Success</B></td>";
									break;
								case "12":
									print "<td COLSPAN=\"3\"><B style='color:#ff0000;'>Change Failed</B></td>";
									break;
								default:	
									print "<td class=\"text-right\">";
									print "<label class=\"ios-switch\"><input type=\"checkbox\" ".(($flgFAULTRECORD == "1")?"checked":"")." onClick=\"return doSwitch(this, 'PARAM_9');\"><i></i></label>";
									print "</td>";
									print "<td style=\"width:80px;\" class=\"text-left\" COLSPAN=\"2\"><B ID=\"lblPARAM_9\">".(($flgFAULTRECORD == "1")?"On":"Off")."</B></td>";
							} ?>
							<?php } else { ?>
                     <td class="text-right" COLSPAN="3"><B>
								<?php print ($flgFAULTRECORD == "1" ? "On" : "Off"); ?>
							</b></td>
							<?php } ?>

                  </tr>
               </tbody>
            </table>
				<?php if ($cfgENABLE == "") { ?>
            <center>
            <a href="javascript:return false;" onClick="return btnSubmitParams(this);" ID="objSUBMITparams" disabled class="btn btn-default" <?php print $cfgENABLE; ?>><i class="fa fa-save"></i> Salva</a>
            </center>
				<?php } ?>
         </div>
      </div>
   </div>
</div>

<?php PAGE_Close("sysparams"); ?>

<?php
// Close MySQL connection
mysqli_close($sqlCONN);
?>

<?php if ($cfgENABLE == "") { ?>
<!-- ADDITIONAL JAVA CODE GOES HERE -->
<SCRIPT LANGUAGE="JavaScript">
//-------------------------------------------------------------------------
// Function to save new settings
function btnSubmitSettings(objLINK) {
   if ((newSETT == "1")&&("<?php print "".$cfgENABLE; ?>" != "disabled")) {
		// Changes made and update available
		var urlSETT = "action=SETTINGS";
		if (currSETT_01 != newSETT_01) { urlSETT += "&S01="+newSETT_01; }
		if (currSETT_02 != newSETT_02) { urlSETT += "&S02="+newSETT_02; }
		if (currSETT_03 != newSETT_03) { urlSETT += "&S03="+newSETT_03; }
		if (currSETT_04 != newSETT_04) { urlSETT += "&S04="+newSETT_04; }
		if (currSETT_05 != newSETT_05) { urlSETT += "&S05="+newSETT_05; }
		if (currSETT_06 != newSETT_06) { urlSETT += "&S06="+newSETT_06; }
		if (currSETT_07 != newSETT_07) { urlSETT += "&S07="+newSETT_07; }
		if (currSETT_08 != newSETT_08) { urlSETT += "&S08="+newSETT_08; }
		if (currSETT_09 != newSETT_09) { urlSETT += "&S09="+newSETT_09; }
		if (currSETT_10 != newSETT_10) { urlSETT += "&S10="+newSETT_10; }
		if (currSETT_11 != newSETT_11) { urlSETT += "&S11="+newSETT_11; }
		if (currSETT_12 != newSETT_12) { urlSETT += "&S12="+newSETT_12; }
		if (currSETT_13 != newSETT_13) { urlSETT += "&S13="+newSETT_13; }
		$.ajax({ 
			async: false,
			url: '<?php print "".$_SERVER["PHP_SELF"]; ?>', 
			type: 'POST', 
			data: urlSETT,
			timeout: 4000,
			success: function( data, textStatus, jQxhr ){
				document.location = "<?php print "".$_SERVER["PHP_SELF"]; ?>";
         },
         error: function( jqXhr, textStatus, errorThrown ){
				document.location = "<?php print "".$_SERVER["PHP_SELF"]; ?>";
         }
		});
		return false;
   } else {
		// No changes made, or update unavailable
		return false;
   }
}
//-------------------------------------------------------------------------
// Function to save new settings
function btnSubmitParams(objLINK) {
   if ((newPARAM == "1")&&("<?php print "".$cfgENABLE; ?>" != "disabled")) {
		// Changes made and update available
		var urlPARS = "action=PARAMETERS";
		if (currPARAM_1 != newPARAM_1) { urlPARS += "&P1="+newPARAM_1; }
		if (currPARAM_2 != newPARAM_2) { urlPARS += "&P2="+newPARAM_2; }
		if (currPARAM_3 != newPARAM_3) { urlPARS += "&P3="+newPARAM_3; }
		if (currPARAM_4 != newPARAM_4) { urlPARS += "&P4="+newPARAM_4; }
		if (currPARAM_5 != newPARAM_5) { urlPARS += "&P5="+newPARAM_5; }
		if (currPARAM_6 != newPARAM_6) { urlPARS += "&P6="+newPARAM_6; }
		if (currPARAM_7 != newPARAM_7) { urlPARS += "&P7="+newPARAM_7; }
		if (currPARAM_8 != newPARAM_8) { urlPARS += "&P8="+newPARAM_8; }
		if (currPARAM_9 != newPARAM_9) { urlPARS += "&P9="+newPARAM_9; }
		$.ajax({
			async: false,
			url: '<?php print "".$_SERVER["PHP_SELF"]; ?>', 
			type: 'POST', 
			data: urlPARS,
			timeout: 4000,
			success: function( data, textStatus, jQxhr ){
				document.location = "<?php print "".$_SERVER["PHP_SELF"]; ?>";
         },
         error: function( jqXhr, textStatus, errorThrown ){
				document.location = "<?php print "".$_SERVER["PHP_SELF"]; ?>";
         }
		});
		return false;
   } else {
      // No changes made, or update unavailable
      return false;
   }
}
//-------------------------------------------------------------------------
// Evaluate selection change status
function doSelect(selOBJECT, selINDEX) {
   // Get the value of SELECT object
   var objVALUE = ""+selOBJECT.options[selOBJECT.selectedIndex].value;
   // Get the current setting value
   var currVALUE = eval("curr" + selINDEX);
   // Get the current APPLY button object
   var lblVALUE = eval("lbl" + selINDEX);
   // Store the new selected SWITCH value into NEW VALUE variable
   eval("new" + selINDEX + " = '" + objVALUE + "'");
   // Check if the setting has changed
   if (objVALUE != currVALUE) {
      // Setting changed, enable the APPLY button
      lblVALUE.innerHTML = "Changed";
      lblVALUE.style.color = "#e7874e";
   }else{
      // Settingnot changed, disable the APPLY button
      lblVALUE.innerHTML = "&nbsp;";
      lblVALUE.style.color = "#000000";
   }
   // Evaluate if SAVE button have to be enabled
   evaluateButtonState();
   // Return a dummy value
   return true;
}
//-------------------------------------------------------------------------
// Evaluate switch change status
function doSwitch(selOBJECT, selINDEX) {
   // Get the value of SWITCH object
   var objVALUE = (selOBJECT.checked == true ? "1" : "0");
   // Get the current settings value
   var currVALUE = eval("curr" + selINDEX);
   // Get the current SWITCH LABEL object
   var lblVALUE = eval("lbl" + selINDEX);
   // Store the new selected SWITCH value into NEW VALUE variable
   eval("new" + selINDEX + " = '" + objVALUE + "'");
   // Check if selected value changed from previous value
   if (currVALUE != objVALUE) {
      // Display that value has been changed
      lblVALUE.innerHTML = "Changed";
      lblVALUE.style.color = "#e7874e";
   }else{
      // Value has been rolled back to its default
      lblVALUE.innerHTML = (currVALUE == "1" ? "On" : "Off");
      lblVALUE.style.color = "#000000";
   }
   // Evaluate if SAVE button have to be enabled
   evaluateButtonState();
   // Return a dummy value
   return true;
}
//-------------------------------------------------------------------------
// Evaluate all settings if they are changed or not for enable the SAVE/CANCEL button
function evaluateButtonState() {
   // Check values between CURRENT and NEW
   var lstSTATES = "";
   if (currSETT_01 == newSETT_01) { lstSTATES += "0"; } else { lstSTATES += "1"; }
   if (currSETT_02 == newSETT_02) { lstSTATES += "0"; } else { lstSTATES += "1"; }
   if (currSETT_03 == newSETT_03) { lstSTATES += "0"; } else { lstSTATES += "1"; }
   if (currSETT_04 == newSETT_04) { lstSTATES += "0"; } else { lstSTATES += "1"; }
   if (currSETT_05 == newSETT_05) { lstSTATES += "0"; } else { lstSTATES += "1"; }
   if (currSETT_06 == newSETT_06) { lstSTATES += "0"; } else { lstSTATES += "1"; }
   if (currSETT_07 == newSETT_07) { lstSTATES += "0"; } else { lstSTATES += "1"; }
   if (currSETT_08 == newSETT_08) { lstSTATES += "0"; } else { lstSTATES += "1"; }
   if (currSETT_09 == newSETT_09) { lstSTATES += "0"; } else { lstSTATES += "1"; }
   if (currSETT_10 == newSETT_10) { lstSTATES += "0"; } else { lstSTATES += "1"; }
   if (currSETT_11 == newSETT_11) { lstSTATES += "0"; } else { lstSTATES += "1"; }
   if (currSETT_12 == newSETT_12) { lstSTATES += "0"; } else { lstSTATES += "1"; }
   if (currSETT_13 == newSETT_13) { lstSTATES += "0"; } else { lstSTATES += "1"; }
   // Set the SAVE and CANCEL buttons states
   objSUBMITsettings.disabled = (lstSTATES == "0000000000000" ? true : false);
   newSETT = (lstSTATES == "0000000000000" ? "0" : "1");
   // Check values between CURRENT and NEW
   var lstSTATES = "";
   if (currPARAM_1 == newPARAM_1) { lstSTATES += "0"; } else { lstSTATES += "1"; }
   if (currPARAM_2 == newPARAM_2) { lstSTATES += "0"; } else { lstSTATES += "1"; }
   if (currPARAM_3 == newPARAM_3) { lstSTATES += "0"; } else { lstSTATES += "1"; }
   if (currPARAM_4 == newPARAM_4) { lstSTATES += "0"; } else { lstSTATES += "1"; }
   if (currPARAM_5 == newPARAM_5) { lstSTATES += "0"; } else { lstSTATES += "1"; }
   if (currPARAM_6 == newPARAM_6) { lstSTATES += "0"; } else { lstSTATES += "1"; }
   if (currPARAM_7 == newPARAM_7) { lstSTATES += "0"; } else { lstSTATES += "1"; }
   if (currPARAM_8 == newPARAM_8) { lstSTATES += "0"; } else { lstSTATES += "1"; }
   if (currPARAM_9 == newPARAM_9) { lstSTATES += "0"; } else { lstSTATES += "1"; }
   // Set the SAVE and CANCEL buttons states
   objSUBMITparams.disabled = (lstSTATES == "000000000" ? true : false);
   newPARAM = (lstSTATES == "000000000" ? "0" : "1");
   // Return a dunny value
   return true;
}
//-------------------------------------------------------------------------
</SCRIPT>
<?php } ?>

<!-- ADDITIONAL JAVA CODE GOES HERE -->



<!-- END PAGE CONTENTS AND CODE -->
</body>
</html>
