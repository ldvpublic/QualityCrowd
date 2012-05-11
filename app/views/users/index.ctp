<h2>Users</h2>

<p>
	<?php echo $this->Html->link('Add User', array('controller' => 'users', 'action' => 'add')); ?>
</p>

<?php echo $this->Form->create('Video', array('action' => 'sendAllToS3', 'inputDefaults' => array('label' => false, 'div' => false))); ?>

<table>
	<tr>
		<th>Id</th>
		<th>Username</th>
		<th>Actions</th>
	</tr>

	<?php foreach ($users as $user): ?>
	<tr>
		<td>
			<?php echo $user['User']['id']; ?>
		</td>
		<td>
			<?php echo $user['User']['username']; ?>
		</td>
		<td>
			<?php echo $this->Html->link('Delete', array('action' => 'delete', $user['User']['id']), null, 'Are you sure?')?>
			<?php echo $this->Html->link('Change Password', array('action' => 'edit', $user['User']['id']))?>
		</td>
	</tr>
	<?php endforeach; ?>

</table>