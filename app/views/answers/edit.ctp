<h2>Edit Answer-Set</h2>

<?php
echo $this->Form->create('Answer');
echo $this->Form->input('Answer.title', array('label' => 'Title (internal)'));
echo $this->Form->input('Answer.continous', array('label' => 'Continous scale'));
?>

<table>
	<tr>
		<th></th>
		<th>Value</th>
		<th>Answer</th>
		<th>Gold</th>
	</tr>
	<?php
	
	for ($i = 0; $i < count($answers) + 3; $i++) {
		$thisAnswer = current($answers);
		
		echo '<tr>';
		echo '<td>' . ($i + 1) . '</td>';
		echo '<td>';
		echo $this->Form->input("data.$i.value", array('label' => '', 'value' => $thisAnswer['value']));
		echo '</td>';
		echo '<td>';
		echo $this->Form->input("data.$i.text", array('label' => '', 'value' => $thisAnswer['text']));
		echo '</td>';
		echo '<td>';
		echo $this->Form->input("data.$i.gold", array('label' => '', 'type' => 'checkbox', 'checked' => $thisAnswer['gold']));
		echo '</td>';
		echo '</tr>';
		
		next($answers);
	}
	?>
</table>
<?php
echo $this->Form->end('Save');
?>

<p><?php echo $this->Html->link('back', array('controller' => 'answers', 'action' => 'index')); ?></p>