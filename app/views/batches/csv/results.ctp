<?php

$table = array();
$table2 = array();
$table3 = array();
$workers = array();

foreach($results as $row) {
	$workers[$row['workerid']] = '';
}

foreach($results as $row) {
	$table[$row['Answer.videoname']] = $workers;
	$table3[$row['Answer.videoname']] = $workers;
}


foreach($results as $row) {
	$table[$row['Answer.videoname']][$row['workerid']] =  $row['Answer.quality'];
	$table3[$row['Answer.videoname']][$row['workerid']] =  $row['Answer.timer'];
	
	if (!isset($table2['timer'][$row['workerid']])) {
		$table2['timer'][$row['workerid']] = 0;
		$table2['counter'][$row['workerid']] = 0;
	}
	$table2['timer'][$row['workerid']] += $row['Answer.timer'];
	$table2['counter'][$row['workerid']] += 1;
	
	$table2['screenres'][$row['workerid']] = $row['Answer.screenres'];
	$table2['screendepth'][$row['workerid']] = $row['Answer.screendepth'];
	$table2['flashver'][$row['workerid']] = $row['Answer.flashver'];
	$table2['useragent'][$row['workerid']] = $row['Answer.useragent'];
	$table2['remoteip'][$row['workerid']] = $row['Answer.remoteip'];
	$table2['remotehost'][$row['workerid']] = $row['Answer.remotehost'];
	$table2['countrycode'][$row['workerid']] = $row['geo.countrycode'];
	$table2['country'][$row['workerid']] = $row['geo.country'];
	$table2['city'][$row['workerid']] = $row['geo.city'];
}

foreach($table as $row) {
	echo "\"\"\t";
	foreach($row as $wid => $cell) {
		echo '"' . $wid . "\"\t";
	}
	echo "\n";
	break;
}
	
echoTable($table);
echoTable($table3);
echoTable($table2);

function echoTable($table) {
	foreach($table as $name => $row) {
		echo '"' . $name . "\"\t";
		foreach($row as $cell) {
			if (is_numeric($cell)) {
				echo $cell . "\t";
			} else {
				echo '"' . $cell . "\"\t";
			}
		}
		echo "\n";
	}
	
	echo "\n";
}
?>