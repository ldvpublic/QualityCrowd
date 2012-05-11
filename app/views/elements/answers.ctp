<?php

$sid = uniqid();
$javascript->link('https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js', false);

?>

<input type="hidden" name="<?= $name ?>" value="">
<input type="hidden" name="<?= $name ?>_text" value="">
<input type="hidden" name="<?= $name ?>_answered" value="0">
<input type="hidden" name="<?= $name ?>_watched" value="0">

<?php

if ($continous) {
	$maxVal = 0;
	$fullHeight = 180;
	
	foreach($answers as $row) {
		if ($row['value'] > $maxVal) $maxVal = $row['value'];
	}

	$labels = '';
	$delta = $fullHeight / $maxVal;
	foreach($answers as $row) {
		if ($row['text'] <> '') {
			$pos = $fullHeight / $maxVal * ($maxVal - $row['value']) + $delta /2 - 5;
			$labels .= '<div class="slider-label" style="top:' . $pos . 'px">' . $row['text'] . '</div>';
		}
		
		if ($row['value'] <> $maxVal) {
			$pos = $fullHeight / $maxVal * ($maxVal - $row['value']);
			$labels .= '<div class="slider-scale" style="top:' . $pos . 'px"></div>';
		}
	}
	
?>

<style type="text/css">
div.slider-area {
	position:relative;
	height:<?= $fullHeight ?>px;
	margin:10px;
	padding:0;
}

div.slider-box {
	width:15px;
	height:<?= $fullHeight ?>px;
	position:absolute;
	top:0px;
	background-color:#ddd;
	left:12px;
}

div.slider-handle {
	width:20px;
	height:10px;
	background-color: red;
	border:1px solid #660000;
	position:absolute;
	cursor:row-resize;
	top:<?= $fullHeight / 2 - 5?>px;
	left:8px;
	border-radius:7px;
}

div.slider-label {
	position:absolute;
	left:40px;
	font-size:12px;
}

div.slider-scale {
	position:absolute;
	border-bottom:1px solid #888;
	width:32px;
	height:0px;
	left:3px;
}

div.slider-scale-end {
	position:absolute;
	border-bottom:2px solid #333;
	width:40px;
	height:0px;
}

</style>

<div id="slider-area-<?= $sid ?>" class="slider-area">
	<div id="slider-box-<?= $sid ?>" class="slider-box"></div>
	<div id="slider-handle-<?= $sid ?>" class="slider-handle"></div>
	<div class="slider-scale-end" style="top:0px;"></div>
	<?= $labels ?>
	<div class="slider-scale-end" style="top:<?= ($fullHeight) ?>px;"></div>
</div>

<script type="text/javascript">
	
	slider<?= $sid ?> = new Object;
	
	slider<?= $sid ?>.overlap = $('#slider-handle-<?= $sid ?>').height() / 2;
	slider<?= $sid ?>.maxPos = <?= $fullHeight ?>;
	slider<?= $sid ?>.maxVal = <?= $maxVal ?>;

	slider<?= $sid ?>.moving = false;
	slider<?= $sid ?>.boxTop = 0;

	refreshValue(0);

	$('#slider-handle-<?= $sid ?>').mousedown(function (e) {
		slider<?= $sid ?>.moving = true;
		slider<?= $sid ?>.boxTop = $('#slider-box-<?= $sid ?>').offset().top;
		$('#slider-box-<?= $sid ?>').css('cursor', 'row-resize');
		return false;
	})

	$(document).mouseup(function () {
		slider<?= $sid ?>.moving = false;
		$('#slider-box-<?= $sid ?>').css('cursor', 'default');
	})

	$(document).mousemove(function (e) {
		if (slider<?= $sid ?>.moving) {
			var newPos =  e.pageY - slider<?= $sid ?>.boxTop - slider<?= $sid ?>.overlap;
			if (newPos < -slider<?= $sid ?>.overlap) newPos = - slider<?= $sid ?>.overlap;
			if (newPos > slider<?= $sid ?>.maxPos - slider<?= $sid ?>.overlap) newPos = slider<?= $sid ?>.maxPos - slider<?= $sid ?>.overlap;
			$('#slider-handle-<?= $sid ?>').css('top', newPos);
			
			refreshValue(1);
		}
	});

	function refreshValue(answered) {
		var newPos = $('#slider-handle-<?= $sid ?>').position().top + slider<?= $sid ?>.overlap;
		var newVal = Math.round(1000 - (newPos / slider<?= $sid ?>.maxPos * 1000));

		$('input[name=<?= $name ?>]').val(newVal);
		$('input[name=<?= $name ?>_text]').val(getTextFromValue(newVal));
		$('input[name=<?= $name ?>_answered]').val(answered);
		$('input[name=<?= $name ?>]').change();
	}
	
	function getTextFromValue(val) {
		if (1 == 0) {
			// nothing
		} 
		<?php 
		$answers = array_reverse($answers);
		foreach($answers as $row):
			$val = round($row['value'] / $maxVal * 1000);
		?>
		else if (val <= <?= $val ?>) { return "<?= $row['text']; ?>"; }	
		<?php endforeach; ?>
	}
</script>


<?php
} else {

	foreach($answers as $row) {
		echo '<input type="radio" name="answerOptions-' . $name . '" id="answerOptions-' . $name . '-' . $row['value'] . '" value="' . $row['value']. '">';
		echo '<label for="answerOptions-' . $name . '-' . $row['value'] . '" id="label-' . $name . '-' . $row['value'] . '">'. $row['text'] . '</label><br />';
	}
	
	?>

<script type="text/javascript">

$('input[name=answerOptions-<?= $name ?>]').change( function() {
	var selectedValue = $('input[name=answerOptions-<?= $name ?>]:checked').val();
	$('input[name=<?= $name ?>]').val(selectedValue);
	$('input[name=<?= $name ?>_text]').val($('#label-<?= $name ?>-' + selectedValue).text());
	$('input[name=<?= $name ?>_answered]').val(1);
	$('input[name=<?= $name ?>]').change();
});
	

</script>

<?php	
}
?>
