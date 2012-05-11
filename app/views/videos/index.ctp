<h2>Videos</h2>

<p>
	<?php echo $this->Html->link('Add Video', array('controller' => 'videos', 'action' => 'add')); ?>
	<?php echo $this->Html->link('Add Video Folder', array('controller' => 'videos', 'action' => 'addfolder')); ?>
</p>

<?php echo $this->Form->create('Video', array('action' => 'sendAllToS3', 'inputDefaults' => array('label' => false, 'div' => false))); ?>

<table>
	<tr>
		<th>Id</th>
		<th>Group</th>
		<th>Title</th>
		<th>Qualification hint</th>
		<th>Width</th>
		<th>Height</th>
		<th>Is Ref.</th>
		<th>Ref.</th>
		<?php if (Configure::read('s3.enabled')): ?>
		<th>S3</th>
		<?php endif; ?>
		<th>Actions</th>
	</tr>

	<?php foreach ($videos as $video): ?>
	<tr>
		<td>
			<?php if (Configure::read('s3.enabled')): ?>
			<?php echo $this->Form->input('item' . $video['Video']['id'], array('type' => 'checkbox', 'value' => $video['Video']['id'],'hiddenField' => false)); ?>
			<?php endif; ?>
			<?php echo $video['Video']['id']; ?>
		</td>
		<td><?php echo $video['Video']['group_id']; ?></td>
		<td>
			<?php echo $this->Html->link($video['Video']['title'], array('controller' => 'videos', 'action' => 'view', $video['Video']['id'])); ?>
		</td>
		<td>
			<?php echo ($video['Video']['qualificationhint'] == '' ? "" : "yes");?>
		</td>
		<td>
			<?php echo $video['Video']['width']; ?>
		</td>
		<td>
			<?php echo $video['Video']['height']; ?>
		</td>
		<td>
			<?php echo ($video['Video']['isreference'] ? "yes" : ""); ?>
		</td>
		<td>
			<?php echo $video['Video']['reference_id']; ?>
		</td>
		
		<?php if (Configure::read('s3.enabled')): ?>
		<td>
			<?php 
			if ($video['Video']['s3'] <> '') {
				echo 'yes';
			} else {
				echo $this->Html->link('send', 
					  array('controller' => 'videos', 'action' => 'sendToS3', $video['Video']['id'])); 
			}
			?>
		</td>
		<?php endif; ?>
		<td>
			<?php echo $this->Html->link('Delete', array('action' => 'delete', $video['Video']['id']), null, 'Are you sure?')?>
			<?php echo $this->Html->link('Edit', array('action' => 'edit', $video['Video']['id']))?>
		</td>
	</tr>
	<?php endforeach; ?>

</table>

<?php if (Configure::read('s3.enabled')): ?>
<?php echo $this->Form->end("Send selected items to S3"); ?>
<?php endif; ?>