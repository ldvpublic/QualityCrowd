<?php

class Batch extends AppModel {
	var $name = 'Batch';

	var $actsAs = array('Serializeable');

	var $belongsTo = array(
		'Question' => array(
			'className'					=> 'Question',
			'foreignKey'				=> 'question_id'
		),
		'Answer' => array(
			'className'					=> 'Answer',
			'foreignKey'				=> 'answer_id'
		),
		'Qualification' => array(
			'className'					=> 'Qualification',
			'foreignKey'				=> 'qualification_id'
		),
	);

	var $hasAndBelongsToMany = array(
		'Video' => array(
			'className'              => 'Video',
			'joinTable'              => 'batches_videos',
			'foreignKey'             => 'batch_id',
			'associationForeignKey'  => 'video_id',
			'unique'                 => true,
			'conditions'             => '',
			'fields'                 => '',
			'order'                  => 'title',
			'limit'                  => '',
			'offset'                 => '',
			'finderQuery'            => '',
			'deleteQuery'            => '',
			'insertQuery'            => ''
		)
	);

	public function writeMTurkFiles() {
		$data = $this->read();

		// Properties file
		$o  = 'title:' . $data['Batch']['title'] . "\n";
		$o .= 'description:' . $data['Batch']['description']  . "\n";
		$o .= 'keywords:' . $data['Batch']['keywords']  . "\n";

		$o .= 'reward:' . $data['Batch']['payment'] . "\n";
		$o .= 'assignments:' . $data['Batch']['assignments'] . "\n";
		$o .= 'assignmentduration:' . $data['Batch']['assignmentduration'] . "\n";
		$o .= 'hitlifetime:' . $data['Batch']['hitlifetime']   . "\n";
		$o .= 'autoapprovaldelay:86400' . "\n";

		$o .= 'qualification.1:' . $data['Qualification']['mturkid'] . "\n";
		$o .= 'qualification.comparator.1:greaterthanorequalto' . "\n";
		$o .= 'qualification.value.1:0' . "\n";
		$o .= 'qualification.private.1:false' . "\n";

		$propsFile = new File(TMP . 'mturk/' . $this->id . '.properties', true);
		$propsFile->write($o);
		$propsFile->close();

		// Question file 
		$url = Configure::read('baseurl') . '/batches/external/' . $this->id . '/${video_id}';
				
		$o  = '<?xml version="1.0" encoding="UTF-8" ?>';
		$o .= '<ExternalQuestion xmlns="http://mechanicalturk.amazonaws.com/AWSMechanicalTurkDataSchemas/2006-07-14/ExternalQuestion.xsd">';
		$o .= '<ExternalURL>' . $url . '</ExternalURL>';
		$o .= '<FrameHeight>650</FrameHeight>';
		$o .= '</ExternalQuestion>';
		

		$questionFile = new File(TMP . 'mturk/' . $this->id . '.question', true);
		$questionFile->write($o);
		$questionFile->close();


		// Input file
		$o = "video_id\tfilename\n";

		foreach($data['Video'] as $video) {
			$o .= $video['id'] . "\t" . $video['filename'] . "\n";
		}

		$inputFile = new File(TMP . 'mturk/' . $this->id . '.input', true);
		$inputFile->write($o);
		$inputFile->close();
	}
}

?>
