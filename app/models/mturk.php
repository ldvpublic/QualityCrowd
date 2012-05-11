<?php
class Mturk extends AppModel {
	var $name = 'Mturk';
	var $useTable = false;

	public function getBalance() {
		$str = $this->_cliCommand('getBalance');

		$str = str_replace('Your account balance: $', '', $str);
		$str = str_replace(',', '.', $str);
		settype($str, 'float');

		return $str;

	}

	public function loadHITs($id) {
	
		$q = $id . '.question';
		$p = $id . '.properties';
		$i = $id . '.input';
		
		$r = $this->_cliCommand('loadHITs', array(
			 'properties' => $p,
			 'question' =>	$q,
			 'input' => $i,
			 //'preview' => '',
			 //'previewfile' => TMP . 'mturk/preview.html',
		));

		$data = array();
		$data['raw'] = $r;
		$data['groupId'] = $this->_getBetween('preview?groupId=', "\n", $r);

		$hids = explode('HITId=', $r);
		unset($hids[0]);
		foreach($hids as $hid) {
			$hid = substr($hid, 0, strpos($hid, "\n"));
			$data['HITIds'][] = $hid;
		}

		//debug($r);
		//debug(print_r($data, true));

		return $data;
	}
	
	public function deleteHITs($id) {
	
		$s = $id . '.input.success';
		
		$r = $this->_cliCommand('deleteHITs', array(
			'successfile' => $s,
			'force' => '',
			'approve' => '',
			'expire' => '',
		));

		$data = array();
		$data['raw'] = $r;
		
		if (strpos($r, "  0 errors occured.") !== false) {
			$data['error'] = false;
		} else {
			$data['error'] = true;
		}

		//debug($r);
		//debug(print_r($data, true));

		return $data;
	}
	
	
	public function createQualificationType($id) {
	
		$q = 'qual_' . $id . '.question';
		$p = 'qual_' . $id . '.properties';
		$a = 'qual_' . $id . '.answer';
		
		$r = $this->_cliCommand('createQualificationType', array(
			 'properties' => $p,
			 'question' =>	$q,
			 'answer' => $a,
		));

		$data = array();
		$data['raw'] = $r;
		$data['qualificationId'] = $this->_getBetween('qualificationId=', "\n", $r);
		//debug($r);
		//debug(print_r($data, true));

		return $data;
	}

	public function getResults($id) {
	
		$s = $id . '.input.success';
		$o = $id . '.results';
	
		// Results get cached for 30 minutes
		if (!file_exists(TMP . 'mturk/' . $o) || filemtime(TMP . 'mturk/' . $o) < time() - 60 * 30) {
			$r = $this->_cliCommand('getResults', array(
				 'successfile' => $s,
				 'outputfile' =>	$o,
			));
		} else {
			$r = '';
		}

		$data = array();
		$data['raw'] = $r;
		
		$o = TMP . 'mturk/' . $o;
		$res = file_get_contents($o);
		
		$res = explode("\n", $res);
		foreach($res as &$row) {
			$row = explode("\t", $row);
			$row = preg_replace(array('/^"/', '/"$/'), '', $row);
		}
		
		$results = array();
		for ($i = 1; $i < count($res); $i++) {
			if (count($res[$i]) > 1) {
				for($ii = 0; $ii < count($res[$i]); $ii++) {
					$results[$i - 1][$res[0][$ii]] = $res[$i][$ii];
				}
			}
		}
		
		$data['results'] = $results;

		//debug($r);
		//debug(print_r($data, true));

		return $data;
	}


	private function _cliCommand($cmd, $args = array()) {

		$this->_writePropertiesFile();

		$env = 'export JAVA_HOME=' . Configure::read('mturk.javahome');
		$cd = 'cd ' . TMP . 'mturk';
		$cli = Configure::read('mturk.clipath') . '/' . $cmd . '.sh';

		foreach($args as $name => $value) {
			$cli .= ' -' . $name . ' ' . $value;
		}

		$cmd = $env . ' && ' . $cd . ' && ' . $cli; //. ' 2>&1';
		//debug($cmd);

		$value = shell_exec($cmd);
		//debug( $value);

		return $value;
	}

	private function _writePropertiesFile() {
		$o  = 'access_key:' . Configure::read('mturk.accesskey') . "\n";
		$o .= 'secret_key:' . Configure::read('mturk.secretkey') . "\n";
		$o .= 'service_url:' . Configure::read('mturk.serviceurl') . "\n";
		$o .= "retriable_errors=Server.ServiceUnavailable,503\n";
		$o .= "retry_attempts=6\n";
		$o .= "retry_delay_millis=500\n";

		$propsFile = new File(TMP . 'mturk/mturk.properties', true);
		$propsFile->write($o);
		$propsFile->close();
	}

	private function _getBetween($start, $end, $haystack) {
		$a = strpos($haystack, $start);
		if ($a !== false) {
			$a += strlen($start);
			$b = strpos($haystack, $end, $a);
			return substr($haystack, $a, $b - $a);
		} else {
			return false;
		}
	}
}

?>
