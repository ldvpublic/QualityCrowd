<h2>Edit Question</h2>

<?php
echo $this->Form->create('Question');
echo $this->Form->input('title', array('label' => 'Title (internal)'));
echo $this->Form->input('description', array('label' => 'Text (above video)'));
echo $this->Form->input('text', array('label' => 'Text (below video)'));
echo $this->Form->end('Save changes');
?>

<p><?php echo $this->Html->link('back', array('controller' => 'questions', 'action' => 'index')); ?></p>