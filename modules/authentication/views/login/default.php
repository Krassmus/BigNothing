<form action="<?= URL::create("authentication/login/authenticate") ?>"
      method="POST"
      class="small"
      id="loginform">
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
</form>
<div class="center">
    <a href="<?= URL::link("authentication/register") ?>"><?= _("Register") ?></a>
    /
    <a href="<?= URL::link("authentication/login/troubleshooting") ?>"><?= _("Troubleshooting") ?></a>
</div>
