<h2>Video: <?php echo $video['Video']['title']?></h2>


<?php echo $this->element('videoplayer', array(
	'id' => $video['Video']['id'],
	'filename' => $video['Video']['filename'],
	's3' => $video['Video']['s3'],
	'width' => $video['Video']['width'],
	'height' => $video['Video']['height'],
)); ?>

<table>
	<tr>
		<th>Group</th>
		<td>
			<?php echo $video['Video']['group_id']?>
		</td>
	</tr>
	<tr>
		<th>Width</th>
		<td>
			<?php echo $video['Video']['width']?> px
		</td>
	</tr>
	<tr>
		<th>Height</th>
		<td>
			<?php echo $video['Video']['height']?> px
		</td>
	</tr>
	<tr>
		<th>Qualification Hint</th>
		<td>
			<?php echo $video['Video']['qualificationhint']?>
		</td>
	</tr>
	<?php if (Configure::read('s3.enabled')): ?>
	<tr>
		<th>Amazon S3</th>
		<td>
			<?php 
			if ($video['Video']['s3'] <> '') {
				echo 'This video is available via Amazon S3';
			} else {
				echo $this->Html->link('send to Amazon S3', 
					  array('controller' => 'videos', 'action' => 'sendToS3', $video['Video']['id'])); 
			}
			?>
		</td>
	</tr>
	<?php endif; ?>
</table>

<p><?php echo $this->Html->link('back', array('controller' => 'videos', 'action' => 'index')); ?></p>