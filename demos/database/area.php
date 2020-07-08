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

$p->data_sql = "select strftime('%Y-%m',o.orderdate) as Year, sum(d.quantity) as Sales
					from `order details` d, orders o
					where o.orderid = d.orderid
					group by strftime('%Y-%m',o.orderdate) limit 50";

$p->chart_type = "area";

// Common Options
$p->title = "Sales Summary";
$p->xlabel = "Month";
$p->x_data_type = "date";
$p->ylabel = "Sales";

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
