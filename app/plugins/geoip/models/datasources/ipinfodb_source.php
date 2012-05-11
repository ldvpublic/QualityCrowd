<?php

$plugin = Inflector::camelize(basename(realpath(dirname(__FILE__) . '/../..')));
App::import('DataSource', $plugin . '.GeoipCommonSource');
unset($plugin);

class IpinfodbSource extends GeoipCommonSource {
	
	var $endpoint = 'http://api.ipinfodb.com/v3/ip-city/?key=%s&ip=%s&format=json&timezone=true';
	
	function selectByIp($config, $ip, $ip_number) {
		$result = array();
		foreach (json_decode(file_get_contents(sprintf($this->endpoint, $config['api_key'], $ip)), true) as $key => $value) {
			$result[strtolower($key)] = $value;
		}
		$this->_transkey($result, 'countrycode', 'country_code');
		$this->_transkey($result, 'countryname', 'country_name');
		$this->_transkey($result, 'regionname', 'region_name');
		$this->_transkey($result, 'cityname', 'city');
		$this->_transkey($result, 'zipcode', 'postal_code');
		$this->_transkey($result, 'timezone', 'gmt_offset');
		return $result;
	}
	
}

