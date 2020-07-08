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
        <script type="text/javascript" src="./fusioncharts/fusioncharts.js"></script>
        <script type="text/javascript" src="fusioncharts/fusioncharts.charts.js"></script>
        <script type="text/javascript" src="fusioncharts/fusioncharts.widgets.js"></script>
        <script type="text/javascript" src="fusioncharts/themes/fusioncharts.theme.fint.js"></script>



        <script type="text/javascript">
                    FusionCharts.ready(function () {
                    var fusioncharts = new FusionCharts({
                    type: 'zoomlinedy',
                            renderAt: 'chart',
                            width: '800',
                            height: '600',
                            dataFormat: 'json',
                            dataSource: {
                            "chart": {
                            "caption": "Performance History",
                                    "pYAxisname": "Value",
                                    "sYAxisname": "PV Array Voltage (V)",
                                    "xAxisname": "Time",
                                    "pYAxisMinValue":"0",
                                    "pYAxisMaxValue":"15",
                                    "sYAxisMaxValue": "100",
                                    "sYAxisMinValue": "0",
                                    "lineThickness": "1",
                                    "compactdatamode": "1",
                                    "dataseparator": "|",
                                    "labelHeight": "30",
                                    "theme": "fint"
                            },
                                    "categories": [{
                                    "category": "<?php
echo implode('|', $data["category"]);
unset($data["category"]);
reset($data);
?>"
                                    }],
<?php
$i = 1;
echo '"dataset":[';
while (list ($key, $val) = each($data)) {


    echo '{"seriesname": "' . $key . '",';
    if (stripos($key, 'PV array voltage') !== FALSE) {
        echo '"parentYAxis": "S",';
    } else {
        echo '"parentYAxis": "P",';
    }
    echo '"data": "' . implode('|', $val) . '"';
    echo "}";
    if ($i != count($data)) {
        echo ",";
    }

    $i++;
}
?>

                            ]
                            }
                    }
                    );
                            fusioncharts.render();
                    });</script>


        <script type="text/javascript">
                    FusionCharts.ready(function () {
                    var fusioncharts = new FusionCharts({
                    type: 'angulargauge',
                            renderAt: 'currentflow',
                            width: '400',
                            height: '250',
                            dataFormat: 'json',
                            dataSource: {
                            "chart": {
                            "caption": "Nett Current (A)",
                                    "subcaption": "-ve = from battery | +ve = to battery ",
                                    "lowerLimit": "-30",
                                    "upperLimit": "+30",
                                    "theme": "fint",
                                    "showValue": "1",
                                    "valueBelowPivot": "1",
                                    "majorTMNumber": "7",
                                    "minorTMNumber": "9",
                            },
                                    "colorRange": {
                                    "color": [{
                                    "minValue": "-30",
                                            "maxValue": "0",
                                            "code": "#e44a00"
                                    }, {
                                    "minValue": "0.001",
                                            "maxValue": "30",
                                            "code": "#6baa01"
                                    }]
                                    },
                                    "dials": {
                                    "dial": [{
                                    "value": "<?php echo $tracer->realtimeData[4] - $tracer->realtimeData[7]; ?>"
                                    }]
                                    }
                            }
                    }
                    );
                            fusioncharts.render();
                    });</script>

        <script type="text/javascript">
                    FusionCharts.ready(function () {
                    var fusioncharts = new FusionCharts({
                    type: 'angulargauge',
                            renderAt: 'PV voltage',
                            width: '300',
                            height: '200',
                            dataFormat: 'json',
                            dataSource: {
                            "chart": {
                            "caption": "PV Voltage (V)",
                                    "lowerLimit": "0",
                                    "upperLimit": "100",
                                    "theme": "fint",
                                    "showValue": "1",
                                    "valueBelowPivot": "1",
                                    "majorTMNumber": "9",
                                    "minorTMNumber": "5",
                            },
                                    "colorRange": {
                                    "color": [{
                                    "minValue": "0",
                                            "maxValue": "90",
                                            "code": "#6baa01"
                                    }, {
                                    "minValue": "91",
                                            "maxValue": "100",
                                            "code": "#e44a00"
                                    }]
                                    },
                                    "dials": {
                                    "dial": [{
                                    "value": "<?php echo $tracer->realtimeData[0]; ?>"
                                    }]
                                    }
                            }
                    }
                    );
                            fusioncharts.render();
                    });</script>


        <script type="text/javascript">
                    FusionCharts.ready(function () {
                    var fusioncharts = new FusionCharts({
                    type: 'angulargauge',
                            renderAt: 'Battery voltage',
                            width: '300',
                            height: '200',
                            dataFormat: 'json',
                            dataSource: {
                            "chart": {
                            "caption": "Battery Voltage (V)",
                                    "lowerLimit": "10",
                                    "upperLimit": "15",
                                    "theme": "fint",
                                    "showValue": "1",
                                    "valueBelowPivot": "1",
                                    "majorTMNumber": "7",
                                    "minorTMNumber": "9",
                            },
                                    "colorRange": {
                                    "color": [{
                                    "minValue": "10",
                                            "maxValue": "11",
                                            "code": "#e44a00"
                                    }, {
                                    "minValue": "11.001",
                                            "maxValue": "13.8",
                                            "code": "#6baa01"
                                    }, {
                                    "minValue": "13.801",
                                            "maxValue": "14.5",
                                            "code": "#f8bd19"
                                    }, {
                                    "minValue": "14.501",
                                            "maxValue": "15",
                                            "code": "#e44a00"
                                    }]
                                    },
                                    "dials": {
                                    "dial": [{
                                    "value": "<?php echo $tracer->realtimeData[3]; ?>"
                                    }]
                                    }
                            }
                    }
                    );
                            fusioncharts.render();
                    });</script>

        <script type="text/javascript">
                    FusionCharts.ready(function () {
                    var fusioncharts = new FusionCharts({
                    type: 'angulargauge',
                            renderAt: 'Load voltage',
                            width: '300',
                            height: '200',
                            dataFormat: 'json',
                            dataSource: {
                            "chart": {
                            "caption": "Load Voltage (V)",
                                    "lowerLimit": "10",
                                    "upperLimit": "15",
                                    "theme": "fint",
                                    "showValue": "1",
                                    "valueBelowPivot": "1",
                                    "majorTMNumber": "16",
                                    "minorTMNumber": "5",
                            },
                                    "colorRange": {
                                    "color": [{
                                    "minValue": "0",
                                            "maxValue": "13.8",
                                            "code": "#6baa01"
                                    }, {
                                    "minValue": "13.801",
                                            "maxValue": "14.5",
                                            "code": "#f8bd19"
                                    }, {
                                    "minValue": "14.501",
                                            "maxValue": "15",
                                            "code": "#e44a00"
                                    }]
                                    },
                                    "dials": {
                                    "dial": [{
                                    "value": "<?php echo $tracer->realtimeData[6]; ?>"
                                    }]
                                    }
                            }
                    }
                    );
                            fusioncharts.render();
                    });</script>


        <script type="text/javascript">
                    FusionCharts.ready(function () {
                    var fusioncharts = new FusionCharts({
                    type: 'angulargauge',
                            renderAt: 'PV power',
                            width: '300',
                            height: '200',
                            dataFormat: 'json',
                            dataSource: {
                            "chart": {
                            "caption": "PV power (W)",
                                    "lowerLimit": "0",
                                    "upperLimit": "400",
                                    "theme": "fint",
                                    "showValue": "1",
                                    "valueBelowPivot": "1",
                                    "majorTMNumber": "5",
                                    "minorTMNumber": "9",
                            },
                                    "colorRange": {
                                    "color": [{
                                    "minValue": "0",
                                            "maxValue": "350",
                                            "code": "#6baa01"
                                    }, {
                                    "minValue": "351",
                                            "maxValue": "400",
                                            "code": "#e44a00"
                                    }]
                                    },
                                    "dials": {
                                    "dial": [{
                                    "value": "<?php echo $tracer->realtimeData[2]; ?>"
                                    }]
                                    }
                            }
                    }
                    );
                            fusioncharts.render();
                    });</script>


        <script type="text/javascript">
                    FusionCharts.ready(function () {
                    var fusioncharts = new FusionCharts({
                    type: 'angulargauge',
                            renderAt: 'Battery power',
                            width: '300',
                            height: '200',
                            dataFormat: 'json',
                            dataSource: {
                            "chart": {
                            "caption": "Battery Power (W)",
                                    "lowerLimit": "0",
                                    "upperLimit": "400",
                                    "theme": "fint",
                                    "showValue": "1",
                                    "valueBelowPivot": "1",
                                    "majorTMNumber": "5",
                                    "minorTMNumber": "9",
                            },
                                    "colorRange": {
                                    "color": [{
                                    "minValue": "0",
                                            "maxValue": "350",
                                            "code": "#6baa01"
                                    }, {
                                    "minValue": "351",
                                            "maxValue": "400",
                                            "code": "#e44a00"
                                    }]
                                    },
                                    "dials": {
                                    "dial": [{
                                    "value": "<?php echo $tracer->realtimeData[5]; ?>"
                                    }]
                                    }
                            }
                    }
                    );
                            fusioncharts.render();
                    });</script>

        <script type="text/javascript">
                    FusionCharts.ready(function () {
                    var fusioncharts = new FusionCharts({
                    type: 'angulargauge',
                            renderAt: 'Load power',
                            width: '300',
                            height: '200',
                            dataFormat: 'json',
                            dataSource: {
                            "chart": {
                            "caption": "Load Power (W)",
                                    "lowerLimit": "0",
                                    "upperLimit": "400",
                                    "theme": "fint",
                                    "showValue": "1",
                                    "valueBelowPivot": "1",
                                    "majorTMNumber": "5",
                                    "minorTMNumber": "9",
                            },
                                    "colorRange": {
                                    "color": [{
                                    "minValue": "0",
                                            "maxValue": "350",
                                            "code": "#6baa01"
                                    }, {
                                    "minValue": "351",
                                            "maxValue": "400",
                                            "code": "#e44a00"
                                    }]
                                    },
                                    "dials": {
                                    "dial": [{
                                    "value": "<?php echo $tracer->realtimeData[8]; ?>"
                                    }]
                                    }
                            }
                    }
                    );
                            fusioncharts.render();
                    });</script>

        <script type="text/javascript">
                    FusionCharts.ready(function () {
                    var fusioncharts = new FusionCharts({
                    type: 'angulargauge',
                            renderAt: 'PV current',
                            width: '300',
                            height: '200',
                            dataFormat: 'json',
                            dataSource: {
                            "chart": {
                            "caption": "PV Current (A)",
                                    "lowerLimit": "0",
                                    "upperLimit": "30",
                                    "theme": "fint",
                                    "showValue": "1",
                                    "valueBelowPivot": "1",
                                    "majorTMNumber": "4",
                                    "minorTMNumber": "9",
                            },
                                    "colorRange": {
                                    "color": [{
                                    "minValue": "0",
                                            "maxValue": "25",
                                            "code": "#6baa01"
                                    }, {
                                    "minValue": "25.001",
                                            "maxValue": "30",
                                            "code": "#e44a00"
                                    }]
                                    },
                                    "dials": {
                                    "dial": [{
                                    "value": "<?php echo $tracer->realtimeData[1]; ?>"
                                    }]
                                    }
                            }
                    }
                    );
                            fusioncharts.render();
                    });</script>

        <script type="text/javascript">
                    FusionCharts.ready(function () {
                    var fusioncharts = new FusionCharts({
                    type: 'angulargauge',
                            renderAt: 'Battery current',
                            width: '300',
                            height: '200',
                            dataFormat: 'json',
                            dataSource: {
                            "chart": {
                            "caption": "Battery Current (A)",
                                    "lowerLimit": "-30",
                                    "upperLimit": "30",
                                    "theme": "fint",
                                    "showValue": "1",
                                    "valueBelowPivot": "1",
                                    "majorTMNumber": "7",
                                    "minorTMNumber": "9",
                            },
                                    "colorRange": {
                                    "color": [{
                                    "minValue": "-30",
                                            "maxValue": "0",
                                            "code": "#e44a00"
                                    }, {
                                    "minValue": "0.001",
                                            "maxValue": "30",
                                            "code": "#6baa01"
                                    }]
                                    },
                                    "dials": {
                                    "dial": [{
                                    "value": "<?php echo $tracer->realtimeData[4]; ?>"
                                    }]
                                    }
                            }
                    }
                    );
                            fusioncharts.render();
                    });</script>

        <script type="text/javascript">
                    FusionCharts.ready(function () {
                    var fusioncharts = new FusionCharts({
                    type: 'angulargauge',
                            renderAt: 'Load current',
                            width: '300',
                            height: '200',
                            dataFormat: 'json',
                            dataSource: {
                            "chart": {
                            "caption": "Load Current (A)",
                                    "lowerLimit": "0",
                                    "upperLimit": "30",
                                    "theme": "fint",
                                    "showValue": "1",
                                    "valueBelowPivot": "1",
                                    "majorTMNumber": "4",
                                    "minorTMNumber": "9",
                            },
                                    "colorRange": {
                                    "color": [{
                                    "minValue": "0",
                                            "maxValue": "25",
                                            "code": "#6baa01"
                                    }, {
                                    "minValue": "25.001",
                                            "maxValue": "30",
                                            "code": "#e44a00"
                                    }]
                                    },
                                    "dials": {
                                    "dial": [{
                                    "value": "<?php echo $tracer->realtimeData[7]; ?>"
                                    }]
                                    }
                            }
                    }
                    );
                            fusioncharts.render();
                    });</script>


        <script type="text/javascript">
                    FusionCharts.ready(function(){
                    var fusioncharts = new FusionCharts({
                    type: 'thermometer',
                            renderAt: 'Charger temp',
                            width: '160',
                            height: '400',
                            dataFormat: 'json',
                            dataSource: {
                            "chart": {
                            "caption": "Charger Temperature",
                                    "lowerLimit": "-20",
                                    "upperLimit": "100",
                                    "numberSuffix": "°C",
                                    "showhovereffect": "1",
                                    "decimals": "2",
                                    "majorTMNumber": "13",
                                    "minorTMNumber": "5",
                                    "thmBulbRadius": "25",
                                    "thmOriginX": "80",
<?php
switch ($tracer->realtimeData[10]) {
    case ($tracer->realtimeData[10] < 10):
        echo '"gaugeFillColor": "#008ee4",';
        echo '"gaugeBorderColor": "#008ee4",';
        break;
    case ($tracer->realtimeData[10] >= 10 && $tracer->realtimeData[10] < 70):
        echo '"gaugeFillColor": "#6baa01",';
        echo '"gaugeBorderColor": "#6baa01",';
        break;
    case ($tracer->realtimeData[10] >= 70 && $tracer->realtimeData[10] < 75):
        echo '"gaugeFillColor": "#f8bd19",';
        echo '"gaugeBorderColor": "#f8bd19",';
        break;
    case ($tracer->realtimeData[10] >= 75):
        echo '"gaugeFillColor": "#e44a00",';
        echo '"gaugeBorderColor": "#e44a00",';
        break;
}
?>
                            "gaugeFillAlpha": "70",
                                    //Customizing gauge border
                                    "showGaugeBorder": "1",
                                    "gaugeBorderThickness": "2",
                                    "gaugeBorderAlpha": "60",
                                    "theme": "fint",
                                    "chartBottomMargin": "20"
                            },
                                    "value": "<?php echo $tracer->realtimeData[10]; ?>"
                            }
                    }
                    );
                            fusioncharts.render();
                    });
        </script>

        <meta charset="utf-8">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <title>Power Status</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <style>
            table.gridtable {
                font-family: verdana,arial,sans-serif;
                font-size:12px;
                color:#333333;
                border-width: 1px;
                border-color: #666666;
                border-collapse: collapse;
                width: 100%;
            }
            table.gridtable th {
                border-width: 1px;
                padding: 8px;
                border-style: solid;
                border-color: #666666;
                background-color: #dedede;
                text-align: center;
            }
            table.gridtable th.connection {
                background-color: <?php echo $connection_bgcolor ?>;
                text-align:center;
            }
            table.gridtable th.tracerstatus {
                background-color: <?php echo $tracerstatus_bgcolor ?>;
                text-align:center;
            }
            table.gridtable td {
                border-width: 1px;
                border-top: 0px;
                padding: 5px;
                border-style: solid;
                border-color: #666666;
                background-color: #ffffff;
                text-align:right;
                height:17px;
            }
            table.gridtable td.bold {
                font-weight: bold;
                width: 33.3%;
                text-align:left;
            }
            table.gridtable td.head {
                font-weight: bold;
                width: 33.3%;
                text-align:right;
            }
            table.gridtable td.button {
                width: 15%;
                text-align:center;
                background-color:#efefef;
                color:#cecece;
                cursor: default;
            }
            div.centered
            {
                text-align: center;
            }
            div.inner
            {
                max-width: 650px;
                width: 95%;
                text-align: center;
                margin: 0 auto;
            }
            div.inner table
            {
                margin: 0 auto;
                text-align: left;
            }
            #chargepercentp {
                width: 100%;
                height: 100%;
                position: absolute;
                vertical-align: middle;
                left:-5px;
                z-index: 10;
            }
            #chargepercentg {
                top: 0;
                width: <?php echo $battSoc; ?>%;
                height: 100%;
                position: absolute;
                background-color:#dedede;
                margin: 0 auto;
                padding: 0;
                z-index: 1;
            }
            #container {
                position: relative;
                top: 0;
                left: 0;
                width:100%;
                height:100%;
                margin: 0 auto;
                padding: 0;
                vertical-align: middle;
                line-height: 27px;
            }
        </style>
    </head>
    <body>
        <div class="centered">
            <table style='width:97%;'>
                <tr>
                    <td>
                        <table>
                            <tr><td colspan="3" style='text-align:center;'><div id="currentflow"></div></td></tr>
                            <tr><td><div id="PV voltage"></div></td><td><div id="Battery voltage"></div></td><td><div id="Load voltage"></div></td></tr>
                            <tr><td><div id="PV current"></div></td><td><div id="Battery current"></div></td><td><div id="Load current"></div></td></tr>
                            <tr><td><div id="PV power"></div></td><td><div id="Battery power"></div></td><td><div id="Load power"></div></td></tr>
                        </table>
                    </td>
                    <td>
                        <table class="gridtable">
                            <tr>
                                <th class="tracerstatus" id="tracerstatus" colspan=2>-= Tracer Status =-</th>
                            </tr>
                            <tr>
                                <td class="bold">Battery status</td><td class="status" id="batterystatus"><?php echo $bStatus; ?></td>
                            </tr>
                            <tr>
                                <td class="bold">Equipment status</td><td class="status" id="equipmentstatus"><?php echo $eStatus; ?></td>
                            </tr>
                            <tr>
                                <td colspan="2" style='text-align:center;'><div id="Charger temp"></div></td>
                            </tr>
                        </table>
                    </td>
                    <td><div id="chart"></div></td>
                </tr>
                <tr><td colspan="3"><table class="gridtable">
                            <tr>
                                <th class="connection" id="connection"><?php echo $connection; ?></th>
                            </tr>
                        </table></td></tr>
            </table>
            <br>
        </div>
    </body>
</html>

