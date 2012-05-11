<style type="text/css">
	html, body {
		margin:0;
		padding:0;
	}
</style>


<?php 

echo $this->element('videoplayer', array(
	'id' => $video['Video']['id'],
	'filename' => $video['Video']['filename'],
	's3' => $video['Video']['s3'],
	'width' => $video['Video']['width'],
	'height' => $video['Video']['height'],
));

if ($video['Video']['reference_id'] <> '') {

	echo $this->element('videoplayer', array(
		'id' => $video['Reference']['id'],
		'filename' => $video['Reference']['filename'],
		's3' => $video['Reference']['s3'],
		'width' => $video['Reference']['width'],
		'height' => $video['Reference']['height'],
	)); 
	
}

?>