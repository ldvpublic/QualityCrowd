<!DOCTYPE html>
<html>
	<head>
		<?php echo $this->Html->charset(); ?>

		<title>QualityCrowd - <?php echo $title_for_layout?></title>

		<?php
			echo $this->Html->meta('icon');
			echo $this->Html->css('cake.generic');
			echo $scripts_for_layout;
		?>
	</head>
	<body id="container">
		<div id="header">
			<h1>QualityCrowd</h1>
			<div id="menu"><?php echo $this->element('mainmenu'); ?></div>
		</div>

		<div id="content">
			<?php echo $this->Session->flash(); ?>
			<?php echo $content_for_layout ?>
		</div>
		
		<div id="footer">&copy; Clemens Horch 2012</div>

		<?php echo $this->element('sql_dump'); ?>
	</body>
</html>