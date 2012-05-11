<?php

class Qualification extends AppModel {
	var $name = 'Qualification';

	var $actsAs = array('Serializeable');

	var $belongsTo = array(
		'Answer' => array(
			'className'					=> 'Answer',
			'foreignKey'				=> 'answer_id'
		),
	);

	var $hasAndBelongsToMany = array(
		'Video' => array(
			'className'              => 'Video',
			'joinTable'              => 'qualifications_videos',
			'foreignKey'             => 'qualification_id',
			'associationForeignKey'  => 'video_id',
			'unique'                 => true,
			'conditions'             => '',
			'fields'                 => '',
			'order'                  => '',
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
		$o  = 'name=' . $data['Qualification']['title'] . "\n";
		$o .= 'description=' . $data['Qualification']['description']  . "\n";
		$o .= 'keywords=' . $data['Qualification']['keywords']  . "\n";

		$o .= 'testduration:' . $data['Qualification']['testduration'] . "\n";
		$o .= 'retrydelayinseconds:10' . "\n";

		$propsFile = new File(TMP . 'mturk/qual_' . $this->id . '.properties', true);
		$propsFile->write($o);
		$propsFile->close();

		// Question file
		$o  = '<?xml version="1.0" encoding="UTF-8" ?>';
		$o .= '<QuestionForm xmlns="http://mechanicalturk.amazonaws.com/AWSMechanicalTurkDataSchemas/2005-10-01/QuestionForm.xsd">';
		$o .= '<Overview>';
		$o .= '<FormattedContent><![CDATA[';
		$o .= $data['Qualification']['question'];
		$o .= ']]></FormattedContent>';
		$o .= '</Overview>';

		$i = 1;

		shuffle($data['Video']);

		foreach($data['Video'] as $v) {
			$url = Configure::read('baseurl') . '/qualifications/external/' . $this->id . '/' . $v['id'];

			$o .= '<Question>';

			$o .= '<QuestionIdentifier>Video' . $i .'</QuestionIdentifier>';
			$o .= '<IsRequired>false</IsRequired>';

			$o .= '<QuestionContent>';
			$o .= '<Title>Video ' . $i . '</Title>';
			$o .= '<FormattedContent><![CDATA[';
			$o .= '<iframe src="' . $url . '" frameborder="0" scrolling="no" height="330" width="770">Error!</iframe>';
			$o .= ']]></FormattedContent>';
	      $o .= '</QuestionContent>';

			$o .= '<AnswerSpecification>';
	      $o .= '<SelectionAnswer>';
	      $o .= '<MinSelectionCount>0</MinSelectionCount>';
	      $o .= '<StyleSuggestion>checkbox</StyleSuggestion>';
	      $o .= '<Selections>';
				$o .= '<Selection>';
	         $o .= '<SelectionIdentifier>watched</SelectionIdentifier>';
	         $o .= '<Text>Yes, I have watched the video.</Text>';
	         $o .= '</Selection>';
	      $o .= '</Selections>';
	      $o .= '</SelectionAnswer>';
	      $o .= '</AnswerSpecification>';
			$o .= '</Question>';

			$i++;
		}

		$o .= '</QuestionForm>';

		$questionFile = new File(TMP . 'mturk/qual_' . $this->id . '.question', true);
		$questionFile->write($o);
		$questionFile->close();

		// Answer file
		$o = '<?xml version="1.0" encoding="UTF-8"?>';
		$o .= '<AnswerKey xmlns="http://mechanicalturk.amazonaws.com/AWSMechanicalTurkDataSchemas/2005-10-01/AnswerKey.xsd">';

		$i = 1;
		foreach($data['Video'] as $v) {
			$o .= '<Question>';
			$o .= '<QuestionIdentifier>Video' . $i . '</QuestionIdentifier>';
				$o .= '<AnswerOption>';
				$o .= '<SelectionIdentifier>watched</SelectionIdentifier>';
				$o .= '<AnswerScore>1</AnswerScore>';
				$o .= '</AnswerOption>';
			$o .= '</Question>';
			$i++;
		}
		$o .= '</AnswerKey>';

		$inputFile = new File(TMP . 'mturk/qual_' . $this->id . '.answer', true);
		$inputFile->write($o);
		$inputFile->close();
	}
}

?>
