<?php

$plugin = Inflector::camelize(basename(realpath(dirname(__FILE__) . '/../..')));
App::import('DataSource', $plugin . '.GeoipCommonSource');
unset($plugin);

class CombinationSource extends GeoipCommonSource {
	
	function selectByIp($config, $ip, $ip_number) {
		$result = array();
		$plugin = Inflector::camelize(basename(realpath(dirname(__FILE__) . '/../..')));
		
		foreach (array_reverse($config['priority']) as $source => $config2) {
			$clz = Inflector::camelize($source) . 'Source';
			App::import('DataSource', $plugin . '.' . $clz);
			$source = new $clz($config2);
			
			foreach ($source->selectByIp($config2, $ip, $ip_number) as $key => $value) {
				if (!empty($value)) $result[$key] = $value;
			}
		}

		return $result;
	}
	
}

