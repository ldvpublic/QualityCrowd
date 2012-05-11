<h2>Add Answer-Set</h2>

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
	for ($i = 0; $i < 10; $i++) {
		echo '<tr>';
		echo '<td>' . ($i + 1) . '</td>';
		echo '<td>';
		echo $this->Form->input("data.$i.value", array('label' => ''));
		echo '</td>';
		echo '<td>';
		echo $this->Form->input("data.$i.text", array('label' => ''));
		echo '</td>';
		echo '<td>';
		echo $this->Form->input("data.$i.gold", array('label' => '', 'type' => 'checkbox'));
		echo '</td>';
		echo '</tr>';
	}
	?>
</table>
<?php
echo $this->Form->end('Add Answer-Set');
?>

<p><?php echo $this->Html->link('back', array('controller' => 'answers', 'action' => 'index')); ?></p>