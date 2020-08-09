<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Solar Tracer</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>


    <style>
        .chart-area {
            width: 640px;
            height: auto;
        }

    </style>

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">


</head>
<script>
    // Visualization API with the 'corechart' package.
    google.charts.load('visualization', { packages: ['corechart'] });
    google.charts.setOnLoadCallback(drawLineChart);
    drawLineChart();
    setInterval(drawLineChart, 50000);
    function drawLineChart() {
        $.ajax({
            url: "http://experiments.ddns.net/pi-solar-tracer/getDataStats.php?q=2",
            dataType: "json",
            type: "GET",
            contentType: "application/json; charset=utf-8",
            success: function (data) {
                var arrPv = [['Hours', 'Volts. (V)','Amps. (A)', 'Watts. (W)']];    // Define an array and assign columns for the chart.

                // Loop through each data and populate the array.
                $.each(data, function (index, value) {
                    arrPv.push([value.timestamp, value.Battery_voltage, value.Battery_charging_current, value.Battery_charging_power]);
                });

                // Set chart Options.
                var options = {
                    title: 'Battery Activiy ',
                    curveType: 'function',
                    legend: { position: 'bottom', textStyle: { color: '#555', fontSize: 14} }  // You can position the legend on 'top' or at the 'bottom'.
                };

                // Create DataTable and add the array to it.
                var figures = google.visualization.arrayToDataTable(arrPv)

                // Define the chart type (LineChart) and the container (a DIV in our case).
                var chart = new google.visualization.LineChart(document.getElementById('chart_line__battery_div'));
                chart.draw(figures, options);      // Draw the chart with Options.
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert('Got an Error on Chart');
            }
        });
    }
</script>

<script>
    // Visualization API with the 'corechart' package.
    google.charts.load('visualization', { packages: ['corechart'] });
    google.charts.setOnLoadCallback(drawLineChart);
    drawLineChart();
    setInterval(drawLineChart, 50000);
    function drawLineChart() {
        $.ajax({
            url: "http://experiments.ddns.net/pi-solar-tracer/getDataStats.php?q=3",
            dataType: "json",
            type: "GET",
            contentType: "application/json; charset=utf-8",
            success: function (data) {
                var arrPv = [['Hours', 'Volts. (V)','Amps. (A)', 'Watts. (W)']];    // Define an array and assign columns for the chart.

                // Loop through each data and populate the array.
                $.each(data, function (index, value) {
                    arrPv.push([value.timestamp, value.Load_voltage, value.Load_current, value.Load_power]);
                });

                // Set chart Options.
                var options = {
                    title: 'Load DC Activiy ',
                    curveType: 'function',
                    legend: { position: 'bottom', textStyle: { color: '#555', fontSize: 14} }  // You can position the legend on 'top' or at the 'bottom'.
                };

                // Create DataTable and add the array to it.
                var figures = google.visualization.arrayToDataTable(arrPv)

                // Define the chart type (LineChart) and the container (a DIV in our case).
                var chart = new google.visualization.LineChart(document.getElementById('chart_line__load_div'));
                chart.draw(figures, options);      // Draw the chart with Options.
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert('Got an Error on Chart');
            }
        });
    }
</script>

<body id="page-top">

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
      <li class="nav-item">
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
      <li class="nav-item active">
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
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow"><?php echo "<p> <font color=blue size='4pt'> Status MPPT Tracer:$connection</p>";?>

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>
          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">
            <div class="topbar-divider d-none d-sm-block"></div>
            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo date('d m Y | H:i:s');?></p></span>
              </a>

            </li>


          </ul>

        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800">Charts</h1>

          <!-- Content Row -->
          <div class="row">

            <div class="col-xl-8 col-lg-7">

              <!-- Area Chart -->
              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Battery Energy Activity</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <div id="chart_line__battery_div" style="width: 700px; height: 320px"></div>
                    </div>
                  <hr>

                </div>
              </div>

              <!-- Bar Chart -->
              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Load Energy Activiy</h6>
                </div>
                <div class="card-body">
                        <div id="chart-container">
                            <div id="chart_line__load_div" style="width: 700px; height: 320px"></div>
                        </div>
                   <hr>
                </div>
              </div>

            </div>


      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>SolarPi <?php echo date("Y");?></span>
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
  <script src="js/demo/chart-bar-demo.js"></script>

  <!-- javascript -->
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/Chart.min.js"></script>
  <script type="text/javascript" src="js/app.js"></script>
  <script type="text/javascript" src="js/linegraphs.js"></script>


</body>

</html>
