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
    $connection = "Connected";
    $connection_bgcolor = "lime";
} else {
    $connection = "Disconnected";
    $connection_bgcolor = "red";
}

// Get Real Time Data
if ($tracer->getRealTimeData()) {
    $tracerstatus_bgcolor = "lime";
    $equipStatus = $tracer->realtimeData[16];
    $chargStatus = 0b11 & ($equipStatus >> 2);
    switch ($chargStatus) {
        case 0: $eStatus = "Not charging";
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
        case 2: $bStatus = "<font color=\"yellow\">Undervolt</font>";
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

//get data for the last 2 weeks
//$ago = time() - 1209600;
//get data for the last 24 hrs
//$ago = time() - 86400;
//get data for the last 48 hrs
$ago = time() - (86400 * 2);

$dsn = "mysql:host=localhost;dbname=Solardata";
$user = "root";
$passwd = "password";

$pdo = new PDO($dsn, $user, $passwd);

//$dbh = new PDO("mysql:host=localhost;dbname=solardata", "databaseusername", "databasepassword");
$sth = $pdo->prepare("select `timestamp`,`PV array voltage`,`PV array current`,`PV array power`,`Battery voltage`,`Battery charging current`,`Battery charging power`,`Load voltage`,`Load current`,`Load power` from stats where `Controller` = 1 and `timestamp` > ? order by `timestamp` asc");
$sth->bindParam(1, $ago);
$sth->execute();

//build the json array
$data = array();
while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
    $data["category"][] = date("H:i", $row["timestamp"]);
    while (list($key, $val) = each($row)) {
        $data[$key][] = $val;
    }
}

unset($data["timestamp"]);

reset($data);
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

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <script src="js/raphael-2.1.4.min.js"></script>
    <script src="js/justgage.js"></script>

<style>
    #g1, #g2, #g3, #g4, #g5, #g6{
        width:80px; height:80px;
        display: inline-block;
        margin: 0em;
    }

</style>


</head>

<body id="page-top">

<script>
    var g1;
    document.addEventListener("DOMContentLoaded", function(event) {
        g1 = new JustGage({
            id: "g1",
            value: 12,
            min: 0,
            max: 50,
            gaugeWidthScale: 0.6,
            counter: true,
            label: "V"

        });

        document.getElementById('g1_refresh').addEventListener('click', function() {
            g1.refresh(getRandomInt(0, 30));
        });
    });
</script>

<script>
    var g2;
    document.addEventListener("DOMContentLoaded", function(event) {
        g2 = new JustGage({
            id: "g2",
            value: 5,
            min: 0,
            max: 30,
            gaugeWidthScale: 0.6,
            counter: true,
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
            value: 14,
            min: 0,
            max: 30,
            gaugeWidthScale: 0.6,
            counter: true,
            label: "W",
        });

        document.getElementById('g3_refresh').addEventListener('click', function() {
            g1.refresh(getRandomInt(0, 20));
        });
    });
</script>

<script>
    var g4;
    document.addEventListener("DOMContentLoaded", function(event) {
        g4 = new JustGage({
            id: "g4",
            value: 12,
            min: 0,
            max: 50,
            symbol: 'V',
            pointer: true,
            pointerOptions: {
                toplength: 8,
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
    var g5;
    document.addEventListener("DOMContentLoaded", function(event) {
        g4 = new JustGage({
            id: "g5",
            value: 5,
            min: 0,
            max: 8,
            symbol: 'A',
            pointer: true,
            pointerOptions: {
                toplength: 8,
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
    var g6;
    document.addEventListener("DOMContentLoaded", function(event) {
        g6 = new JustGage({
            id: "g6",
            value: 12,
            min: 0,
            max: 50,
            symbol: 'W',
            pointer: true,
            pointerOptions: {
                toplength: 8,
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
                toplength: 8,
                bottomlength: -20,
                bottomwidth: 6,
                color: '#8e8e93'
            },
            gaugeWidthScale: 0.6,
            counter: true,
            label: "Ah"


        });

        document.getElementById('g7_refresh').addEventListener('click', function() {
            g1.refresh(getRandomInt(0, 30));
        });
    });

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
        Options
      </div>

      <!-- Nav Item - Charts -->
      <li class="nav-item">
        <a class="nav-link" href="charts.html">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Charts</span></a>
      </li>

      <!-- Nav Item - Tables -->
      <li class="nav-item">
        <a class="nav-link" href="tables.html">
          <i class="fas fa-fw fa-table"></i>
          <span>Tables</span></a>
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
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>


          <ul class="navbar-nav ml-auto">


            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo "The time is " . date("h:i:sa");?></span>
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Profile
                </a>
                <a class="dropdown-item" href="#">
                  <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                  Settings
                </a>
                <a class="dropdown-item" href="#">
                  <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                  Activity Log
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
                </a>
              </div>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
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
                      <div class="h5 mb-0 font-weight-bold text-gray-800">Battery Activity</div>
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
                      <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">KiloWats Generated</div>
                        <div class="col-auto">
                            <i class="fas fa-charging-station fa-2x text-gray-300"></i>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">18Kws</div>
                        <hr>
                             <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Temperature</div>
                            <i class="fas fa-thermometer-half fa-2x text-gray-300"></i>
                               <div class="h5 mb-0 font-weight-bold text-gray-800">18ºC</div>


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
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                      <div class="dropdown-header">Dropdown Header:</div>
                      <a class="dropdown-item" href="#">Action</a>
                      <a class="dropdown-item" href="#">Another action</a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                  </div>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                  <div class="chart-area">
                    <canvas id="myAreaChart"></canvas>
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
                      <a class="dropdown-item" href="#">Another action</a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                  </div>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                  <div class="chart-pie pt-4 pb-2">
                    <canvas id="myPieChart"></canvas>
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
                  <h6 class="m-0 font-weight-bold text-primary">Projects</h6>
                </div>
                <div class="card-body">
                  <h4 class="small font-weight-bold">Server Migration <span class="float-right">20%</span></h4>
                  <div class="progress mb-4">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                  <h4 class="small font-weight-bold">Sales Tracking <span class="float-right">40%</span></h4>
                  <div class="progress mb-4">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                  <h4 class="small font-weight-bold">Customer Database <span class="float-right">60%</span></h4>
                  <div class="progress mb-4">
                    <div class="progress-bar" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                  <h4 class="small font-weight-bold">Payout Details <span class="float-right">80%</span></h4>
                  <div class="progress mb-4">
                    <div class="progress-bar bg-info" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                  <h4 class="small font-weight-bold">Account Setup <span class="float-right">Complete!</span></h4>
                  <div class="progress">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
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
            <span>Copyright &copy; 2020</span>
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
