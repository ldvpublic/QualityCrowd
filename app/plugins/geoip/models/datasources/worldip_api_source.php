<?php

$plugin = Inflector::camelize(basename(realpath(dirname(__FILE__) . '/../..')));
App::import('DataSource', $plugin . '.GeoipCommonSource');
unset($plugin);

class WorldipApiSource extends GeoipCommonSource {
	
	var $endpoint = 'http://api.wipmania.com/%s?%s';
	
	function selectByIp($config, $ip, $ip_number) {
		$country_code = trim(file_get_contents(sprintf($this->endpoint, $ip, $_SERVER['HTTP_HOST'])));
		return compact('ip', 'country_code');
	}
	
}

