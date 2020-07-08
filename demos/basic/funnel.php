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

$p->data = array(array(array('Heavy Industry', 12),array('Retail', 9), array('Light Industry', 14), array('Out of home', 16),array('Commuting', 7), array('Orientation', 9)));
$p->chart_type = "funnel";

// Common Options
$p->title = "Funnel Chart";
$out = $p->render('c1');
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="../../lib/js/chartphp.css">
		<script src="../../lib/js/jquery.min.js"></script>
		<script src="../../lib/js/chartphp-l.js"></script>	
		<style>
			/* white color data labels */
			.jqplot-data-label{color:white;}
		</style>
	</head>

	<body>
		<div>
		<?php echo $out; ?>
		</div>
	</body>
</html>
