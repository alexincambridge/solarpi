<?php

/*
 * PHP EpSolar Tracer Class (PhpEpsolarTracer) v0.9
 *
 * Library for communicating with
 * Epsolar/Epever Tracer BN MPPT Solar Charger Controller
 *
 * THIS PROGRAM COMES WITH ABSOLUTELY NO WARRANTIES !
 * USE IT AT YOUR OWN RISKS !
 *
 * Copyright (C) 2016 under GPL v. 2 license
 * 13 March 2016
 *
 * @author Luca Soltoggio
 * http://www.arduinoelettronica.com/
 * https://arduinoelectronics.wordpress.com/
 *
 * This is an example on how to use the library
 * It creates a web page with tracer data
 *
 * The version below is a highly modified version of that referred to by the headers above, the origninal can be found at https://github.com/toggio/PhpEpsolarTracer
 */

require_once 'PhpEpsolarTracer.php';
$tracer = new PhpEpsolarTracer('/dev/ttyXRUSB0');

$tracerstatus_bgcolor = "#dedede";
// $ecolor = "black";
// $battSoc = 0;
// Get Info and check if is connected
if ($tracer->getInfoData()) {
    $connection = "<font color=\"green\">Connected </font><img src='images/usb-conectado.png'>";
} else {
    $connection = "<font color=\"red\">Disconnected </font><img src='images/usb-desconectado.png'>";

}

// Get Real Time Data
if ($tracer->getRealTimeData()) {
    $tracerstatus_bgcolor = "green";
    $equipStatus = $tracer->realtimeData[16];
    $chargStatus = 0b11 & ($equipStatus >> 2);
    switch ($chargStatus) {
        case 0: $eStatus = "<font color=\"red\">Not charging</font>";
            break;
        case 1: $eStatus = "Float (13.8V)";
            break;
        case 2: $eStatus = "Boost (14.4V)";
            break;
        case 3: $eStatus = "Equalization (14.6V)";
            break;
    };
    if ($equipStatus >> 4) {
        $eStatus = "<font color=\"red\">FAULT</font>";
        $tracerstatus_bgcolor = "red";
    }

    $battStatus = $tracer->realtimeData[15];
    $battLevel = 0b1111 & $battStatus;
    switch ($battLevel) {
        case 0: $bStatus = "Normal";
            break;
        case 1: $bStatus = "<font color=\"red\">Overvolt</font>";
            break;
        case 2: $bStatus = "<font color=\"orange\">Undervolt</font>";
            break;
        case 3: $bStatus = "<font color=\"red\">Low volt disconnect</font>";
            break;
        case 4: {
            $bStatus = "<font color=\"red\">FAULT</font>";
            $tracerstatus_bgcolor = "red";
            break;
        }
    }

    $battSoc = $tracer->realtimeData[12];
}

//energy generated total
$tracer->getStatData();
//total energy consumed
$tracer->statData[7];
//total energy generated
$tracer->statData[9];

?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Solar Tracer Dashboard</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>



    <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <script src="js/raphael-2.1.4.min.js"></script>
    <script src="js/justgage.js"></script>
    <script src="js/chartjs-2.9.3/canvasjs.min.js.js"></script>
    <script src="js/chartjs-2.9.3/jquery.canvasjs.min.js"></script>

<style>
    #g1, #g2, #g3, #g4, #g5, #g6, #g7, #g8, #g9, #g10{
        width:80px; height:80px;
        display: inline-block;
        margin: 0em;
    }

</style>


</head>

<body id="page-top">

<!-- Current time on top bar-->
<script type="text/javascript">
    var timestamp = '<?=time();?>';
    function updateTime(){
        $('#time').html(Date(timestamp));
        timestamp++;
    }
    $(function(){
        setInterval(updateTime, 1000);
    });
</script>


<!--Solar Panels info  g1 V, g2 A, g3 W #3300 [0]
//Voltaje x Corriente = Potencia
//1V x 1A = 1 W

//V x A = W
//A =  W / V
//100W/18V  = 5.55 amperios-->

<script>
    var g1;
    document.addEventListener("DOMContentLoaded", function(event) {
        g1 = new JustGage({
            id: "g1",
            decimals: true,
            value: "<?php echo $tracer->realtimeData[0]; ?>",
            symbol: 'V',
            min: 0,
            max: 24,
            counter: true,
	    pointer: true,
            pointerOptions: {
                toplength: 2,
                bottomlength: -20,
                bottomwidth: 6,
                color: '#8e8e93'
            },

            label: "V",
            gaugeWidthScale: 0.6,
        });

  });

</script>

<script>
    var g2;
    document.addEventListener("DOMContentLoaded", function(event) {
        g2 = new JustGage({
            id: "g2",
            decimals: 2,
            value: "<?php echo $tracer->realtimeData[1]; ?>",
            symbol: 'A',
            min: 0,
            max: 10,
            gaugeWidthScale: 0.6,
            counter: true,
	    pointer: true,
            pointerOptions: {
                toplength: 2,
                bottomlength: -20,
                bottomwidth: 6,
                color: '#8e8e93'
            },
            label: "A",

        });

        document.getElementById('g2_refresh').addEventListener('click', function() {
            g1.refresh(getRandomInt(0, 30));
        });
    });
</script>

<script>
    var g3;
    document.addEventListener("DOMContentLoaded", function(event) {
        g2 = new JustGage({
            id: "g3",
            decimals: 2,
            value: "<?php echo $tracer->realtimeData[2]; ?>",
            symbol: 'W',
            min: 0,
            max: 200,
            gaugeWidthScale: 0.6,
	    counter: true,
            pointer: true,
            pointerOptions: {
                toplength: 2,
                bottomlength: -20,
                bottomwidth: 6,
                color: '#8e8e93'
            },
            label: "W",
        });

        document.getElementById('g3_refresh').addEventListener('click', function() {
            g1.refresh(getRandomInt(0, 20));
        });
    });
</script>
<!-- Battery info  g4 V, g5 A, g6 W -->
<script>
    //battery voltage 3
    var g4;
    document.addEventListener("DOMContentLoaded", function(event) {
        g4 = new JustGage({
            id: "g4",
            decimals: true,
            value: "<?php echo $tracer->realtimeData[3]; ?>",
            min: 0,
            max: 24,
            symbol: 'V',
            pointer: true,
            pointerOptions: {
                toplength: 2,
                bottomlength: -20,
                bottomwidth: 6,
                color: '#8e8e93'
            },
            gaugeWidthScale: 0.6,
            counter: true,
            label: "V"

        });

        document.getElementById('g4_refresh').addEventListener('click', function() {
            g1.refresh(getRandomInt(0, 30));
        });
    });
</script>
<script>
    // battery current
    var g5;
    document.addEventListener("DOMContentLoaded", function(event) {
        g5 = new JustGage({
            id: "g5",
            decimals: 2,
            value: "<?php echo $tracer->realtimeData[4]; ?>",
            min: 0,
            max: 10,
            symbol: 'A',
            pointer: true,
            pointerOptions: {
                toplength: 2,
                bottomlength: -20,
                bottomwidth: 6,
                color: '#8e8e93'
            },
            gaugeWidthScale: 0.6,
            counter: true,
            label: "A"

        });

        document.getElementById('g5_refresh').addEventListener('click', function() {
            g1.refresh(getRandomInt(0, 30));
        });
    });
</script>
<script>
    //Battery power [5]
    var g6;
    document.addEventListener("DOMContentLoaded", function(event) {
        g6 = new JustGage({
            id: "g6",
            decimals: 2,
            value: "<?php echo $tracer->realtimeData[5]; ?>",
            min: 0,
            max: 100,
            symbol: 'W',
            pointer: true,
            pointerOptions: {
                toplength: 2,
                bottomlength: -20,
                bottomwidth: 6,
                color: '#8e8e93'
            },
            gaugeWidthScale: 0.6,
            counter: true,
            label: "W"


        });

        document.getElementById('g6_refresh').addEventListener('click', function() {
            g1.refresh(getRandomInt(0, 30));
        });
    });
</script>
<!-- DC Load information -->
<script>
    //DC Load V
    var g6;
    document.addEventListener("DOMContentLoaded", function(event) {
        g6 = new JustGage({
            id: "g8",
            decimals: true,
            value: "<?php echo $tracer->realtimeData[6]; ?>",
            min: 0,
            max: 24,
            symbol: 'V',
            pointer: true,
            pointerOptions: {
                toplength: 2,
                bottomlength: -20,
                bottomwidth: 6,
                color: '#8e8e93'
            },
            gaugeWidthScale: 0.6,
            counter: true,
            label: "V"


        });

        document.getElementById('g8_refresh').addEventListener('click', function() {
            g1.refresh(getRandomInt(0, 30));
        });
    });
</script>
<script>
    //DC load Current Amper [7]
    var g9;
    document.addEventListener("DOMContentLoaded", function(event) {
        g6 = new JustGage({
            id: "g9",
            decimals: 2,
            value: "<?php echo $tracer->realtimeData[7]; ?>",
            min: 0,
            max: 8,
            symbol: 'A',
            pointer: true,
            pointerOptions: {
                toplength: 2,
                bottomlength: -20,
                bottomwidth: 6,
                color: '#8e8e93'
            },
            gaugeWidthScale: 0.6,
            counter: true,
            label: "A"


        });

        document.getElementById('g9_refresh').addEventListener('click', function() {
            g1.refresh(getRandomInt(0, 30));
        });
    });
</script>

<script>
    //DC  power
    var g10;
    document.addEventListener("DOMContentLoaded", function(event) {
        g6 = new JustGage({
            id: "g10",
            decimals: 2,
            value: "<?php echo $tracer->realtimeData[8]; ?>",
            min: 0,
            max: 100,
            symbol: 'W',
            pointer: true,
            pointerOptions: {
                toplength: 2,
                bottomlength: -20,
                bottomwidth: 6,
                color: '#8e8e93'
            },
            gaugeWidthScale: 0.6,
            counter: true,
            label: "W"


        });

        document.getElementById('g10_refresh').addEventListener('click', function() {
            g1.refresh(getRandomInt(0, 30));
        });
    });
</script>
<!-- Battery status % Ah remain -->
<script>
    var g7;
    document.addEventListener("DOMContentLoaded", function(event) {
        g7 = new JustGage({
            id: "g7",
            value: <?php echo $battSoc; ?>,
            min: 0,
            max: 100,
            symbol: '%',
            pointer: true,
            pointerOptions: {
                toplength: 2,
                bottomlength: 2,
                bottomwidth: 6,
                color: '#8e8e93'
            },
            levelColors: [
                "#F50000",
                "#ffcc00",
                "#71D506"
            ],
            gaugeWidthScale: 0.6,
            counter: true,
            label: "Ah"


        });

    });

</script>

<script>
    window.onload = function () {

        var chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            title:{
                text: "Daily High Temperature at Different Beaches"
            },
            axisX: {
                valueFormatString: "DD MMM,YY"
            },
            axisY: {
                title: "Temperature (in °C)",
                includeZero: false,
                suffix: " °C"
            },
            legend:{
                cursor: "pointer",
                fontSize: 16,
                itemclick: toggleDataSeries
            },
            toolTip:{
                shared: true
            },
            data: [{
                name: "Myrtle Beach",
                type: "spline",
                yValueFormatString: "#0.## °C",
                showInLegend: true,
                dataPoints: [
                    { x: new Date(2017,6,24), y: 31 },
                    { x: new Date(2017,6,25), y: 31 },
                    { x: new Date(2017,6,26), y: 29 },
                    { x: new Date(2017,6,27), y: 29 },
                    { x: new Date(2017,6,28), y: 31 },
                    { x: new Date(2017,6,29), y: 30 },
                    { x: new Date(2017,6,30), y: 29 }
                ]
            },
                {
                    name: "Martha Vineyard",
                    type: "spline",
                    yValueFormatString: "#0.## °C",
                    showInLegend: true,
                    dataPoints: [
                        { x: new Date(2017,6,24), y: 20 },
                        { x: new Date(2017,6,25), y: 20 },
                        { x: new Date(2017,6,26), y: 25 },
                        { x: new Date(2017,6,27), y: 25 },
                        { x: new Date(2017,6,28), y: 25 },
                        { x: new Date(2017,6,29), y: 25 },
                        { x: new Date(2017,6,30), y: 25 }
                    ]
                },
                {
                    name: "Nantucket",
                    type: "spline",
                    yValueFormatString: "#0.## °C",
                    showInLegend: true,
                    dataPoints: [
                        { x: new Date(2017,6,24), y: 22 },
                        { x: new Date(2017,6,25), y: 19 },
                        { x: new Date(2017,6,26), y: 23 },
                        { x: new Date(2017,6,27), y: 24 },
                        { x: new Date(2017,6,28), y: 24 },
                        { x: new Date(2017,6,29), y: 23 },
                        { x: new Date(2017,6,30), y: 23 }
                    ]
                }]
        });
        chart.render();

        function toggleDataSeries(e){
            if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                e.dataSeries.visible = false;
            }
            else{
                e.dataSeries.visible = true;
            }
            chart.render();
        }

    }
</script>

<script>

// Visualization API with the 'corechart' package.
    google.charts.load('visualization', { packages: ['corechart'] });
    google.charts.setOnLoadCallback(drawLineChart);
    drawLineChart();
//check every 60 sec
    setInterval(drawLineChart, 600000);
    function drawLineChart() {
        $.ajax({
            url: "http://experiments.ddns.net/pi-solar-tracer/getDataStats.php?q=1",
            dataType: "json",
            type: "GET",
            contentType: "application/json; charset=utf-8",
            success: function (data) {
                var arrPv = [['Hours', 'Volts. (V)','Amp. (A)', 'Watts. (W)']];    // Define an array and assign columns for the chart.

                // Loop through each data and populate the array.
                $.each(data, function (index, value) {
                    arrPv.push([value.timestamp, value.PV_array_voltage, value.PV_array_current, value.PV_array_power]);
                });

                // Set chart Options.
                var options = {
                    title: 'Daily Energy on Solar Panel',
                    curveType: 'function',
                    legend: { position: 'bottom', textStyle: { color: '#555', fontSize: 14} }  // You can position the legend on 'top' or at the 'bottom'.
                };

                // Create DataTable and add the array to it.
                var figures = google.visualization.arrayToDataTable(arrPv)

                // Define the chart type (LineChart) and the container (a DIV in our case).
                var chart = new google.visualization.LineChart(document.getElementById('chart_line_div'));
                chart.draw(figures, options);      // Draw the chart with Options.
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
               // alert('Got an Error on Chart');
            }
        });
    }
</script>

<script>
    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(drawBarColors);

    function drawBarColors() {
        var data = google.visualization.arrayToDataTable([
            ['Current', '+ ve', '- ve'],
            ['+ ve, - ve', <?php echo $tracer->realtimeData[4];?>,<?php echo $tracer->realtimeData[7];?>]

        ]);

        var options = {
            title: 'Net Current on Battery',
            chartArea: {width: '50%'},
            colors: ['#b0120a', '#ffab91'],
            hAxis: {
                title: '+ ve / - ve = to Battery',
                minValue: 0
            },
            vAxis: {
                title: 'VE'
            }
        };
        var chart = new google.visualization.BarChart(document.getElementById('chart_net_div'));
        chart.draw(data, options);
    }

</script>

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
        <div class="sidebar-brand-text mx-3">Solar Tracer <sup>2.0</sup></div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item active">
        <a class="nav-link" href="index.php">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        options
      </div>

      <!-- Nav Item - Charts -->
      <li class="nav-item">
        <a class="nav-link" href="charts.php">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Charts</span></a>
      </li>

      <!-- Nav Item - Tables -->
      <li class="nav-item">
        <a class="nav-link" href="tables.php">
          <i class="fas fa-fw fa-table"></i>
          <span>Tables</span></a>
      </li>

        <!-- Nav Item - Info -->
        <li class="nav-item">
            <a class="nav-link" href="info.php">
                <i class="fas fa-fw fa-info"></i>
                <span>Info</span></a>
        </li>

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow"><?php echo "<p> <font color=blue size='4pt'> Status MPPT Tracer: $connection</p>";?>

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>

          </button>

          <ul class="navbar-nav ml-auto">
            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><p id="time"></p></span>
              </a>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard <?php
		//day or night time request
           if ($tracer->getDiscreteData()) {

               $day_night = $tracer->discreteData[1];
           if ($day_night == 0){
               echo "<img src='images/icon-sol.png'>";
               }else {
               echo "<img src='images/icon-luna.png'>";
           }
          }

          ?>
                <?php include "./php/tracer_model_dectect.php" ?>
        </h1>

          </div>

          <!-- Content Row -->
          <div class="row">
            <!-- Metemos Gauges aqui -->
             <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Solar Panel Activity</div>
                        <div class="container">
                            <div id="g1" class="gauge"></div>
                            <div id="g2" class="gauge"></div>
                            <div id="g3" class="gauge"></div>
                            </div>
                        <br>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">Solar Activity</div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-solar-panel fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Battery Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Battery Activity</div>
                        <div class="container">
                        <div id="g4" class="gauge"></div>
                        <div id="g5" class="gauge"></div>
                        <div id="g6" class="gauge"></div>
                        </div>
                        <br>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">Battery Activity</div><?php echo $bStatus; ?>
                        <hr>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">Equipment Status</div><?php echo $eStatus; ?>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-car-battery fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

              <!-- Battery Example -->
              <div class="col-xl-3 col-md-6 mb-4">
                  <div class="card border-left-success shadow h-100 py-2">
                      <div class="card-body">
                          <div class="row no-gutters align-items-center">
                              <div class="col mr-2">
                                  <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Battery SOC</div>
                                  <div class="container">
                                      <div id="g7" class="gauge"></div>
                                  </div>
                                  <div class="h5 mb-0 font-weight-bold text-gray-800">Battery Remain Capacity</div>
                              </div>
                              <div class="col-auto">
                                  <i class="fas fa-car-battery fa-2x text-gray-300"></i>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>

            <!-- Pending Requests Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Energy Generated</div>
                        <div class="col-auto">
                            <i class="fas fa-charging-station fa-2x text-gray-300"></i>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $tracer->statData[10]; ?> Kwh</div>
                        <hr>
			<div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Energy Consumed</div>
                        <div class="col-auto">
                            <i class="fas fa-charging-station fa-2x text-gray-300"></i>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $tracer->statData[7]; ?> Kwh</div>
			<hr>
				<div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Temperature</div>
                            <i class="fas fa-thermometer-half fa-2x text-gray-300"></i>
                               <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $tracer->realtimeData[10];?> º C</div>

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

          <!-- Content Row -->

          <div class="row">

	<!-- Area Chart -->
            <div class="col-xl-8 col-lg-7">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Energy Produced</h6>
                  <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                  </div>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                  <div class="chart-area">
                      <div>
                          <div id="chart_line_div" style="width: 700px; height: 320px;"></div>
                      </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Pie Chart -->
            <div class="col-xl-4 col-lg-5">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">DC Load Information</h6>
                  <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                      <div class="dropdown-header">Dropdown Header:</div>
                      <a class="dropdown-item" href="#">Action</a>
                      <a class="dropdown-item" href="#">Another accion</a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                  </div>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                  <div class="chart-pie pt-4 pb-2">
                      <div class="h5 mb-0 font-weight-bold text-gray-800">DC Load Information</div>
                      <div class="container">
                          <div id="g8" class="gauge"></div>
                          <div id="g9" class="gauge"></div>
                          <div id="g10" class="gauge"></div>
                      </div>
          </div>
                  </div>
                  <div class="mt-4 text-center small">
                    <span class="mr-2">
                      <i class="fas fa-circle text-primary"></i> Load Current (A)
                    </span>
                    <span class="mr-2">
                      <i class="fas fa-circle text-success"></i> Load Voltage (V)
                    </span>
                    <span class="mr-2">
                      <i class="fas fa-circle text-info"></i> Load Power (W)
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Content Row -->
          <div class="row">

            <!-- Content Column -->
            <div class="col-lg-6 mb-4">

              <!-- Project Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Net Energy (A) Produced and Consumed</h6>
                </div>
                  <div class="container">
                  <div class="card-body">
                      <div id="chart_net_div" style="width: 400px; height: 250px;"></div>
                </div>
                </div>
              </div>
             </div>
          </div>
        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>SolarPi <?php echo date("Y");?><</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="login.html">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="vendor/chart.js/Chart.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="js/demo/chart-area-demo.js"></script>
  <script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>
