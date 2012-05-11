<?php
class QuestionsController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'Questions';

	function index() {
		$this->set('questions', $this->Question->find('all'));
	}

	function view($id = null) {
		$this->Question->id = $id;
		$this->set('question', $this->Question->read());
	}

	function add() {
		if (!empty($this->data)) {
			if ($this->Question->save($this->data)) {
				$this->Session->setFlash('Your Question has been saved.');
				$this->redirect(array('action' => 'index'));
			}
		}
	}
	
	function edit($id = null) {
		$this->Question->id = $id;
		if (empty($this->data)) {
			$this->data = $this->Question->read();
		} else {
			if ($this->Question->save($this->data)) {
				$this->Session->setFlash('Your question has been updated.');
				$this->redirect(array('action' => 'index'));
			}
		}
	}

	function delete($id) {
		if ($this->Question->delete($id)) {
			$this->Session->setFlash('The Question with id: ' . $id . ' has been deleted.');
			$this->redirect(array('action' => 'index'));
		}
	}
}
?>
