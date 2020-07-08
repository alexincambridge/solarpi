<?php
/**
 * Charts 4 PHP
 *
 * @author Shani <support@chartphp.com> - http://www.chartphp.com
 * @version 2.0
 * @license: see license.txt included in package
 */

include_once("../../config.php");
include_once(CHARTPHP_LIB_PATH . "/inc/chartphp_dist.php");

// Horizontal
$p = new chartphp();
include("../../example_data.php");
$p->data = $bar_stacked_data;
$p->chart_type = "bar-stacked";

// Common Options
$p->title = "Bar Stacked";
$p->xlabel = "Vehicles";
$p->ylabel = "Frequency";
$p->series_label = array('Quarter 1','Quarter 2','Quarter 3','Quarter 4');
$p->direction = "horizontal";
$p->targetx_start = 20;
$p->targetx_end = 20;
$p->targety_start = "Hybrid";
$p->targety_end = "Truck";
$p->targetline_color = "green";
$p->targetline_width = 4;
$p->targetline_style = "dashdot";  //line

 
$out1 = $p->render('c1');


// Vertical
// $p = new chartphp();
// include("../../example_data.php");
// $p->data = $bar_stacked_data;

// $p->chart_type = "bar-stacked";

// // Common Options
// $p->title = "Bar Stacked";
// $p->xlabel = "Vehicles";
// $p->ylabel = "Frequency";
// $p->series_label = array('Quarter 1','Quarter 2','Quarter 3','Quarter 4');

// $out2 = $p->render('c2');


?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="../../lib/js/chartphp.css">
		<script src="../../lib/js/jquery.min.js"></script>
		<script src="../../lib/js/chartphp.js"></script>
	</head>

	<body>
		<div>
			<?php echo $out1; ?>
			<br>
			<?php // echo $out2; ?>
		</div>
	</body>
</html>
