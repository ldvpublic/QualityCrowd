<?php

$plugin = Inflector::camelize(basename(realpath(dirname(__FILE__) . '/../..')));
App::import('DataSource', $plugin . '.GeoipCommonSource');
unset($plugin);

class LinuxboxSource extends GeoipCommonSource {
	
	function selectByIp($config, $ip, $ip_number) {
		if (trim(@$config['path']) == '') return array();
		if (!file_exists(@$config['path'])) return array();
		
		$result = array();
		if ($fp = fopen($config['path'], 'r')) {
			while (($csv = fgetcsv($fp, 8192, ':')) !== false) {
				list($start, $end, , , $organization, $city, $state, $country_code, $tech_contact) = $csv;
				if ($ip_number < $start) continue;
				if ($ip_number > $end) continue;
				$result = compact('ip', 'country_code', 'city', 'state', 'tech_contact', 'organization');
				break;
			}
			fclose($fp);
		}
		return $result;
	}
	
}

