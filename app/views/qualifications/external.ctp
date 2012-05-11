<style type="text/css">
	html, body {
		margin:0;
		padding:0;
	}

	div.answer-box {
		float:left;
		margin-left:15px;
		width:400px;
	}

	div.video-box {
		float:left;
	}
</style>

<?php

foreach($qualification['Video'] as $video) {
	if ($video['id'] == $videoid) break;
}



echo $this->element('videoplayer', array(
	'id' => $video['id'],
	'filename' => $video['filename'],
	's3' => $video['s3'],
	 'width' => $video['width'],
	'height' => $video['height'],
));

echo '<div class="answer-box">';
echo '<p>' . $video['qualificationhint'] . '</p>';

$a = $qualification['Answer'];
$a['name'] = 'quality';
echo $this->element('answers', $a);
echo '</div>'

?>