<?php
class VideosController extends AppController {
	var $helpers = array ('Html', 'Form', 'Javascript');
	var $name = 'Videos';
	var $components =  array('Auth', 'Session', 'Amazon-Upload.Upload');

	function beforeFilter() {
		$this->Auth->allow('external');
	}

	function index() {
		$this->set('videos', $this->Video->find('all', array(
			'order' => array('Video.group_id', 'Video.title')
		)));
	}

	function view($id = null) {
		$this->Video->id = $id;
		$this->set('video', $this->Video->read());
	}

	function external($id) {
		$this->layout = 'plain';
		$this->Video->id = $id;
		$this->set('video', $this->Video->read());
	}

	function add() {
		$referenceVideos = $this->Video->find('list', array(
			'conditions' => array('Video.isreference' => 1),
		));
		$this->set('referenceVideos', $referenceVideos);
			
		if (!empty($this->data)) {
			if ($this->Video->save($this->data)) {
				$this->Session->setFlash('Your video has been saved.');
				$this->redirect(array('action' => 'index'));
			}
		}
	}

	function delete($id) {
		if ($this->Video->delete($id)) {
			$this->Session->setFlash('The video with id: ' . $id . ' has been deleted.');
			$this->redirect(array('action' => 'index'));
		}
	}

	function edit($id = null) {
		$this->Video->id = $id;
		
		if (empty($this->data)) {
			$this->data = $this->Video->read();
			$referenceVideos = $this->Video->find('list', array(
				'conditions' => array('Video.isreference' => 1),
			));
			$this->set('referenceVideos', $referenceVideos);
			
		} else {
			if ($this->Video->save($this->data)) {
				$this->Session->setFlash('Your video has been updated.');
				$this->redirect(array('action' => 'index'));
			}
		}
	}
	
	function sendToS3($id) {
		$this->uploadToS3($id);
		
		$this->Session->setFlash('The video has been sent to Amazon S3');
		$this->redirect(array('action' => 'index'));
	}
	
	function sendAllToS3() {
		if (!empty($this->data)) {
			foreach($this->data['Video'] as $itemId) {
				$this->uploadToS3($itemId);
			}
			
			$this->Session->setFlash('The videos have been sent to Amazon S3');
			$this->redirect(array('action' => 'index'));
		}
	}

	private function uploadToS3($id) {
		$this->Video->id = $id;
		$data = $this->Video->read();
		$data = $data['Video'];
				
		$bucket = Configure::read('s3bucket');
		$filename = $id . '-' . $data['filename'];
		$localpath = WWW_ROOT . 'files/videos/' . $id . '/' . $data['filename'];
		
		$file = array(
			  'name' => $filename,
			  'type' => 'video/mp4',
			  'tmp_name' => $localpath,
			  'error' => 0,
			  'size' => filesize($localpath),
		);
			
		$url = $this->Upload->put($file, $bucket, $id . '-');
		
		$this->Video->set('s3', basename($url));
		$this->Video->save();
	}

	function addfolder() {
		if (!empty($this->data)) {

			$path = $this->data['Video']['path'];
			$groupId = $this->data['Video']['group_id'];
			$width = $this->data['Video']['width'];
			$height = $this->data['Video']['height'];

			if ($handle = opendir($path)) {

				$data = array();

				while (false !== ($file = readdir($handle))) {
					if (preg_match('#.*(\.mp4|\.flv)$#i', $file)) {

						$title = $file;
						$title = str_replace('-ll.mp4', '', $title);
						$title = str_replace('.mp4', '', $title);
						$title = str_replace('.flv', '', $title);
						$title = Inflector::humanize($title);

						$data[] = array(
							 'title' => $title,
							 'group_id' => $groupId,
							 'width' => $width,
							 'height' =>  $height,
							 'videofile' => array(
								  'name' => $file,
								  'type' => 'video/mp4',
								  'tmp_name' => $path . DIRECTORY_SEPARATOR . $file,
								  'error' => 0,
								  'size' => filesize($path . DIRECTORY_SEPARATOR . $file),
							 )
						);
					}
				}

				closedir($handle);

				$this->Video->saveAll($data);
			}

			$this->Session->setFlash('Your videos have been saved.');
			$this->redirect(array('action' => 'index'));
		}
	}
}
?>