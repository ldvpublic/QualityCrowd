<h2>Edit Video</h2>

<?php
echo $this->Form->create('Video', array('type' => 'file'));
echo $this->Form->input('title');
echo $this->Form->input('qualificationhint', array('label' => 'Qualification hint'));
echo $this->Form->input('width');
echo $this->Form->input('height');
echo $this->Form->input('isreference', array('label' => 'is reference with full quality', 'type' => 'checkbox'));
echo $this->Form->input('reference_id', array(
            'type' => 'select',
				'empty' => true,
            'options' => $referenceVideos,
            'selected' => $html->value('Video.reference_id'),
				'label' => 'Reference',
        ));


echo $this->Form->end('Save changes');
?>

<p><?php echo $this->Html->link('back', array('controller' => 'videos', 'action' => 'index')); ?></p>