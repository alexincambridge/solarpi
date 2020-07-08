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
$p->data_sql = "select p.reorderlevel as Demand, p.unitprice as 'Unit Price', sum(o.quantity) as Sales, p.productname as 'Product'
				from products p, 'Order Details' o
				where o.productid = p.productid
				group by product having p.unitprice < 100 and demand > 5 limit 5";

$p->chart_type = "bubble";

// Common Options
$p->title = "Product Demand/Sales";
$p->xlabel = "Price";
$p->ylabel = "Demand";
$out = $p->render('c2');
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
