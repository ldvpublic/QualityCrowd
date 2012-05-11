<style type="text/css">
	html, body {
		margin:0;
		padding:20px;
	}

	div.qualification {
		margin-top:40px;
	}
	
	p {
		max-width: 800px;
	}
	
	h2 {
		border-bottom:1px solid;
		margin-top:40px;
		margin-bottom:7px;
	}
</style>

<?php

echo '<h1>' . $qualification['Qualification']['title'] . '</h1>';
echo '<p><i>' . $qualification['Qualification']['description'] . '</i></p>';
echo '<p>' . $qualification['Qualification']['question'] . '</p>';


$i = 1;

foreach($qualification['Video'] as $video) {
	echo '<div class="qualification">';
	echo '<h2>Video ' . $i . '</h2>';
	
	$videoTitle = '';
	
	if ($video['Video']['reference_id'] <> '') {
		echo $this->element('videoplayer', array(
			'id' => $video['Reference']['id'],
			'filename' => $video['Reference']['filename'],
			's3' => $video['Reference']['s3'],
			'width' => $video['Reference']['width'],
			'height' => $video['Reference']['height'],
			'title' => 'Undistorted original video, perfect quality',
		));
		
		$videoTitle = 'Distorted video for evaluation';
	}
	
	echo $this->element('videoplayer', array(
		'id' => $video['Video']['id'],
		'filename' => $video['Video']['filename'],
		's3' => $video['Video']['s3'],
		'width' => $video['Video']['width'],
		'height' => $video['Video']['height'],
		'title' => $videoTitle,
	));

	echo '<div class="answer-box">';
	echo '<p>' . $video['Video']['qualificationhint'] . '</p>';

	$a = $qualification['Answer'];
	$a['name'] = 'quality';
	echo $this->element('answers', $a);
	echo '</div>';
	
	echo '</div>';
	
	$i++;
}


?>