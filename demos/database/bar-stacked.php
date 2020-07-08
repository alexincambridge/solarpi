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
$p->data_sql = "select
					strftime('%Y',p_o.orderdate) as Year,
					(
						select sum(od.unitprice*od.quantity)
						from orders as o, `order Details` as od
						where o.orderid = od.orderid and strftime('%Y',o.orderdate) = strftime('%Y',p_o.orderdate) and 			cast(strftime('%m', o.orderdate) as integer) BETWEEN 1 AND 3
					) as Q1,
					(
						select sum(od.unitprice*od.quantity)
						from orders as o, `order Details` as od
						where o.orderid = od.orderid and strftime('%Y',o.orderdate) = strftime('%Y',p_o.orderdate) and 			cast(strftime('%m', o.orderdate) as integer) BETWEEN 4 AND 6
					) as Q2,
					(
						select sum(od.unitprice*od.quantity)
						from orders as o, `order Details` as od
						where o.orderid = od.orderid and strftime('%Y',o.orderdate) = strftime('%Y',p_o.orderdate) and 			cast(strftime('%m', o.orderdate) as integer) BETWEEN 7 AND 9
					) as Q3,
					(
						select sum(od.unitprice*od.quantity)
						from orders as o, `order Details` as od
						where o.orderid = od.orderid and strftime('%Y',o.orderdate) = strftime('%Y',p_o.orderdate) and 			cast(strftime('%m', o.orderdate) as integer) BETWEEN 10 AND 12
					) as Q4
					from orders as p_o, `order Details` as p_od
					where p_o.orderid = p_od.orderid
					group by year
					";

$p->chart_type = "bar-stacked";

// Common Options
$p->title = "Quarter Sales / Year";
$p->xlabel = "Year";
$p->ylabel = "Sales";
$out1 = $p->render('c1');
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
		</div>
	</body>
</html>
