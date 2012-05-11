<?php

class Video extends AppModel {
	var $name = 'Video';
	
	var $belongsTo = array(
		'Reference' => array(
			'className'		=> 'Video',
			'foreignKey'	=> 'reference_id'
		)
	);
	
	var $virtualFields = array(
    'fulltitle' => "CONCAT(Video.group_id, ': ', Video.title)"
	);
	
	var $displayField = 'fulltitle';
	var $order = 'fulltitle';

	public $actsAs = array('FileModel'=>
		array(
		  'dir'=>array('files/videos'),
		  'file_field'=>array('videofile'),
		  'file_db_file'=>array('filename')
		)
	);

	var $validate = array(
		'title' => array(
			'rule' => 'notEmpty'
		)
	);
}

?>
