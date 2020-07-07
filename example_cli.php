<?php
/*
 * PHP EpSolar Tracer Class (PhpEpsolarTracer) v0.9
 *
 * Library for communicating with 
 * Epsolar/Epever Tracer BN MPPT Solar Charger Controller
 *
 * THIS PROGRAM COMES WITH ABSOLUTELY NO WARRANTIES !
 * USE IT AT YOUR OWN RISKS !
 *
 * Copyright (C) 2016 under GPL v. 2 license
 * 13 March 2016
 *
 * @author Luca Soltoggio
 * http://www.arduinoelettronica.com/
 * https://arduinoelectronics.wordpress.com/
 *
 * This is an example on how to use the library
 *
 * It queries and prints all charger controller's registries
 *
 */
 
require_once 'PhpEpsolarTracer.php';

$tracer = new PhpEpsolarTracer('/dev/ttyXRUSB0');

if ($tracer->getInfoData()) {
	print "Info Data\n";
	print "----------------------------------\n";
	for ($i = 0; $i < count($tracer->infoData); $i++)
		print str_pad($i, 2, '0', STR_PAD_LEFT)." ".$tracer->infoKey[$i].": ".$tracer->infoData[$i]."\n";
	} else print "Cannot get Info Data\n";

if ($tracer->getRatedData()) {
	print "Rated Data\n";
	print "----------------------------------\n";
	for ($i = 0; $i < count($tracer->ratedData); $i++)
		print str_pad($i, 2, '0', STR_PAD_LEFT)." ".$tracer->ratedKey[$i].": ".$tracer->ratedData[$i].$tracer->ratedSym[$i]."\n";
	} else print "Cannot get Rated Data\n";

if ($tracer->getRealtimeData()) {
	print "\nRealTime Data\n";
	print "----------------------------------\n";
	for ($i = 0; $i < count($tracer->realtimeData); $i++)
		print str_pad($i, 2, '0', STR_PAD_LEFT)." ".$tracer->realtimeKey[$i].": ".$tracer->realtimeData[$i].$tracer->realtimeSym[$i]."\n";
	} else print "Cannot get RealTime Data\n";

if ($tracer->getStatData()) {
	print "\nStatistical Data\n";
	print "----------------------------------\n";
	for ($i = 0; $i < count($tracer->statData); $i++)
		print str_pad($i, 2, '0', STR_PAD_LEFT)." ".$tracer->statKey[$i].": ".$tracer->statData[$i].$tracer->statSym[$i]."\n";
	} else print "Cannot get Statistical Data\n";
	
if ($tracer->getSettingData()) {
	print "\nSettings Data\n";
	print "----------------------------------\n";
	for ($i = 0; $i < count($tracer->settingData); $i++)
		print str_pad($i, 2, '0', STR_PAD_LEFT)." ".$tracer->settingKey[$i].": ".$tracer->settingData[$i].$tracer->settingSym[$i]."\n";
	} else print "Cannot get Settings Data\n";

if ($tracer->getCoilData()) {
	print "\nCoils Data\n";
	print "----------------------------------\n";
	for ($i = 0; $i < count($tracer->coilData); $i++)
		print str_pad($i, 2, '0', STR_PAD_LEFT)." ".$tracer->coilKey[$i].": ".$tracer->coilData[$i]."\n";
	} else print "Cannot get Coil Data\n";

if ($tracer->getDiscreteData()) {
	print "\nDiscrete Data\n";
	print "----------------------------------\n";
	for ($i = 0; $i < count($tracer->discreteData); $i++)
		print str_pad($i, 2, '0', STR_PAD_LEFT)." ".$tracer->discreteKey[$i].": ".$tracer->discreteData[$i]."\n";
	} else print "Cannot get Discrete Data\n";
?>
