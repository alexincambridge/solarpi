<?php

require_once 'PhpEpsolarTracer.php';
$tracer = new PhpEpsolarTracer('/dev/ttyXRUSB0');

//Info device
$tracer->getStatData();

$max_voltage = $tracer->statData[0];
$min_voltage = $tracer->statData[1];

$max_bat_voltage = $tracer->statData[2];
$min_bat_voltage = $tracer->statData[3];

$consume_energy_today = $tracer->statData[4];
$consume_energy_month = $tracer->statData[5];
$consume_energy_year = $tracer->statData[6];
$total_consume_energy = $tracer->statData[7];

$generate_energy_today = $tracer->statData[8];
$generate_energy_month = $tracer->statData[9];
$generate_energy_year = $tracer->statData[10];
$total_generated = $tracer->statData[11];

$carbon_diox = $tracer->statData[12];
$net_battery_current = $tracer->statData[13];
$battery_temperature = $tracer->statData[14];
$ambient_temperature = $tracer->statData[15];


?>

<!DOCTYPE html>
<html lang="en">

<head>

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

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>SB Admin 2 - Tables</title>

  <!-- Custom fonts for this template -->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

  <!-- Custom styles for this page -->
  <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">


    <style>
        table {
             font-family: arial, sans-serif;
             border-collapse: collapse;
             width: 99%;
         }

        td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 5px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }
    </style>
</head>

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
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow"><?php echo "<p> <font color=SlateBlue size='4pt'> Status MPPT Tracer:$connection</p>";?>

                <!-- Sidebar Toggle (Topbar) -->
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>

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
          <h1 class="h3 mb-2 text-gray-800">Tables</h1>

          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Statistic informacion Device Table</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table>
                    <tr>

                    <tr>Statistic</tr>

                          <td>Max input voltage today:  <?php echo $max_voltage ?>V</td><tr>
                          <td>Min input voltage today: <?php echo $min_voltage ?>V</td><tr>
                          <td>Max battery voltage today: <?php echo $max_bat_voltage ?>V</td><tr>
                          <td>Min battery voltage today:<?php echo $min_bat_voltage ?>V</td><tr>

                          <td>Consumed energy today: <?php echo $consume_energy_today ?>KWH</td><tr>
                          <td>Consumed energy this month: <?php echo $consume_energy_month ?>KWH</td><tr>
                          <td>Consumed energy this year:<?php echo $consume_energy_year ?>KWH</td><tr>
                          <td>Total consumed energy:<?php echo $total_consume_energy ?>KWH</td><tr>

                          <td>Generated energy today <?php echo $generate_energy_today ?>KWH</td><tr>
                          <td>Generated energy this month: <?php echo $generate_energy_month ?>KWH</td><tr>
                          <td>Generated energy this year:<?php echo $generate_energy_year ?>KWH</td><tr>

                          <td>Total generated energy: <?php echo $total_generated ?>KWH</td><tr>
                          <td>Carbon dioxide reduction: <?php echo $carbon_diox ?>T</td><tr>
                          <td>Net battery current: <?php echo $net_battery_current ?>A</td><tr>
                          <td>Battery temperature: <?php echo $battery_temperature ?>C</td><tr>
                          <td>Ambient temperature: <?php echo $ambient_temperature ?>C</td><tr>
                      </tr>
                  </table>
                  </tbody>
                </table>
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

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="js/demo/datatables-demo.js"></script>

</body>

</html>
