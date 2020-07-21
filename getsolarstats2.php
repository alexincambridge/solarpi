#!/usr/bin/php
<?php
/**
 * Created by PhpStorm.
 * User: alexg
 * Date: 21/07/2020
 * Time: 22:10
 */

//data and stores it in a database

$dbh = new PDO("mysql:host=localhost;dbname=solardata", "root", "toor");


//this is planning for future expansion, this array holds the wireless device connection details
$solararray = array();
$solararray["/dev/ttyXRUSB0"]["ip"] = '192.168.1.149';
$solararray["/dev/ttyXRUSB0"]["port"] = '23';

//eg expanded system with a second controller
//$solararray["/dev/ttyXRUSB0"]["ip"] = '192.168.1.149';
//$solararray["/dev/ttyXRUSB0"]["port"] = '23';

require_once 'PhpEpsolarTracer.php';

$date = date("H:i:s");


$time = time();

$i = 1;

while (list ($key, $val) = each($solararray)) {

$tracer = new PhpEpsolarTracer($key);


if ($tracer->getStatData()) {

    $sth = $dbh->prepare("insert into status (`Controller`,`timestamp`,`PV_array_voltage`,`PV_array_current`,`PV_array_power`,`Battery_voltage`,`Battery_charging_current`,`Battery_charging_power`,`Load_voltage`,`Load_current`,`Load_power`,`Charger_temperature`, `Heat_sink_temperature`,`Battery_status`,`Equipment_status`) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $sth->BindParam(1, $i);
    $sth->BindParam(2, $date);
    $sth->BindParam(3, $tracer->statData[0]);
    $sth->BindParam(4, $tracer->statData[1]);
    $sth->BindParam(5, $tracer->statData[2]);
    $sth->BindParam(6, $tracer->statData[3]);
    $sth->BindParam(7, $tracer->statData[4]);
    $sth->BindParam(8, $tracer->statData[5]);
    $sth->BindParam(9, $tracer->statData[6]);
    $sth->BindParam(10, $tracer->statData[7]);
    $sth->BindParam(11, $tracer->statData[8]);
    $sth->BindParam(12, $tracer->statData[10]);
    $sth->BindParam(13, $tracer->statData[11]);
    $sth->BindParam(14, $tracer->statData[15]);
    $sth->BindParam(15, $tracer->statData[16]);

    $sth->execute();

    //station id
    $i++;

    echo "statData data committed"
}

?>