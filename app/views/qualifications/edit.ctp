<h2>Edit Qualification</h2>

<?php
echo $this->Form->create('Qualification');
echo $this->Form->input('Qualification.title');
echo $this->Form->input('Qualification.keywords');
echo $this->Form->input('Qualification.description');
echo $this->Form->input('Qualification.question');
echo $this->Form->input('Qualification.testduration', array('default' => '3600'));
echo $this->Form->input('Qualification.answer_id');
echo $this->Form->input('Video', array(
            'type' => 'select',
            'multiple' => true,
            'options' => $videos,
            'selected' => $html->value('Video.Video'),
				'label' => 'Videos',
        ));
echo $this->Form->end('Save changes');
?>

<p><?php echo $this->Html->link('back', array('controller' => 'qualifications', 'action' => 'index')); ?></p>