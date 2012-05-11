<!DOCTYPE html>
<html>
	<head>
		<?php
			echo $this->Html->charset();
			echo $scripts_for_layout;
		?>

		<style type="text/css">
			html, body {
				font-family: Helvetica, Arial, sans-serif;
				font-size:12px;
			}
		</style>
	</head>
	<body id="container">
		<div id="content">
			<?php echo $this->Session->flash(); ?>
			<?php echo $content_for_layout ?>
		</div>
	</body>
</html>
