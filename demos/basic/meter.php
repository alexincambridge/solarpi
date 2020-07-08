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

$p->data = array(array(266));
$p->intervals = array(100,200,300,400,500);
$p->chart_type = "meter";
// $p->color = array('#8059d9', '#b96ffd', '#ee70fe', '#ff70dd', '#ff70a6'); // voilet -> pink
$p->color = array('#f50500', '#fe5b00', '#fcae02', '#bbfa37','#9ef410'); // red -> green
$p->xlabel = "Mbps";
// $p->theme = "dark";

// Common Options
$p->title = "Meter Gauge Chart";
$out = $p->render('c1');
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="../../lib/js/chartphp.css">
		<script src="../../lib/js/jquery.min.js"></script>
		<script src="../../lib/js/chartphp-l.js"></script>
	</head>
	<body>
		<div>
			<?php echo $out; ?>
		</div>
	</body>
</html>
