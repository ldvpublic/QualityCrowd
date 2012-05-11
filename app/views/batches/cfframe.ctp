<?php
$javascript->link('flashver.js', false);
$javascript->link('https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js', false);

$videoTitle = "";

if ($video['Video']['reference_id'] <> '') {
	echo $this->element('videoplayer', array(
		'id' => $video['Reference']['id'] . '-1',
		'filename' => $video['Reference']['filename'],
		's3' => $video['Reference']['s3'],
		'width' => $video['Reference']['width'],
		'height' => $video['Reference']['height'],
		'title' => 'Undistorted original video, perfect quality',
	));	
	
	$videoTitle = 'Distorted video for evaluation';
}

echo $this->element('videoplayer', array(
	'id' => $video['Video']['id'] . '-2',
	'filename' => $video['Video']['filename'],
	's3' => $video['Video']['s3'],
	'width' => $video['Video']['width'],
	'height' => $video['Video']['height'],
	'title' => $videoTitle,
));


?>

<p style="margin-bottom:10px;"><?php echo $batch['Question']['text']; ?></p>

<?php
$a = $batch['Answer'];
$a['name'] = 'quality';
echo $this->element('answers', $a);
?>

<script type="text/javascript">
	
	var videoWatched = false;
	<?php if ($video['Video']['reference_id'] <> ''): ?>
	var video1watched = false;
	var video2watched = false;
	<?php endif; ?>
	
	$('input[name=quality]').change( function() {
		sendValues();
	});
	
	function onVideoComplete(videoid) {
		<?php if ($video['Video']['reference_id'] <> ''): ?>
		if (videoid == '<?= ($video['Reference']['id'] . '-1') ?>') {
			video1watched = true;
		}
		if (videoid == '<?= ($video['Video']['id'] . '-2') ?>') {
			video2watched = true;
		}
		if (video1watched == true && video2watched == true) {
			videoWatched = true;
			$('input[name=quality_watched]').val(1);
			sendValues();
		}
		
		<?php else: ?>		
		videoWatched = true;
		$('input[name=quality_watched]').val(1);
		sendValues();
		<?php endif; ?>
	}
	
	function sendValues() {
		window.parent.postMessage('<?= $video['Video']['id'] ?>' + 
				':' + $('div#content').height() +
				':' + $('input[name=quality]').val() + 
				':' + $('input[name=quality_text]').val() +
				':' + $('input[name=quality_answered]').val() + 
				':' + $('input[name=quality_watched]').val(), '*');
	}
	
	$(document).ready(function () {
		sendValues();
	});
</script>
