<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Medidor Temperatura, Humedad</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['gauge']});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {

        var dataVoltage = google.visualization.arrayToDataTable([
          ['Label', 'Value'],
          ['Volts', 0],
 
        ]);

	 var dataWatts = google.visualization.arrayToDataTable([
          ['Label', 'Value'],
          ['Watts', 0],

        ]);

        var dataPower = google.visualization.arrayToDataTable([
          ['Label', 'Value'],
          ['Power', 0]

        ]);

        var optionsVoltage = {
          width: 400, height: 400,
	  min: 0, max: 30,
          greenFrom: 12, greenTo: 24,
          yellowFrom:5, yellowTo: 12,
          redFrom:0, redTo: 5,
          majorTicks:['5','10','20','30'],
          minorTicks: 10

        };

       var optionsWatts = {
          width: 400, height: 400,
          min: 0, max: 30,
          greenFrom: 12, greenTo: 24,
          yellowFrom:5, yellowTo: 12,
          redFrom:0, redTo: 5,
          majorTicks:['5','10','20','30'],
          minorTicks: 10

        };

       var optionsPower = {
          width: 400, height: 400,
          min: 0, max: 30,
          greenFrom: 12, greenTo: 24,
          yellowFrom:5, yellowTo: 12,
          redFrom:0, redTo: 5,
          majorTicks:['5','10','20','30'],
          minorTicks: 10

        };

        var chartVoltage = new google.visualization.Gauge(document.getElementById('chartVolts'));
        var chartVoltage = new google.visualization.Gauge(document.getElementById('chartWatts'));

        chartVolts.draw(data, optionsVoltage, optionsWatts, optionsPower);

        setInterval(function() {
            var JSON=$.ajax({
                url:"http://experiments.ddns.net/solarpi/test.php?q=1",
                dataType: 'json',
                async: false}).responseText;
            var Respuesta=jQuery.parseJSON(JSON);
            
          dataVoltage.setValue(0, 1,Respuesta[0].PV_array_voltage);
          data.setValue(1, 1,Respuesta[0].PV_array_current);
	  data.setValue(2, 1,Respuesta[0].PV_array_power);
          chartVoltage.draw(dataVoltage, optionsVoltage);
        }, 1300);
        
      }
    </script>

</head>
<body>
       <div id="chartVolts" style="width: 900px; height: 500px"></div>

       <div id="chartWatts" style="width: 900px; height: 500px"></div>   
</body>
</html>

