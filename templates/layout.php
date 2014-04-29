<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<link href="<?= $GLOBALS['URI'] ?>assets/stylesheets/bignothing.css" rel="stylesheet" media="screen">
		<link rel="shortcut icon" href="<?= $GLOBALS['URI'] ?>assets/images/favicon.png">
		<script src="<?= $GLOBALS['URI'] ?>assets/javascripts/jquery/jquery-1.8.2.js"></script>
		<script src="<?= $GLOBALS['URI'] ?>assets/javascripts/jquery/jquery-ui-1.9.1.custom.js"></script>
		<script src="<?= $GLOBALS['URI'] ?>assets/javascripts/underscore/underscore-min.js"></script>
		<script src="<?= $GLOBALS['URI'] ?>assets/javascripts/openpgpjs/openpgp.min.js"></script>
		<script src="<?= $GLOBALS['URI'] ?>assets/javascripts/bignothing.js"></script>

		<title>
			BigNothing
		</title>
	</head>
	<body>
		<div id="topbar">huhu</div>
		<div id="topbar_ghost">just to be there</div>
		<div id="contentstream">
			<?= $content ?>
		</div>
		<div style="min-height: 20px;"></div>
	</body>
</html>