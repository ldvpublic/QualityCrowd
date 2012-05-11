<?php
class BatchesController extends AppController {
	var $uses = array('Batch', 'Mturk', 'Geoip', 'Crowdflower', 'Video');
	var $helpers = array ('Html','Form', 'Number', 'Javascript');
	var $name = 'Batches';
	var $components = array('RequestHandler');

	function beforeFilter() {
		$this->Auth->allow('external', 'CFframe');
	}

	function index() {
		$this->set('batches', $this->Batch->find('all'));
	}

	function add() {
		$this->set('questions', $this->Batch->Question->find('list'));
		$this->set('qualifications', $this->Batch->Qualification->find('list'));
		$this->set('videos', $this->Batch->Video->find('list'));
		$this->set('answers', $this->Batch->Answer->find('list'));

		if (!empty($this->data)) {
			if ($this->Batch->save($this->data)) {
				$this->Session->setFlash('Your Batch has been saved.');
				$this->redirect(array('action' => 'index'));
			}
		}
	}
	
	function edit($id = null) {
		$this->Batch->id = $id;
		
		if (empty($this->data)) {
			$this->data = $this->Batch->read();
			
			$this->set('questions', $this->Batch->Question->find('list'));
			$this->set('qualifications', $this->Batch->Qualification->find('list'));
			$this->set('videos', $this->Batch->Video->find('list'));
			$this->set('answers', $this->Batch->Answer->find('list'));
			
		} else {
			if ($this->Batch->save($this->data)) {
				$this->Session->setFlash('Your Batch has been updated.');
				$this->redirect(array('action' => 'index'));
			}
		}
	}

	function view($id = null) {
		$this->Batch->id = $id;
		$this->set('batch', $this->Batch->read());
	}
	
	function delete($id) {
		if ($this->Batch->delete($id)) {
			$this->Session->setFlash('The Batch with id: ' . $id . ' has been deleted.');
			$this->redirect(array('action' => 'index'));
		}
	}
	
	function external($id) {
		$this->layout = 'plain';
		$this->Batch->id = $id;
		$this->set('batch', $this->Batch->read());

		if (isset($this->params['pass'][1])) {
			$this->Video->id = $this->params['pass'][1];
			$this->set('video', $this->Video->read());
		} else {
			$this->set('videoid', -1);
		}
	}

	function cfframe($id) {
		$this->layout = 'plain';
		$this->Batch->id = $id;
		$this->set('batch', $this->Batch->read());

		if (isset($this->params['pass'][1])) {
			$this->Video->id = $this->params['pass'][1];
			$this->set('video', $this->Video->read());
		} else {
			$this->set('videoid', -1);
		}
	}
	
	function CFpublish($id) {
		$this->Batch->id = $id;
		$data = $this->Batch->read();
		
		// Create Job Page
		
		$url = Configure::read('baseurl') . '/batches/cfframe/' . $id . '/{{videoid}}';
		
		$page = '<iframe id="qc{{videoid}}" src="' . $url . '" height="600" width="800"> </iframe>
			
			<p>The following both fields will be filled in automatically after answering 
				the question or watching the video respectively.</p>
			
			<cml:text name="watched" label="Video watched completely?" validates="regex" data-validates-regex="^Yes$" data-validates-regex-message="Please watch the entire video." default="No"/>
			<cml:text name="answered" label="Question answered?" validates="regex" data-validates-regex="^Yes$" data-validates-regex-message="Please answer the question above." default="No"/>
			<cml:hidden name="qualityrating" validates="required"/>
			<cml:hidden name="textrating" gold="textrating_gold"/>
			<cml:hidden name="useragent" validates="user_agent" />

			<script>window.jQuery || document.write(\'<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js">\x3C/script>\')</script>

			<script type="text/javascript">
				jQuery.noConflict();

				jQuery("input").keypress(function(event) {
					event.preventDefault();
					jQuery(event.target).blur();
				});

				jQuery("input").mousedown(function(event) {
					event.preventDefault();
					jQuery(event.target).blur();
				});

				jQuery("input").focus(function(event) {
					jQuery(event.target).blur();
				});

				window.addEventListener("message", receiveMessage, false);

				function receiveMessage(event)
				{
					var data = event.data.split(":");
					if (data[0] == "{{videoid}}") {
						jQuery("#qc{{videoid}}").height(parseInt(data[1])+40);
						jQuery("#qc{{videoid}}").parents("div.cml").find("input.qualityrating").val(data[2]);
						jQuery("#qc{{videoid}}").parents("div.cml").find("input.textrating").val(data[3]);

						jQuery("#qc{{videoid}}").parents("div.cml").find("input.answered").val((data[4] == 1 ? "Yes" : "No"));
						jQuery("#qc{{videoid}}").parents("div.cml").find("input.answered").focus();
						jQuery("#qc{{videoid}}").parents("div.cml").find("input.answered").blur();

						jQuery("#qc{{videoid}}").parents("div.cml").find("input.watched").val((data[5] == 1 ? "Yes" : "No"));
						jQuery("#qc{{videoid}}").parents("div.cml").find("input.watched").focus();
						jQuery("#qc{{videoid}}").parents("div.cml").find("input.watched").blur();
					}
				}
			</script>';
		
		$css = '';
		
		
		// Create Job
		$properties = array(
			'title' => $data['Batch']['title'],
			'instructions' => $data['Question']['description'],
			'problem' => $page,
			'css' => $css,
			'judgments_per_unit' => $data['Batch']['assignments'],
			 
		);
		
		$r = $this->Crowdflower->createJob($properties);
	
		
		if (!isset($r->error)) {
			$this->Batch->saveField('cf_jobid', $r->id);
		} else {
			$this->Session->setFlash('An error occured: ' . $r->error->message);
			$this->redirect(array('action' => 'index'));
			return false;
		}
		
		$jobid = $r->id;
		
		
		// Prepare golden textratings from answer definition
		$goldTexts = "";
		
		foreach($data['Answer']['answers'] as $row) {
			if ($row['gold']) {
				$goldTexts .= $row['text'] . "\n";
			}
		}
		
		$goldTexts = trim($goldTexts);
		
		
		// Upload Data
		$units = array();
		$anyGold = false;
		
		foreach($data['Video'] as $video) {
			
			$unit = array(
				 'videoid' => $video['id'],
				 'title' => $video['title'],
				 'filename' => $video['filename'],
			);
			
			if($video['isreference']) {
				$anyGold = true;
				
				$textrating_reason = "This video had excellent quality. Watch the videos more carefully, please. ";
				$textrating_reason .= "If you are not sure how to rate the videos correctly, we recommend to do the training as described in the instructions.";
				
				$unit['textrating_gold'] = $goldTexts;
				$unit['textrating_gold_reason'] = $textrating_reason;
			} 			
			
			$units[] = (object) $unit;
		}
		
		$r = $this->Crowdflower->uploadUnits($jobid, $units);
		
		
		// Add Gold
		if ($anyGold) {
			sleep(3);
			$r = $this->Crowdflower->addGold($jobid, 'textrating');
		}
		
		
		if (!isset($r->error)) {
			$this->Session->setFlash('The Batch with id: ' . $id . ' has been published at Crowdflower.');
		} else {
			$this->Session->setFlash('An error occured: ' . $r->error->message);
		}

		$this->redirect(array('action' => 'index'));
	}
	
	function CFunpublish($id) {	
		$this->Batch->id = $id;
		$data = $this->Batch->read();
		
		$r = $this->Crowdflower->deleteJob($data['Batch']['cf_jobid']);
	
		if (isset($r->error)) {
			$this->Session->setFlash('An error occured: ' . $r->error->message);
		} else {
			$this->Session->setFlash('The Batch with id: ' . $id . ' has been unpublished from Crowdflower');
			$this->Batch->id = $id;
			$this->Batch->saveField('cf_jobid', null);
		}
		
		$this->redirect(array('action' => 'index'));
	}
	

	function MTpublish($id) {
		$this->Batch->id = $id;
		$data = $this->Batch->read();

		if ($data['Qualification']['mturkid'] == '') {
			$this->Session->setFlash('Error: the assigned qualification has not been published.');
			$this->redirect(array('action' => 'index'));
		} else {
		
			$this->Batch->writeMTurkFiles();
			$data = $this->Mturk->loadHITs($id);

			if ($data['groupId'] <> '') {
				$this->Batch->saveField('mturk_groupid', $data['groupId']);
				$this->Session->setFlash('The Batch with id: ' . $id . ' has been published at MTurk.');
			} else {
				$this->Session->setFlash('An error occured: ' . $data['raw']);
			}

			$this->redirect(array('action' => 'index'));

		}
	}
	
	function MTunpublish($id) {	
		$data = $this->Mturk->deleteHITs($id);
		
		if ($data['error']) {
			$this->Session->setFlash('An error occured: ' . $data['raw']);
		} else {
			$this->Session->setFlash('The Batch with id: ' . $id . ' has been unpublished from MTurk.');
			$this->Batch->id = $id;
			$this->Batch->saveField('mturk_groupid', '');
		}
		$this->redirect(array('action' => 'index'));
	}


	function MTresults($id) {
		$this->Batch->id = $id;
		$this->set('batch', $this->Batch->read());
		
		$data = $this->Mturk->getResults($id);
		$data = $data['results'];
		
		$mos = array();
		
		foreach($data as &$row) {
			if (isset($row['Answer.quality']) && $row['Answer.quality'] <> '') {
				
				if (filter_var($row['Answer.remoteip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
					$geodata = $this->Geoip->findByIp($row['Answer.remoteip']);
					$row['geo.countrycode'] = $geodata['Geoip']['country_code'];
					$row['geo.city'] = $geodata['Geoip']['city'];
					$row['geo.country'] = $geodata['Geoip']['country_name'];
				} else {
					$row['geo.countrycode'] = '';
					$row['geo.city'] = '';
					$row['geo.country'] = '';
				}
					
				if (!isset($mos[$row['Answer.videoid']])) {
					$mos[$row['Answer.videoid']] = array(
						'count' => 0, 
						'value' => 0,
						'timer' => 0,
						
					);
				}
				
				$mos[$row['Answer.videoid']]['name'] = $row['Answer.videoname'];
				$mos[$row['Answer.videoid']]['count']++;
				$mos[$row['Answer.videoid']]['value'] += $row['Answer.quality'];
				$mos[$row['Answer.videoid']]['timer'] += $row['Answer.timer'];	
			}
		}
		
		foreach($mos as &$row) {
			$row['value'] = $row['value'] / $row['count'];
			$row['timer'] = $row['timer'] / $row['count'];
		}
		
		$this->set('mos', $mos);
		$this->set('results', $data);
	}
}
?>