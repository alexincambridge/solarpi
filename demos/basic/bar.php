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

$p = new chartphp();
		
include("../../example_data.php");
$p->data=$bar_chart_data;
$p->chart_type = "bar";

// Common Options
$p->title = "Bar Chart";
$p->xlabel = "Months";
$p->ylabel = "Purchase";
$p->show_xticks = true;
$p->show_yticks = true;
$p->show_point_label = true;
$p->targetx_start = "2010/12";
$p->targetx_end = "2012/04";
$p->targety_start = 250;
$p->targety_end = 250;
$p->targetline_color = "purple";
$p->targetline_width = 4;
$p->targetline_style = "dashdot";  //line

$out = $p->render('c1');
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
			<?php echo $out; ?>
		</div>
	</body>
</html>
