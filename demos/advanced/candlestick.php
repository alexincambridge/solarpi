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

// data array is populated from example data file
include("../../example_data.php");
$p->data = $candlestick_chart_data;
$p->chart_type = "candlestick";
$p->rangestart=$rangestart;
$p->rangeend=$rangeend;
$p->rangesliderstart=$rangesliderstart;
$p->rangesliderend=$rangesliderend;
$p->rangetitle= $rangetitle;
$p->rangetype= $rangetype;
$p->yrangestart=$yrangestart;
$p->yrangeend=$yrangeend;
//$p->decreasing_line_color = "rgb(164,196,0)";  //Optional
//$p->increasing_line_color = "rgb(27,161,226)";  //Optional
//$p->series_label = array("Team1");	  //Optional

// Common Options
$p->title = "Candle Stick Chart";
$p->legend = false;
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
