<h2>Add Video Folder</h2>

<?php
echo $this->Form->create('Video');
echo $this->Form->input('path', array('default' => APP . '../upload'));
echo $this->Form->input('group_id', array('label' => 'Group', 'type' => 'text'));
echo $this->Form->input('width', array('default' => '352'));
echo $this->Form->input('height', array('default' => '288'));
echo $this->Form->end('Import Folder');
?>

<p><?php echo $this->Html->link('back', array('controller' => 'videos', 'action' => 'index')); ?></p>