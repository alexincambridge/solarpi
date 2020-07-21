<?php
header('Content-Type: application/json');
$pdo=new PDO("mysql:dbname=solardata;host=127.0.0.1","root","password");

switch($_GET['q']){
    // Buscar Último Dato
    case 1:
        $statement=$pdo->prepare("SELECT `timestamp`,`PV_array_voltage`,`PV_array_current`,`PV_array_power` FROM `status`");
        $statement->execute();
        $results=$statement->fetchAll(PDO::FETCH_ASSOC);

        $json=json_encode($results, JSON_NUMERIC_CHECK);
        echo $json;
        break;
}
    // Buscar Todos los datos
?>


