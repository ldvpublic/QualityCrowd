<?php
$uid = uniqid();

$root = 'https://' . $_SERVER['HTTP_HOST'];
$swf = $root . $this->webroot . 'files/flash/qcplayer.swf';
$buttons = $root . $this->webroot . 'img/playerbuttons.png';
$jsdir = $root . $this->webroot . 'js/';

if ($s3 == '') {
	$vid = $root . $this->webroot . 'files/videos/' . $id . '/' . $filename;
} else {
	$vid = Configure::read('cloudfronturl');
	$vid .= '/' . $s3;
}

if (preg_match('/\.mp4$/i', $filename)) {
	$html5 = true;
} else {
	$html5 = false;
}

$javascript->link('flashver.js', false);
$javascript->link('swfobject.js', false);
$javascript->link('https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js', false);
?>

<style type="text/css">
	div.video-box {
		width:<?= $width ?>px;
		min-height:<?= ($height + 28) ?>px;
		margin-top:10px;
		margin-bottom:10px;
		display:inline-block;
	}

	div.video-box * {
		outline:0;
	}
	
	div.videotitle {
		margin-top:0px;
		margin-bottom:5px;
		font-size:16px;
		font-weight:bold;
	}
	
	div.flash-fallback {
		display:none; 
		padding:10px; 
		border: 1px solid red;
	}

<?php if($html5): ?>
	video {
		background-color:#ccc;
	}

	div.button {
		width:24px;
		height:24px;
		background-image:url('<?php echo $buttons; ?>');
		float:left;
	}

	div.playbutton {
		background-position:24px;
	}

	div.pausebutton {
		background-position:0px;
	}

	div.progressbox {
		margin-top:4px;
		margin-left:10px;
		float:right;
		width:<?= ($width - 52) ?>px; height:15px;
		border:1px solid #444;
		position:relative;
	}

	div.progress {
		width:0px; height:100%; 
		background:#888;
		z-index:10;
		position:absolute;
	}

	div.buffered {
		width:0px; height:100%; 
		background:#bbb;
		position:absolute;
		z-index:5;
	}

	div.buftext {
		font-size:10px;
		padding-left:4px;
		position:absolute;
		z-index:20;
	}
<?php endif; ?>
</style>

<div class="video-box">
	<?php if(isset($title)): ?>
	<div class="videotitle"><?= $title ?></div>
	<?php endif; ?>
	<div id="flash-fallback-<?= $uid ?>" class="flash-fallback">
		<p><b>You are using an version of Adobe's Flash Player plugin, that is not supported by this
		website.</b></p>
		<p>Click the button to download the latest version from Adobe:</p>
		<p><a href="http://www.adobe.com/go/getflashplayer">
			<img src="https://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" border="0" />
		</a></p>
	</div>
	<?php if($html5): ?>
	<video id="myplayer-<?= $uid ?>" width="<?= $width ?>" height="<?= $height ?>" style="display:none">
		<source id="mysource-<?= $uid ?>"src="" type='video/mp4; codecs="avc1.64001E"' />
	</video>

	<div id="mycontrols-<?= $uid ?>" style="display:none;">
		<div id="playbutton-<?= $uid ?>" class="button playbutton"></div>
		<div id="progressbox-<?= $uid ?>" class="progressbox">
			<div id="progress-<?= $uid ?>" class="progress"></div>
			<div id="buffered-<?= $uid ?>" class="buffered"></div>
			<div id="buftext-<?= $uid ?>" class="buftext"></div>
		</div>
	</div>
	<?php endif; ?>
</div>


<script type="text/javascript">
	<?php if($html5): ?>
	if (typeof(myPlayers) == 'undefined') {
		myPlayers = new Object;
	}
	
	myPlayers['<?= $uid ?>'] = document.getElementById('myplayer-<?= $uid ?>');
	
	var t;
	var allowPlaying = false;
	var isChrome = navigator.userAgent.toLowerCase().indexOf('chrome') > -1;

	if(!supportsH264() || !isChrome) {
		if (DetectFlashVer(10,0,0)) {
			embedFlashPlayer();
		} else {
			$('#flash-fallback-<?= $uid ?>').show();
		}
	} else {
		$('#playbutton-<?= $uid ?>').click(playPause);
		$('#myplayer-<?= $uid ?>').click(playPause);

		$('#myplayer-<?= $uid ?>').show();
		$('#mycontrols-<?= $uid ?>').show();

		$('#playbutton-<?= $uid ?>').hide();

		$('#mysource-<?= $uid ?>').attr('src', '<?php echo $vid; ?>');
		startCount();
		$('#buftext-<?= $uid ?>').html('loading...');
		myPlayers['<?= $uid ?>'].load();
	}

	function playPause() {
		if ($('#playbutton-<?= $uid ?>').hasClass('playbutton')) {
			if (allowPlaying) {
				$('#playbutton-<?= $uid ?>').removeClass('playbutton');
				$('#playbutton-<?= $uid ?>').addClass('pausebutton');
				myPlayers['<?= $uid ?>'].play();
			}
		} else {
			$('#playbutton-<?= $uid ?>').removeClass('pausebutton');
			$('#playbutton-<?= $uid ?>').addClass('playbutton');
			myPlayers['<?= $uid ?>'].pause();
		}
	}

	function startCount() {
		t = window.setInterval(function() {

			if (myPlayers['<?= $uid ?>'].duration) {
				$('#progress-<?= $uid ?>').width(myPlayers['<?= $uid ?>'].currentTime / myPlayers['<?= $uid ?>'].duration * <?= ($width - 52) ?>);
				$('#buffered-<?= $uid ?>').width(myPlayers['<?= $uid ?>'].buffered.end(0) / myPlayers['<?= $uid ?>'].duration * <?= ($width - 52) ?>);
			

				if (myPlayers['<?= $uid ?>'].buffered.end(0) > myPlayers['<?= $uid ?>'].duration * 0.9) {
					$('#playbutton-<?= $uid ?>').show();
					allowPlaying = true;
				}
				if (myPlayers['<?= $uid ?>'].buffered.end(0) >= myPlayers['<?= $uid ?>'].duration * 0.99) {
					$('#buftext-<?= $uid ?>').html('');
				} else {
					$('#buftext-<?= $uid ?>').html('&nbsp;loading... ' + Math.round(myPlayers['<?= $uid ?>'].buffered.end(0) / myPlayers['<?= $uid ?>'].duration * 100) + '%');
				}
			}

			if (myPlayers['<?= $uid ?>'].ended) {
				$('#playbutton-<?= $uid ?>').removeClass('pausebutton');
				$('#playbutton-<?= $uid ?>').addClass('playbutton');

				if (typeof(onVideoComplete) == 'function') {
					onVideoComplete('<?= $id ?>');
				}
			}
		}, 100);
	}

	function supportsVideo() {
		return !!document.createElement('video').canPlayType;
	}

	function supportsH264() {
		if (!supportsVideo()) {
			return false;
		}
		var v = document.createElement("video");
		return v.canPlayType('video/mp4; codecs="avc1.64001E"');
	}
	<?php endif; ?>

	function embedFlashPlayer() {
		var flashvars = {};
			flashvars.videoid = "<?= $id; ?>";
			flashvars.video = "<?= $vid; ?>";
			flashvars.width = <?= $width ?>;
			flashvars.height = <?= $height ?>;
			var params = {wmode:"transparent", scale: "noscale", align:"t", salign:"tl"};
			var attributes = {};
			swfobject.embedSWF("<?php echo $swf; ?>", "flash-fallback-<?= $uid ?>", "<?= $width ?>", "<?= ($height + 28 )?>;", "10.0.0", false, flashvars, params, attributes);
	}
	<?php if(!$html5): ?>
	embedFlashPlayer();
	<?php endif; ?>
</script>