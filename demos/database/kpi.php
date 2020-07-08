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

$p->data_sql = "select sum(d.quantity) as 'Sales/Month' from `order details` d";
$p->chart_type = "kpi";
// Common Options
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
		<style>
		.kpi {
			border: 1px solid #cdcdcd;
			padding: 10px;
			margin-left: 10px;
			margin-bottom: 10px;
			width: 20%;
			background-color: aliceblue;
			font-family: tahoma;
			font-size: 100%;
			float:left;
			color: #FFF;
		}
		
		.kpi-value {
			font-size:24px;
			padding:10px;
			text-align:center			
		}
		
		.kpi-text {
			text-align:center;			
		}
		</style>

		<div class="kpi" style="background-color:#20d9d2">
			<div class="kpi-value">
			<?php echo $out["value"]; ?>
			</div>
			<div class="kpi-text">
			<?php echo $out["text"]; ?>
			</div>
		</div>

		<div class="kpi" style="background-color:#8b46ff">
			<div class="kpi-value">
			<?php echo $out["value"]; ?>
			</div>
			<div class="kpi-text">
			<?php echo $out["text"]; ?>
			</div>
		</div>

		<div class="kpi" style="background-color:#ff6f2c">
			<div class="kpi-value">
			<?php echo $out["value"]; ?>
			</div>
			<div class="kpi-text">
			<?php echo $out["text"]; ?>
			</div>
		</div>
		
		<div class="kpi" style="background-color:#18bfff">
			<div class="kpi-value">
			<?php echo $out["value"]; ?>
			</div>
			<div class="kpi-text">
			<?php echo $out["text"]; ?>
			</div>
		</div>

		<div class="kpi" style="background-color:#fcb400">
			<div class="kpi-value">
			<?php echo $out["value"]; ?>
			</div>
			<div class="kpi-text">
			<?php echo $out["text"]; ?>
			</div>
		</div>

		<div class="kpi" style="background-color:#2d7ef7">
			<div class="kpi-value">
			<?php echo $out["value"]; ?>
			</div>
			<div class="kpi-text">
			<?php echo $out["text"]; ?>
			</div>
		</div>

		<div class="kpi" style="background-color:#f82b60">
			<div class="kpi-value">
			<?php echo $out["value"]; ?>
			</div>
			<div class="kpi-text">
			<?php echo $out["text"]; ?>
			</div>
		</div>
		
		<div class="kpi" style="background-color:#20c933">
			<div class="kpi-value">
			<?php echo $out["value"]; ?>
			</div>
			<div class="kpi-text">
			<?php echo $out["text"]; ?>
			</div>
		</div>

	</body>
</html>
