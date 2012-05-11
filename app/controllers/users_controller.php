<?php

class UsersController extends AppController {
    var $name = 'Users';

    /**
     *  The AuthComponent provides the needed functionality
     *  for login, so you can leave this function blank.
     */
    function login() {
    }

    function logout() {
        $this->redirect($this->Auth->logout());
		  
    }
	 
	 function index() {
		 $this->set('users', $this->User->find('all'));
	 }
	 
	 function add() {
		 if (!empty($this->data)) {
			 
			if ($this->data['User']['pwd1'] <> $this->data['User']['pwd2']) {
				$this->Session->setFlash('The two passwords do not match.');
				return;
			}
			
			$this->data['User']['password'] =  $this->Auth->password($this->data['User']['pwd1']);
			 
			if ($this->User->save($this->data)) {
				$this->Session->setFlash('The user has been created.');
				$this->redirect(array('action' => 'index'));
			}
		}
	 }
	 
	 function edit($id = null) {
		$this->User->id = $id;
		
		if (empty($this->data)) {
			$this->data = $this->User->read();
			
		} else {
						
			if ($this->data['User']['pwd1'] <> $this->data['User']['pwd2']) {
				$this->Session->setFlash('The two passwords do not match.');
				return;
			}
			
			$this->data['User']['password'] =  $this->Auth->password($this->data['User']['pwd1']);
			 
			
			if ($this->User->save($this->data)) {
				$this->Session->setFlash('The user has been updated.');
				$this->redirect(array('action' => 'index'));
			}
		}
	}
	 
	 function delete($id) {
		if ($this->User->delete($id)) {
			$this->Session->setFlash('The user with id: ' . $id . ' has been deleted.');
			$this->redirect(array('action' => 'index'));
		}
	}
}

?>