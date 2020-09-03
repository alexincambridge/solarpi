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

        var data = google.visualization.arrayToDataTable([
          ['Label', 'Value'],
          ['Volts', 0],
          ['Watts', 0],
          ['Power', 0]
         
        ]);

        var options = {
          width: 400, height: 400,
	  min: 0, max: 30,
          greenFrom: 12, greenTo: 24,
          yellowFrom:5, yellowTo: 12,
          redFrom:0, redTo: 5,
          majorTicks:['5','10','20','30']
        };

        var chart = new google.visualization.Gauge(document.getElementById('Medidores'));
        chart.draw(data, options);

        setInterval(function() {
            var JSON=$.ajax({
                url:"http://experiments.ddns.net/solarpi/test.php?q=1",
                dataType: 'json',
                async: false}).responseText;
            var Respuesta=jQuery.parseJSON(JSON);
            
          data.setValue(0, 1,Respuesta[0].PV_array_voltage);
          data.setValue(1, 1,Respuesta[0].PV_array_current);
	  data.setValue(2, 1,Respuesta[0].PV_array_power);
          chart.draw(data, options);
        }, 1300);
        
      }
    </script>

</head>
<body>
       <div id="Medidores" style="width: 900px; height: 500px"></div>
   
</body>
</html>

