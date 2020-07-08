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

// Vertical
$p = new chartphp();

include("../../example_data.php");
$p->data = $bar_grouped_chart_data;
$p->chart_type = "bar-grouped";

// Common Options
$p->title = "Grouped Bar Chart";
$p->xlabel = "Vehicles";
$p->ylabel = "Frequency";
$p->series_label = array('Quarter 1','Quarter 2','Quarter 3','Quarter 4');
 
$out1 = $p->render('c1');

// Vertical
/* $p = new chartphp();
$p->data = array(
				array(
					array(1,4), array(2,6), array(3,7), array(4,10), array(5,8)
					),
				array(
					array(1,7), array(2,5),array(3,3),array(4,2), array(5,10)
					),
				array(
					array(1,14), array(2,9), array(3,9), array(4,8), array(5,3)
					),
				array(
					array(1,14), array(2,9), array(3,9), array(4,8), array(5,8)
					)
				);

$p->chart_type = "bar-stacked";

// Common Options
$p->title = "Vertical Bar Stacked";
$p->xlabel = "My X Axis";
$p->ylabel = "My Y Axis";
$p->series_label = array('Q1','Q2','Q3','Q4');
$out2 = $p->render('c2'); */
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
			<?php //echo $out2; ?>
		</div>
	</body>
</html>
