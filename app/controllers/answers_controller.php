<?php
class AnswersController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'Answers';

	function index() {
		$this->set('answers', $this->Answer->find('all'));
	}

	function add() {
		if (!empty($this->data)) {

			$data = array();

			foreach($this->data['data'] as $row) {
				if ($row['value'] <> '') {
					$data[] = $row;
				}
			}
			
			unset($this->data['data']);
			$this->data['Answer']['answers'] = $data;

			if ($this->Answer->save($this->data)) {
				$this->Session->setFlash('Your answer has been saved.');
				$this->redirect(array('action' => 'index'));
			}
		}
	}
	
	function edit($id) {
		$this->Answer->id = $id;
		
		if (empty($this->data)) {
			$this->data = $this->Answer->read();
			$this->set('answers', $this->data['Answer']['answers']);
		} else {
			$data = array();

			foreach($this->data['data'] as $row) {
				if ($row['value'] <> '') {
					$data[] = $row;
				}
			}
			
			unset($this->data['data']);
			$this->data['Answer']['answers'] = $data;
			
			if ($this->Answer->save($this->data)) {
				$this->Session->setFlash('Your answer has been updated.');
				$this->redirect(array('action' => 'index'));
			}
		}
	}

	function delete($id) {
		if ($this->Answer->delete($id)) {
			$this->Session->setFlash('The answer with id: ' . $id . ' has been deleted.');
			$this->redirect(array('action' => 'index'));
		}
	}
}
?>
