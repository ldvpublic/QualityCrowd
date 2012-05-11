<h2>Batch: <?php echo $batch['Batch']['title']; ?></h2>

<table>
	<tr>
		<th>Description</th>
		<td><?php echo $batch['Batch']['description']; ?></td>
	</tr>
	<tr>
		<th>Keywords</th>
		<td><?php echo $batch['Batch']['keywords']; ?></td>
	</tr>
	<tr>
		<th>Payment</th>
		<td><?php echo $this->Number->currency($batch['Batch']['payment'], 'USD'); ?></td>
	</tr>
	<tr>
		<th>Assignments</th>
		<td><?php echo $batch['Batch']['assignments']; ?></td>
	</tr>
	<tr>
		<th>Assignment duration</th>
		<td><?php echo $batch['Batch']['assignmentduration']; ?></td>
	</tr>
	<tr>
		<th>HIT lifetime</th>
		<td><?php echo $batch['Batch']['hitlifetime']; ?></td>
	</tr>
	<tr>
		<th>Question</th>
		<td><?php echo $this->Html->link($batch['Question']['title'], array('controller' => 'questions', 'action' => 'view', $batch['Question']['id'])); ?></td>
	</tr>
	<tr>
		<th>Answer-Set</th>
		<td><?php echo $this->Html->link($batch['Answer']['title'], array('controller' => 'answers', 'action' => 'index')); ?></td>
	</tr>
	<tr>
		<th>Videos</th>
		<td>
			<ul>
			<?php
			$videoCounter = 0;
			foreach ($batch['Video'] as $video) {
				$videoCounter++;
				echo '<li>' . $this->Html->link($video['title'], array('controller' => 'videos', 'action' => 'view', $video['id'])) . '</li>';
			} ?>
			</ul>
		</td>
	</tr>
	<?php if (Configure::read('mturk.enabled')): ?>
	<tr>
		<th>MTurk HIT-Group ID</th>
		<td><?php echo $batch['Batch']['mturk_groupid']; ?></td>
	</tr>
	<?php endif; ?>
</table>

<p><?php echo $this->Html->link('back', array('controller' => 'batches', 'action' => 'index')); ?></p>