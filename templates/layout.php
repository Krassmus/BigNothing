<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<link href="<?= URL::link("assets/stylesheets/bignothing.css") ?>" rel="stylesheet" media="screen">
		<link rel="shortcut icon" href="<?= URL::link("assets/images/favicon.png") ?>">
		<script src="<?= URL::link("assets/javascripts/jquery/jquery-1.8.2.js") ?>"></script>
		<script src="<?= URL::link("assets/javascripts/jquery/jquery-ui-1.9.1.custom.js") ?>"></script>
		<script src="<?= URL::link("assets/javascripts/underscore/underscore-min.js")  ?>"></script>
		<script src="<?= URL::link("assets/javascripts/openpgpjs/openpgp.min.js") ?>"></script>
		<script src="<?= URL::link("assets/javascripts/bignothing.js") ?>"></script>

		<title>
			BigNothing
		</title>
	</head>
	<body>
		<div id="topbar">Big Nothing</div>
		<div id="topbar_ghost">just to be there</div>
		<div id="contentstream">
			<?= $content ?>
		</div>
		<div style="min-height: 20px;"></div>
	</body>
</html>