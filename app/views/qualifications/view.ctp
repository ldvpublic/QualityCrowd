<h2>Qualification: <?php echo $qualification['Qualification']['title']; ?></h2>

<table>
	<tr>
		<th>Description</th>
		<td><?php echo $qualification['Qualification']['description']; ?></td>
	</tr>
	<tr>
		<th>Keywords</th>
		<td><?php echo $qualification['Qualification']['keywords']; ?></td>
	</tr>
	<tr>
		<th>Test duration</th>
		<td><?php echo $qualification['Qualification']['testduration']; ?></td>
	</tr>
	<tr>
		<th>Question</th>
		<td><?php echo $qualification['Qualification']['question']; ?></td>
	</tr>
	<tr>
		<th>Answer-Set</th>
		<td><?php echo $this->Html->link($qualification['Answer']['title'], array('controller' => 'answers', 'action' => 'index')); ?></td>
	</tr>
	<tr>
		<th>Videos</th>
		<td>
			<ul>
			<?php
			$videoCounter = 0;
			foreach ($qualification['Video'] as $video) {
				$videoCounter++;
				echo '<li>' . $this->Html->link($video['title'], array('controller' => 'videos', 'action' => 'view', $video['id'])) . '</li>';
			} ?>
			</ul>
		</td>
	</tr>
	<tr>
		<th>MTurk Qualification ID</th>
		<td><?php echo $qualification['Qualification']['mturkid']; ?></td>
	</tr>
</table>

<p><?php echo $this->Html->link('back', array('controller' => 'qualifications', 'action' => 'index')); ?></p>