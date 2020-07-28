#!/usr/bin/php

<?php
//harvest data and stores it in a database

$dbh = new PDO("mysql:host=localhost;dbname=solardata", "root", "password");

 
//this is planning for future expansion, this array holds the wireless device connection details
$solararray = array();
$solararray["/dev/ttyXRUSB0"]["ip"] = '192.168.1.149';
$solararray["/dev/ttyXRUSB0"]["port"] = '23';

$date = date("H:i:s");

require_once 'PhpEpsolarTracer.php';
$i = 1;
while (list ($key, $val) = each($solararray)) {

    $tracer = new PhpEpsolarTracer($key);

    if ($tracer->getRatedData()) {
        $sth = $dbh->prepare("TRUNCATE TABLE tracer");
        $sth = $dbh->prepare("INSERT INTO tracer (`Controller`,`timestamp`,`PV_rate_voltage`,`PV_rate_current`,`PV_rate_power`,`Battery_rate_voltage`,`Rate_charging_current`,`Rate_charging_power`,`Charging_mode`,`Rate_load_current`) values (?,?,?,?,?,?,?,?,?,?)");
        $sth->BindParam(1, $i);
        $sth->BindParam(2, $date);
        $sth->BindParam(3, $tracer->ratedData[0]);
        $sth->BindParam(4, $tracer->ratedData[1]);
        $sth->BindParam(5, $tracer->ratedData[2]);
        $sth->BindParam(6, $tracer->ratedData[3]);
        $sth->BindParam(7, $tracer->ratedData[4]);
        $sth->BindParam(8, $tracer->ratedData[5]);
        $sth->BindParam(9, $tracer->ratedData[6]);
        $sth->BindParam(10, $tracer->ratedData[7]);
        $sth->execute();

        //station id
        $i++;
    }
echo "committed";
}
?>

