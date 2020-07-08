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
$p->data_sql ="select c.categoryname, sum(a.quantity) as 'Sales 1997', sum(a.quantity)+1000 as 'Sales 1998'
				from products b, `order details` a, categories c
				where a.productid = b.productid and c.categoryid = b.categoryid
				group by c.categoryid
				order by c.categoryid";

$p->chart_type = "bar-grouped";
// Common Options
$p->title = "Sales / Year";
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
