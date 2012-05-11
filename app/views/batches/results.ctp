<h2>Batch Results: <?php echo $batch['Batch']['title']; ?></h2>

<p><?php echo $this->Html->link('download as CSV-file', 'results/' . $batch['Batch']['id'] . '.csv'); ?></p>

<h3>Mean Opinion Score</h3>
<table>
	<tr>
		<th>Video</th>
		<th>N</th>	
		<th>MOS</th>
		<th>avg. Duration (sec)</th>
	</tr>
	
<?php
foreach($mos as $row) {
	echo '<tr>';
	echo '<td>' . $row['name'] . '</td>';
	echo '<td>' . $row['count'] . '</td>';
	echo '<td>' . round($row['value'], 2) . '</td>';
	echo '<td>' . round($row['timer']) . '</td>';
	echo '</tr>';
}
?>
</table>

<p><?php echo $this->Html->link('back', array('controller' => 'batches', 'action' => 'index')); ?></p>