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

$p->data_sql = "select c.categoryname, sum(a.quantity) as 'Sales'
				from products b, `order details` a, categories c
				where a.productid = b.productid and c.categoryid = b.categoryid
				group by c.categoryid
				order by c.categoryid";

$p->chart_type = "bar";

// Common Options
$p->title = "Category Sales";
$p->xlabel = "Category";
$p->ylabel = "Sales";
// $p->direction='horizontal';
$p->show_point_label = true;

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
