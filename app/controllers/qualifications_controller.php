<?php
class QualificationsController extends AppController {
	var $uses = array('Qualification', 'Mturk', 'Video');
	var $helpers = array ('Html', 'Form', 'Number', 'Javascript');
	var $name = 'Qualifications';

	function beforeFilter() {
		$this->Auth->allow('external', 'externalpage');
	}

	function index() {
		$this->set('qualifications', $this->Qualification->find('all'));
	}

	function add() {
		$this->set('videos', $this->Qualification->Video->find('list'));
		$this->set('answers', $this->Qualification->Answer->find('list'));

		if (!empty($this->data)) {
			if ($this->Qualification->save($this->data)) {
				$this->Session->setFlash('Your Qualification has been saved.');
				$this->redirect(array('action' => 'index'));
			}
		}
	}
	
	function edit($id = null) {
		$this->Qualification->id = $id;
		
		if (empty($this->data)) {
			$this->data = $this->Qualification->read();
			$this->set('videos', $this->Qualification->Video->find('list'));
			$this->set('answers', $this->Qualification->Answer->find('list'));

		} else {
			if ($this->Qualification->save($this->data)) {
				$this->Session->setFlash('Your Qualification has been updated.');
				$this->redirect(array('action' => 'index'));
			}
		}
	}
	
	function view($id = null) {
		$this->Qualification->id = $id;
		$this->set('qualification', $this->Qualification->read());
	}

	function delete($id) {
		if ($this->Qualification->delete($id)) {
			$this->Session->setFlash('The Qualification with id: ' . $id . ' has been deleted.');
			$this->redirect(array('action' => 'index'));
		}
	}

	function publish($id) {
		$this->Qualification->id = $id;
		$this->Qualification->writeMTurkFiles();

		$data = $this->Mturk->createQualificationType($id);

		if ($data['qualificationId'] <> '') {
			$this->Qualification->saveField('mturkid', $data['qualificationId']);
			$this->Session->setFlash('The Qualification with id: ' . $id . ' has been published.');
		} else {
			$this->Session->setFlash('An error occured: ' . $data['raw']);
		}

		$this->redirect(array('action' => 'index'));
	}

	function external($id) {
		$this->layout = 'plain';
		$this->Qualification->id = $id;
		$this->set('qualification', $this->Qualification->read());
		
		if (isset($this->params['pass'][1])) {
			$this->set('videoid', $this->params['pass'][1]);
		} else {
			$this->set('videoid', -1);
		}
	}
	
	function externalpage($id) {
		$this->layout = 'plain';
		$this->Qualification->id = $id;
		
		$data = $this->Qualification->read();
		
		foreach($data['Video'] as &$video) {
			$this->Video->id = $video['id'];
			$video = $this->Video->read();
		}
		
		$this->set('qualification', $data);
	}
}
?>