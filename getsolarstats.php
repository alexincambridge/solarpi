#!/usr/bin/php

<?php
//harvest data and stores it in a database

$dsn = "mysql:host=localhost;dbname=Solardata";
$user = "root";
$passwd = "password";

$pdo = new PDO($dsn, $user, $passwd);

//$dbh = new PDO(mysql:host=localhost;dbname="Solardata", "root", "password");

 
//this is planning for future expansion, this array holds the wireless device connection details
$solararray = array();
$solararray["/dev/ttyXRUSB0"]["ip"] = '192.168.1.149';
$solararray["/dev/ttyXRUSB0"]["port"] = '23';

//eg expanded system with a second controller
//$solararray["/dev/ttyUSB22"]["ip"] = '192.168.123.22';
//$solararray["/dev/ttyUSB22"]["port"] = '23';

require_once 'PhpEpsolarTracer.php';

$time = time();

$i = 1;
while (list ($key, $val) = each($solararray)) {

    $tracer = new PhpEpsolarTracer($key);


    if ($tracer->getRealtimeData()) {
 
        $sth = $pdo->prepare("insert into stats (`Controller`,`timestamp`,`PV array voltage`,`PV array current`,`PV array power`,`Battery voltage`,`Battery charging current`,`Battery charging power`,`Load voltage`,`Load current`,`Load power`,`Charger temperature`, `Heat sink temperature`,`Battery status`,`Equipment status`) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $sth->BindParam(1, $i);
        $sth->BindParam(2, $time);
        $sth->BindParam(3, $tracer->realtimeData[0]);
        $sth->BindParam(4, $tracer->realtimeData[1]);
        $sth->BindParam(5, $tracer->realtimeData[2]);
        $sth->BindParam(6, $tracer->realtimeData[3]);
        $sth->BindParam(7, $tracer->realtimeData[4]);
        $sth->BindParam(8, $tracer->realtimeData[5]);
        $sth->BindParam(9, $tracer->realtimeData[6]);
        $sth->BindParam(10, $tracer->realtimeData[7]);
        $sth->BindParam(11, $tracer->realtimeData[8]);
        $sth->BindParam(12, $tracer->realtimeData[10]);
        $sth->BindParam(13, $tracer->realtimeData[11]);
        $sth->BindParam(14, $tracer->realtimeData[15]);
        $sth->BindParam(15, $tracer->realtimeData[16]);

        $sth->execute();

        //station id
        $i++;
    }
}
?>

