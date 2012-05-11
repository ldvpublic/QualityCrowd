<h2><?php __('Qualifications') ?></h2>

<?php echo $this->Html->link('Add Qualification', array('controller' => 'qualifications', 'action' => 'add')); ?>

<table>
	<tr>
		<th>Id</th>
		<th><?php __('Title') ?></th>
		<th><?php __('Answer-Set') ?></th>
		<th><?php __('Videos') ?></th>
		<th><?php __('Actions') ?></th>
	</tr>

	<?php foreach ($qualifications as $qualification): ?>
	<tr>
		<td><?php echo $qualification['Qualification']['id']; ?></td>
		<td>
			<?php echo $this->Html->link($qualification['Qualification']['title'], array('controller' => 'qualifications', 'action' => 'view', $qualification['Qualification']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($qualification['Answer']['title'], array('controller' => 'answers', 'action' => 'index')); ?>
		</td>
		<td>
			<ul>
			<?php
				$videoCounter = 0;
			foreach ($qualification['Video'] as $video): $videoCounter++; ?>
				<li><?php echo $this->Html->link($video['title'], array('controller' => 'videos', 'action' => 'view', $video['id'])); ?></li>
			<?php endforeach; ?>
			</ul>
		</td>
		<td>
			<?php echo $this->Html->link('Edit', array('action' => 'edit', $qualification['Qualification']['id']))?>
			<?php echo $this->Html->link('Delete', array('action' => 'delete', $qualification['Qualification']['id']), null, 'Are you sure?')?>
			
			<?php
			if ($qualification['Qualification']['mturkid'] == '') {
				echo $this->Html->link('Publish', array('action' => 'publish', $qualification['Qualification']['id']), null, 'Are you sure?');
			}
			?>

			<?php echo $this->Html->link('Preview', array('action' => 'externalpage', $qualification['Qualification']['id']))?>
		</td>
	</tr>
	<?php endforeach; ?>

</table>
