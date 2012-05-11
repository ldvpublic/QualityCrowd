<?php
class PagesController extends AppController {
	var $name = 'Pages';
	var $uses = array('Mturk', 'Crowdflower');

	public function mturk() {
		$this->set('balance', $this->Mturk->getBalance());
	}

	public function cf() {
		debug(print_r($this->Crowdflower->getJobs(), true));
	}

}
