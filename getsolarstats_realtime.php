#!/usr/bin/php

<?php
//harvest data and stores it in a database

$dbh = new PDO("mysql:host=localhost;dbname=solardata", "root", "password");

//this is planning for future expansion, this array holds the wireless device connection details
$solararray = array();
$solararray["/dev/ttyXRUSB0"]["ip"] = '192.168.1.149';
$solararray["/dev/ttyXRUSB0"]["port"] = '23';

//eg expanded system with a second controller
//$solararray["/dev/ttyXRUSB0"]["ip"] = '192.168.1.149';
//$solararray["/dev/ttyXRUSB0"]["port"] = '23';

require_once 'PhpEpsolarTracer.php';

$date = date("M H:i");

$time = time();

$i = 1;
while (list ($key, $val) = each($solararray)) {

    $tracer = new PhpEpsolarTracer($key);


    if ($tracer->getRealtimeData()) {
        $sth = $dbh->prepare("INSERT INTO status_realtime (`PV_array_voltage`,`PV_array_current`,`PV_array_power`,`Battery_voltage`,`Battery_charging_current`,`Battery_charging_power`,`Load_voltage`,`Load_current`,`Load_power`) values (?,?,?,?,?,?,?,?,?)");
        //$sth->BindParam(1, $i);
        //$sth->BindParam(2, $date);
        $sth->BindParam(1, $tracer->realtimeData[0]);   //voltage
        $sth->BindParam(2, $tracer->realtimeData[1]);   //current
        $sth->BindParam(3, $tracer->realtimeData[2]);   //power
        $sth->BindParam(4, $tracer->realtimeData[3]);   //bat volt
        $sth->BindParam(5, $tracer->realtimeData[4]);  //bat current
        $sth->BindParam(6, $tracer->realtimeData[5]);  //bat power
        $sth->BindParam(7, $tracer->realtimeData[6]);  //load volt
        $sth->BindParam(8, $tracer->realtimeData[7]); //load current
        $sth->BindParam(9, $tracer->realtimeData[8]); //load power
        $sth->execute();

        //station id
        $i++;
    }
        echo "committed";
}

?>
