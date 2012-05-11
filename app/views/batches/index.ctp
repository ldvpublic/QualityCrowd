<h2><?php __('Batches') ?></h2>

<?php echo $this->Html->link('Add Batch', array('controller' => 'batches', 'action' => 'add')); ?>

<table>
	<tr>
		<!--<th>Id</th>-->
		<th><?php __('Title') ?></th>
		<th><?php __('Question') ?></th>
		<th><?php __('Answer-Set') ?></th>
		<th><?php __('Qualification') ?></th>
		<th><?php __('Videos') ?></th>
		<!--<th><?php __('Payment') ?></th>
		<th><?php __('Assignments') ?></th>
		<th><?php __('Total') ?></th>-->
		<th><?php __('Actions') ?></th>
	</tr>

	<?php foreach ($batches as $batch): ?>
	<tr>
		<!--<td><?php echo $batch['Batch']['id']; ?></td>-->
		<td>
			<?php echo $this->Html->link($batch['Batch']['title'], array('controller' => 'batches', 'action' => 'view', $batch['Batch']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($batch['Question']['title'], array('controller' => 'questions', 'action' => 'view', $batch['Question']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($batch['Answer']['title'], array('controller' => 'answers', 'action' => 'index')); ?>
		</td>
		<td>
			<?php echo $this->Html->link($batch['Qualification']['title'], array('controller' => 'qualifications', 'action' => 'view', $batch['Qualification']['id'])); ?>
		</td>
		<td>
			<ul>
			<?php 
				$videoCounter = 0;
			foreach ($batch['Video'] as $video): $videoCounter++; ?>
				<li><?php echo $this->Html->link($video['title'], array('controller' => 'videos', 'action' => 'view', $video['id'])); ?></li>
			<?php endforeach; ?>
			</ul>
		</td>
		<!--<td><?php echo $this->Number->currency($batch['Batch']['payment'], 'USD'); ?></td>
		<td><?php echo $batch['Batch']['assignments']; ?></td>
		<td><?php echo $this->Number->currency($batch['Batch']['assignments'] * $videoCounter * $batch['Batch']['payment'], 'USD'); ?></td>-->
		<td>
			<?php 
			
			if ($batch['Batch']['mturk_groupid'] == '' && $batch['Batch']['cf_jobid'] == '') {
				echo $this->Html->link('Edit', array('action' => 'edit', $batch['Batch']['id'])) . '</br>';	
			}
			
			echo $this->Html->link('Delete', array('action' => 'delete', $batch['Batch']['id']), null, 'Are you sure?');

			if (Configure::read('mturk.enabled')) {
				if ($batch['Batch']['mturk_groupid'] == '') {
					echo '<br/>' . $this->Html->link('MT-Publish', array('action' => 'MTpublish', $batch['Batch']['id']), null, 'Are you sure?');
				} else {
					echo '<br/>' . $this->Html->link('MT-Unpublish', array('action' => 'MTunpublish', $batch['Batch']['id']), null, 'Are you sure? All result data will be lost!');
					echo '<br/>' . $this->Html->link('MT-Results', array('action' => 'MTresults', $batch['Batch']['id']));
				}
			}
			
			if (Configure::read('cf.enabled')) {
				if ($batch['Batch']['cf_jobid'] == '') {
					echo '<br/>' . $this->Html->link('CF-Publish', array('action' => 'CFpublish', $batch['Batch']['id']), null, 'Are you sure?');
				} else {
					echo '<br/>' . $this->Html->link('CF-Unpublish', array('action' => 'CFunpublish', $batch['Batch']['id']), null, 'Are you sure? All result data will be lost!');
				}
			}

			echo '<br/>' . $this->Html->link('Preview', array('action' => 'external', $batch['Batch']['id']));
			?>
		</td>
	</tr>
	<?php endforeach; ?>

</table>
