<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<link href="./assets/stylesheets/bignothing.css" rel="stylesheet" media="screen">
		<link rel="shortcut icon" href="./assets/images/favicon.png">
		<script src="./assets/javascripts/jquery/jquery-1.8.2.js"></script>
		<script src="./assets/javascripts/jquery/jquery-ui-1.9.1.custom.js"></script>
		<script src="./assets/javascripts/underscore/underscore-min.js"></script>
		<script src="./assets/javascripts/openpgpjs/openpgp.min.js"></script>
		<script src="./assets/javascripts/bignothing.js"></script>

		<title>
			BigNothing
		</title>
	</head>
	<body>
		<div id="loginwindow">
            <form action="?" method="POST" id="loginform">
                <h1><?= _("Enter the matrix ...") ?></h1>
                <table>
                    <tbody>
                        <tr>
                            <td width="50%"><label for="login"><?= _("Login") ?></label></td>
                            <td width="50%"><input type="text" name="login" id="login" autofocus="autofocus"></td>
                        </tr>
                        <tr>
                            <td><label for="password"><?= _("First password") ?></label></td>
                            <td><input type="password" name="password" id="password"></td>
                        </tr>
                        <tr>
                            <td><label for="passphrase"><?= _("Second password") ?></label></td>
                            <td><input type="password" name="passphrase" id="passphrase"></td>
                        </tr>
                    </tbody>
                </table>
                <div class="center"><button type="submit"><?= _("enter") ?></button></div>
                <script>
                    jQuery(function () {
                        jQuery("#loginform").on("submit", function () {
                            console.log(jQuery("#passphrase").val());
                            jQuery("#passphrase").val("");
                        });
                    });
                </script>
            </form>
            <div class="center">
                <a><?= _("Register") ?></a> / <a><?= _("Troubleshooting") ?></a>
            </div>
		</div>
	</body>
</html>