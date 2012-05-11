<!-- File: /app/views/questions/index.ctp -->

<h2>Questions</h2>

<?php echo $this->Html->link('Add Question', array('controller' => 'questions', 'action' => 'add')); ?>

<table>
	<tr>
		<th>Id</th>
		<th>Title</th>
		<th>Actions</th>
	</tr>

	<!-- Here is where we loop through our $questions array, printing out question info -->

	<?php foreach ($questions as $question): ?>
	<tr>
		<td><?php echo $question['Question']['id']; ?></td>
		<td>
			<?php echo $this->Html->link($question['Question']['title'], array('controller' => 'questions', 'action' => 'view', $question['Question']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link('Edit', array('action' => 'edit', $question['Question']['id']))?>
			<?php echo $this->Html->link('Delete', array('action' => 'delete', $question['Question']['id']), null, 'Are you sure?')?>
		</td>
	</tr>
	<?php endforeach; ?>

</table>