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

$url = "https://docs.google.com/spreadsheets/d/1KkNJxvjhpjyzUu-0EW0xmhnYxKN8RPeEqhctxKJkx3s/gviz/tq?tqx=out:csv&sheet=Sheet1";
$p->data_csv_url = $url;

$p->title = "Visits / Day";
$p->chart_type = "bar";

// read column 1 for x
$p->csv_xindex = 1;
// read column 3 for y
$p->csv_yindex = 3;

// reverse data order
$p->reverse_order = true;
$p->show_point_label = true;

$p->margin["b"] = 120;
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
