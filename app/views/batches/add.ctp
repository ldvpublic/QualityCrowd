<h2>Add Batch</h2>

<?php
echo $this->Form->create('Batch');
echo $this->Form->input('Batch.title');
echo $this->Form->input('Batch.description');
echo $this->Form->input('Batch.keywords');
echo $this->Form->input('Batch.payment');
echo $this->Form->input('Batch.assignments');
echo $this->Form->input('Batch.assignmentduration', array('default' => '3600'));
echo $this->Form->input('Batch.hitlifetime', array('default' => '604800'));
echo $this->Form->input('Batch.question_id');
echo $this->Form->input('Batch.qualification_id');
echo $this->Form->input('Batch.answer_id');
echo $this->Form->input('Video', array(
            'type' => 'select',
            'multiple' => true,
            'options' => $videos,
            'selected' => $html->value('Video.Video'),
				'label' => 'Videos',
        ));
echo $this->Form->end('Add Batch');
?>

<p><?php echo $this->Html->link('back', array('controller' => 'batches', 'action' => 'index')); ?></p>