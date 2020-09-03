
<?php
header('Content-Type: application/json');
$pdo=new PDO("mysql:dbname=solardata;host=127.0.0.1","root","password");

switch($_GET['q']){
    // Buscar Último Datos de Panel photovoltaic
    case 1:
        $statement=$pdo->prepare("SELECT `id`,`PV_array_voltage`,`PV_array_current`,`PV_array_power` FROM `status_realtime` ORDER BY `id` DESC LIMIT 1");
        $statement->execute();
        $results=$statement->fetchAll(PDO::FETCH_ASSOC);

        $json=json_encode($results, JSON_NUMERIC_CHECK);
        echo $json;
        break;

}

?>
