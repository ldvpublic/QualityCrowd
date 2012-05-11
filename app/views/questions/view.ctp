<!-- File: /app/views/questions/view.ctp -->

<h2>Question: <?php echo $question['Question']['title']?></h2>

<table>
	<tr>
		<th>Description (above video)</th>
		<td><?php echo $question['Question']['description']?></td>
	</tr>
	<tr>
		<th>Text (below video)</th>
		<td><?php echo $question['Question']['text']?></td>
	</tr>
</table>

<p><?php echo $this->Html->link('back', array('controller' => 'questions', 'action' => 'index')); ?></p>