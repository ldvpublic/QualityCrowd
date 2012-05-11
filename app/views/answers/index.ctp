<h2>Answer-Sets</h2>

<?php echo $this->Html->link('Add Answer-Set', array('controller' => 'answers', 'action' => 'add')); ?>

<table>
	<tr>
		<th>Id</th>
		<th>Title</th>
		<th>Continous</th>
		<th>Answers</th>
		<th>Actions</th>
	</tr>

	<?php foreach ($answers as $answer): ?>
	<tr>
		<td><?php echo $answer['Answer']['id']; ?></td>
		<td>
			<?php echo $answer['Answer']['title']; ?>
		</td>
		<td>
			<?php echo ($answer['Answer']['continous'] ? 'Yes' : 'No'); ?>
		</td>
		<td>
			<?php
			foreach($answer['Answer']['answers'] as $row) {
				echo $row['value'] . ' - ' . $row['text'];
				echo ($row['gold'] ? ' <i>(Gold)</i>' : '');
				echo '<br/>';
			}
			?>
		</td>
		<td>
			<?php echo $this->Html->link('Delete', array('action' => 'delete', $answer['Answer']['id']), null, 'Are you sure?')?>
			<?php echo $this->Html->link('Edit', array('action' => 'edit', $answer['Answer']['id']))?>
		</td>
	</tr>
	<?php endforeach; ?>

</table>