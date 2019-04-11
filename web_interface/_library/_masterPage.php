<?php
//---------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------
// Function to create bage headers and menue
function PAGE_Open($pageTitle, $myOnLoadFunction) {
?>
   <title><?php echo $pageTitle ?> - Solar Pi</title>
   <!-- Mobile viewport optimized: h5bp.com/viewport -->
   <meta name="viewport" content="width=device-width">
   <link rel="stylesheet" href="/_css/bootstrap.min.css">
   <link rel="stylesheet" href="/_css/font-awesome.min.css">
   <link rel="stylesheet" href="/_css/weather-icons.min.css">
   <link rel="stylesheet" href="/_css/sb-admin-2.css">
</head>
<body onLoad="<?php echo $myOnLoadFunction; ?>">

<div id="wrapper">
   <!-- Navigation -->
   <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
      <div class="navbar-header">
         <button type="button" value="ciao" class="navbar-toggle" id="button-toggle">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
         </button>
         <a class="navbar-brand" href="/">Solar Pi</a>
      </div>
      <div class="navbar-default sidebar" role="navigation">
         <div class="sidebar-nav navbar-collapse collapse" id="menu-entries">
            <ul class="nav" id="side-menu">
               <li><a id="dashboard" href="/"><iclass="fa fa-dashboard fa-fw"></i>Dashboard</a></li>
               <li>
                  <a href="#"><i class="fa fa-bar-chart-o fa-fw"></i> Grafici Energetici<span class="fa arrow"></span></a>
                  <ul id='chart_energy' class="nav nav-second-level">
                     <li><a id="daily_energycharts" href="/energy_charts/daily.php">Giornalieri</a></li>
                     <li><a id="weekly_energycharts" href="/energy_charts/weekly.php">Settimanali</a></li>
                     <li><a id="monthly_energycharts" href="/energy_charts/monthly.php">Mensili</a></li>
                     <li><a id="yearly_energycharts" href="/energy_charts/yearly.php">Annuali</a></li>
                  </ul>
               </li>
               <li>
                  <a href="#"><i class="fa fa-bar-chart-o fa-fw"></i> Grafici Sistema<span class="fa arrow"></span></a>
                  <ul id='chart_system' class="nav nav-second-level">
                     <li><a id="daily_systemcharts" href="/system_charts/daily.php">Giornalieri</a></li>
                     <li><a id="weekly_systemcharts" href="/system_charts/weekly.php">Settimanali</a></li>
                     <li><a id="monthly_systemcharts" href="/system_charts/monthly.php">Mensili</a></li>
                     <li><a id="yearly_systemcharts" href="/system_charts/yearly.php">Annuali</a></li>
                  </ul>
               </li>
               <li><a id="savings" href="/savings.php"><i class="fa fa-money fa-fw"></i> Risparmio</a></li>
               <li><a id="weather" href="/weather.php"><i class="fa fa-thermometer fa-fw"></i> Meteo</a></li>
               <li><a id="tables" href="/tables.php"><i class="fa fa-table fa-fw"></i> Tabelle</a></li>
               <li><a id="events" href="/events.php"><i class="fa fa-exclamation-triangle fa-fw"></i> Eventi di Sistema</a></li>
               <li><a id="sysparams" href="/systemparameters.php"><i class="fa fa-wrench fa-fw"></i> Parametri del Sistema</a></li>
               <li><a id="about" href="/about.php"><i class="fa fa-question fa-fw"></i> About</a></li>
            </ul>
         </div>
      </div>
   </nav>
   <div id="page-wrapper">
      <div class="row">
         <div class="col-lg-12">
            <h1 class="page-header"><?php echo $pageTitle ?><span style="float:right;font-size:16px;margin-top:20px;" id="pageTitleStatus">&nbsp;</span></h1>
         </div>
      </div>
<?php
}
//---------------------------------------------------------------------------------------------
// Function to create page ending
function PAGE_Close($selectedMenu) {
?>
   </div>
   <!-- /#page-wrapper -->
</div>
<!-- /#wrapper -->

<script type="application/javascript" src="/_js/jquery.min.js"></script>
<script type="application/javascript" src="/_js/highcharts.js"></script>
<script type="application/javascript" src="/_js/highcharts-more.js"></script>
<script type="application/javascript" src="/_js/highcharts_language.js"></script>
<script type="application/javascript" src="/_js/metisMenu.min.js"></script>
<script type="application/javascript" src="/_js/sb-admin-2.js"></script>
<script>
   $('#side-menu').find('a').each(function (i) { $(this).removeClass('active'); });
   $('#<?php echo $selectedMenu ?>').addClass("active");
   $("#button-toggle").click(function(){ $("#menu-entries").toggleClass("collapse"); });
</script>
<?php
}
//---------------------------------------------------------------------------------------------
function PAGE_Panel($panelICON,$panelVALUE,$panelNOTE,$panelCOLOR,$summaryLINK,$summaryTEXT,$summaryVALUE) {
?>
   <div class="col-lg-6 col-md-6">
      <div class="panel <?php echo $panelCOLOR ?> panel-primary">
         <div class="panel-heading">
            <div class="row">
               <div class="col-xs-3"><i class="<?php echo $panelICON ?> fa-5x"></i></div>
               <div class="col-xs-9 text-right">
                  <div class="huge" ID="<?php echo $panelVALUE ?>">n/a</div>
                  <div><?php echo $panelNOTE ?></div>
               </div>
            </div>
         </div>
         <div class="panel-footer">
            <a href="<?php echo $summaryLINK ?>">
               <span class="pull-left"><?php echo $summaryTEXT ?></span>
               <span class="pull-right" ID="<?php echo $summaryVALUE ?>">n/a</span>
               <div class="clearfix"></div>
            </a>
         </div>
      </div>
   </div>
<?php
}
//---------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------
