<?php

class GeoipCommonSource extends DataSource {
	
	var $schema = array(
		'area_code',
		'city',
		'continent_code',
		'country_code',
		'country_code3',
		'country_name',
		'dma_code',
		'gmt_offset',
		'ip',
		'is_dst',
		'latitude',
		'longitude',
		'metro_code',
		'organization',
		'postal_code',
		'region',
		'region_name',
		'registry',
		'state',
		'tech_contact',
		'timezone',
	);
	
	function __construct($config) {
		$this->config = $config;
		$this->name = Inflector::underscore(str_replace('Source', '', get_class($this)));
		Cache::config(sprintf('geoip_%s', $this->name), $this->_cacheConfig('+6 months'));
	}
	
	function _cacheConfig($default_cache_period) {
		return array(
			'engine' => 'File',  
			'duration' => isset($this->config['cache']) ? $this->config['cache'] : $default_cache_period,
			'path' => CACHE,
			'prefix' => sprintf('cake_geoip_%s_', $this->name),
		);
	}
	
	function _createGeoipRecord() {
		$record = array();
		foreach ($this->schema as $field) $record[$field] = false;
		return $record;
	}
	
	function describe($model) {
	}
	
	function listSources() {
		return array('geoips');
	}
	
	function create($model, $fields = array(), $values = array()) {
	}
	
	function _extractIp($model, $queryData) {
 		$ip = false;
		foreach ((array)@$queryData['conditions'] as $field => $value) {
			if (empty($value)) continue;
			list($key, $field) = pluginSplit($field);
			switch (true) {
				case ($key == $model->name) && (strtolower($field) == 'ip'):
				case ($key == '') && (strtolower($field) == 'ip'):
					$ip = $value;
			}
			if ($ip) break;
		}
		if (empty($ip)) $ip = $this->_currentIp();
		return $ip;
	}
	
	function _currentIp() { 
		switch (true) {
			case !empty($_SERVER['HTTP_CLIENT_IP']): return $_SERVER['HTTP_CLIENT_IP'];
			case !empty($_SERVER['HTTP_X_FORWARDED_FOR']): return $_SERVER['HTTP_X_FORWARDED_FOR'];
			default: return $_SERVER['REMOTE_ADDR'];
		}
	}
	
	function _convert($ip) {
		list($a, $b, $c, $d) = explode('.', $ip, 4);
		return 16777216 * $a + 65536 * $b + 256 * $c + $d;
	}
	
	function _transkey(&$result, $old_key, $new_key) {
		$result[$new_key] = $result[$old_key];
		unset($result[$old_key]);
	}
	
	function selectByIp($config, $ip, $ip_number) {
		return array();
	}
	
	function read($model, $queryData = array()) {
		$ip = $this->_extractIp($model, $queryData);
		$ip_number = $this->_convert($ip);
		
		$result = Cache::read($ip, sprintf('geoip_%s', $this->name));
		if (empty($result)) {
			$result = $this->_createGeoipRecord();
			foreach ($this->selectByIp($this->config, $ip, $ip_number) as $key => $value) {
				if (isset($result[$key])) $result[$key] = $value;
			}
			ksort($result);
			Cache::write($ip, $result, sprintf('geoip_%s', $this->name));
		}

		return array(array($model->name => $result));
	}
	
	function update($model, $fields = array(), $values = array()) {
	}
	
	function delete($model, $id = null) {
	}
	
	function query() {
		$args = func_get_args();
		$model = $args[2];
		$results = $this->read($model, array('conditions' => array($model->name . '.ip' => $args[1][0])));
		return preg_match('/^findBy/', $args[0]) ? array_shift($results) : $results;
	}

}

