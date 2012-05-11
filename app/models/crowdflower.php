<?php

App::import('Core', array('HttpSocket'));

class Crowdflower extends AppModel {
	var $name = 'Crowdflower';
	var $useTable = false;


	public function getJobs() {
		return $this->_restCall('get', 'jobs');
	}

	public function createJob($properties) {
		
		$params = array();
		
		foreach($properties as $key => $val) {
			$params['job[' . $key . ']'] = $val;
		}
		
		return $this->_restCall('post', 'jobs', $params);
		
	}
	
	public function deleteJob($jobid) {
		return $this->_restCall('delete', 'jobs/' . $jobid);
	}
	
	public function addGold($jobid, $field) {
		$params = array('check' => $field);
		return $this->_restCall('put', 'jobs/' . $jobid . '/gold', $params);
		
	}
	
	
	public function uploadUnits($jobid, $units) {
		$json = '';
		
		foreach($units as $unit) {
			$json .= json_encode($unit);
		}
	
		
		return $this->_restCall('post', 'jobs/' . $jobid . '/upload', $json);
	}
	
	
	private function _restCall($action, $method, $data = null, $header = null) {
		
		$http = new HttpSocket();
		
		$apikey = Configure::read('cf.apikey');
		$apiurl = Configure::read('cf.apiurl');
		
		$url = $apiurl . '/' . $method . '.json?key=' . $apikey;
		
		$request = array();

		if (!is_array($data)) {
			$request['header'] = array('Content-Type' => 'application/json');
		}
			
		switch(strtolower($action)) {
			default:
				die('undefined action');
				break;
				
			case 'get':
				$r = $http->get($url, $data, $request);
				break;
			
			case 'post':
				$r = $http->post($url, $data, $request);
				break;
		
			case 'put':
				$r = $http->put($url, $data, $request);
				break;
			
			case 'delete':
				$r = $http->delete($url, $data, $request);
				break;
		}
		
		return json_decode($r);
		
	}
}

?>
