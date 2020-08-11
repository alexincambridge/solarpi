<?php

require_once 'PhpEpsolarTracer.php';
$tracer = new PhpEpsolarTracer('/dev/ttyXRUSB0');


//do this first so we can see the result in the collected data
if ($_GET["load"] == 'on') {
    $tracer->setLoadOn();
}
if ($_GET["load"] == 'off') {
    $tracer->setLoadOff();
}


//Info device
$tracer->getRatedData();

$voltage = $tracer->ratedData[0];;
$current = $tracer->ratedData[1];
$power = $tracer->ratedData[2];
$batt_voltage = $tracer->ratedData[3];
$rate_charg_current = $tracer->ratedData[4];
$rate_charg_power = $tracer->ratedData[5];
$charg_mode = $tracer->ratedData[6];
$rate_load_current = $tracer->ratedData[7];


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
            width: 100%;
            border-collapse: collapse;
        }

        table, td, th {
            border: 1px solid black;
            padding: 5px;
        }

        th {text-align: left;}
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
                <h1 class="h3 mb-2 text-gray-800">Information about device in operation</h1>

                <!-- DataTales Example -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Info Device Table</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table>
                                <tr>
                                    <img src="images/tracer.png" alt="Device" width="160" height="120">
                                    <th>Model: <?php include "./php/tracer_model_dectect.php" ?>
                                    </th>

                                </tr>
                                <tr>
                                    <td>PV Volt:<?php echo $voltage ?>V</td>
                                    <td>PV Current:<?php echo $current ?>A</td>
                                    <td>PV Power:<?php echo $power ?>W</td>
                                </tr>
                                <tr>
                                    <td>Battery Voltage:<?php echo $batt_voltage ?>V</td>
                                    <td>Charge Current:<?php echo $rate_charg_current ?>A</td>
                                    <td>Charge Power:<?php echo $rate_charg_power ?>W</td>
                                </tr>
                                <tr>
                                    <td>Charge Mode:<?php echo $charg_mode ?></td>
                                    <td>Load DC:<?php echo $rate_load_current ?>A</td>
                                    <td></td>
                                </tr>
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
                    <span>SolarPi 2020</span>
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
