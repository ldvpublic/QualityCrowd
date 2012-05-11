<h2>Add Video</h2>

<?php
echo $this->Form->create('Video', array('type' => 'file'));
echo $this->Form->input('title');
echo $this->Form->input('group_id', array('label' => 'Group'));
echo $this->Form->input('qualificationhint', array('label' => 'Qualification hint'));
echo $this->Form->input('width', array('default' => '352'));
echo $this->Form->input('height', array('default' => '288'));
echo $this->Form->input('isreference', array('label' => 'is reference with full quality', 'type' => 'checkbox', 'default' => false));
echo $this->Form->input('reference_id', array(
            'type' => 'select',
				'empty' => true,
            'options' => $referenceVideos,

				'label' => 'Reference',
        ));
echo $this->Form->file('videofile');

echo $this->Form->end('Add Video');
?>

<p><?php echo $this->Html->link('back', array('controller' => 'videos', 'action' => 'index')); ?></p>