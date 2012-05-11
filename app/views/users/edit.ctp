<h2>Edit User</h2>

<?php
echo $this->Form->create('User');
echo $this->Form->input('pwd1', array('type' => 'password', 'label' => "Password"));
echo $this->Form->input('pwd2', array('type' => 'password', 'label' => "Confirm Password")); 

echo $this->Form->end('Change Password');
?>

<p><?php echo $this->Html->link('back', array('controller' => 'users', 'action' => 'index')); ?></p>