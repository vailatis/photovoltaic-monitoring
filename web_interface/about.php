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




<?php PAGE_Open("About", ""); ?>

<div class="row">
   <div class="col-lg-12">
      Solar Pi is a Raspberry Pi based, Flask powered photovoltaic monitor. The background of this project can
      be found at <a href="http://blog.tafkas.net/2014/07/03/a-raspberry-pi-photovoltaic-monitoring-solution/">
      http://blog.tafkas.net</a>
  </div>
</div>

<?php PAGE_Close("about"); ?>

<!-- ADDITIONAL JAVA CODE GOES HERE -->
<!-- ADDITIONAL JAVA CODE GOES HERE -->



<!-- END PAGE CONTENTS AND CODE -->
</body>
</html>
