<form action="<?= URL::create("authentication/login/authenticate") ?>" method="POST" id="loginform">
    <h1><?= _("#Welcome on board ...") ?></h1>
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
            jQuery("#contentstream").delay(300).fadeIn(300, function () { jQuery(this).find("#login").focus(); });
            jQuery("#loginform").on("submit", function () {
                console.log(jQuery("#passphrase").val());
                jQuery("#passphrase").val("");
            });
        });
    </script>
</form>
<div class="center">
    <a href="<?= URL::link("authentication/login/register") ?>"><?= _("Register") ?></a> / <a href=""><?= _("Troubleshooting") ?></a>
</div>

<style>
    #contentstream {
        width: 400px;
        height: 400px;
        border-radius: 20px;
        background-image: linear-gradient(to bottom, #f5f5f5 0%,#eeeeee 35%,#cccccc 100%);
        padding: 20px;
        margin-left: auto;
        margin-right: auto;
        margin-top: 70px;
        display: none;

    }
    #contentstream input, #contentstream table {
        width: 100%;
    }
</style>