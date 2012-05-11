<?php
$javascript->link('flashver.js', false);
$javascript->link('https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js', false);


echo $batch['Question']['description'];

if (isset($_GET['assignmentId']) && $_GET['assignmentId'] <> 'ASSIGNMENT_ID_NOT_AVAILABLE') {
	
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

	
} else {
	echo '<div style="background:#ccc; width:352px; height:208px;
		font-size:90px; color:#bbb; text-align:center; padding-top:80px;">Video</div>';

	$video = array('Video' => array('id' => -1, 'title' => '---preview---'));
}
?>

<p style="margin-bottom:10px;"><?php echo $batch['Question']['text']; ?></p>

<form id="mturk_form" method="POST" action="https://workersandbox.mturk.com/mturk/externalSubmit">
	<input type="hidden" id="assignmentId" name="assignmentId" value="">

	<?php
	$a = $batch['Answer'];
	$a['name'] = 'quality';
	echo $this->element('answers', $a);
	?>

	<input type="hidden" name="remotehost" value="<?php echo gethostbyaddr($_SERVER['REMOTE_ADDR']); ?>">
	<input type="hidden" name="remoteip" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>">
	<input type="hidden" name="useragent" value="<?php echo $_SERVER['HTTP_USER_AGENT']; ?>">
	<input type="hidden" name="videoname" value="<?php echo $video['Video']['title']; ?>">
	<input type="hidden" name="videoid" value="<?php echo $video['Video']['id']; ?>">
	<input type="hidden" name="flashver" id="flashver" value="">
	<input type="hidden" name="screenres" id="screenres" value="">
	<input type="hidden" name="screendepth" id="screendepth" value="">
	<input type="hidden" name="timer" id="timer" value="0">

	<input id="submitButton" type="submit" value="Submit">

</form>

<script type="text/javascript">
	//
	// This method Gets URL Parameters (GUP)
	//
	function gup( name ) {
		var regexS = "[\\?&]"+name+"=([^&#]*)";
		var regex = new RegExp( regexS );
		var tmpURL = window.location.href;
		var results = regex.exec( tmpURL );
		if( results == null )
			return "";
		else
			return results[1];
	}

	//
	// This method decodes the query parameters that were URL-encoded
	//
	function decode(strToDecode) {
		var encoded = strToDecode;
		return unescape(encoded.replace(/\+/g,  " "));
	}

	function onVideoComplete() {
		enableForm();
	}

	function enableForm() {
		var form = document.getElementById('mturk_form');
		if (document.referrer && ( document.referrer.indexOf('workersandbox') != -1) ) {
			form.action = "https://workersandbox.mturk.com/mturk/externalSubmit";
		}
		document.getElementById('submitButton').disabled = false;
		document.getElementById('submitButton').value = "Submit";
	}


	// fill statistic variables

	$("#flashver").val(GetSwfVer());
	$("#screenres").val(screen.width + 'x' + screen.height);
	$("#screendepth").val(screen.colorDepth);

	window.setInterval(function () {
		$('#timer').val(parseInt($('#timer').val()) + 1);
	}, 1000);


	// process url parameters

	document.getElementById('assignmentId').value = gup('assignmentId');

	//
	// Check if the worker is PREVIEWING the HIT or if they've ACCEPTED the HIT
	//
	if (gup('assignmentId') == "ASSIGNMENT_ID_NOT_AVAILABLE") {
		// If we're previewing, disable the button and give it a helpful message
		document.getElementById('submitButton').disabled = true;
		document.getElementById('submitButton').value = "You must ACCEPT the HIT before you can submit the results.";
		dontShowVideo = true;
	} else {
		document.getElementById('submitButton').disabled = true;
		document.getElementById('submitButton').value = "Watch the video now...";
	}
</script>
