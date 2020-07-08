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

// Common Options
$p->title = "Pie Chart";
// data array is populated from example data file
include("../../example_data.php");
$p->data = $pie_chart_data;
$p->chart_type = "pie";

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
