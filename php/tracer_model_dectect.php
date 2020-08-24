
<?php
//detect the epever model
$tracermodel1206AN = "Tracer1206AN";
$tracermodel2206AN = "Tracer2206AN";
$tracermodel1210AN = "Tracer1210AN";
$tracermodel2210AN = "Tracer2210AN";
$tracermodel3210AN = "Tracer3210AN";
$tracermodel4210AN = "Tracer4210AN";

if ($tracer->getRatedData()) {

    $rate_voltage = $tracer->ratedData[0];
    $rate_charging_current = $tracer->ratedData[4];


    if ($rate_voltage == 60 && $rate_charging_current == 10) {
        echo $tracermodel1206AN;
    }
    if ($rate_voltage == 60 && $rate_charging_current == 20) {
        echo $tracermodel2206AN;
    }
    if ($rate_voltage == 100 && $rate_charging_current == 10) {
        echo $tracermodel1210AN;
    }
    if ($rate_voltage == 100 && $rate_charging_current == 20) {
        echo $tracermodel2210AN;
    }
    if ($rate_voltage == 100 && $rate_charging_current == 30) {
        echo $tracermodel3210AN;
    }
    if ($rate_voltage == 100 && $rate_charging_current == 40) {
        echo $tracermodel4210AN;

    }
}
?>