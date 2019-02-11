<form class="bn" id="register_data">
    <label>
        <?= _("Login") ?>
        <input type="text" name="login" required>
    </label>

    <label>
        <?= _("Email") ?>
        <input type="email" name="email" required>
    </label>

    <label>
        <?= _("Password") ?>
        <input type="password" name="password" required minlength="16">
    </label>

    <label>
        <?= _("Just to be sure: Please repeat your password") ?>
        <input type="password" name="password_repeated" required>
    </label>

    <label>
        <?= _("Second Password (This one is for end-to-end-encryption)") ?>
        <input type="password" name="password2" required minlength="16">
    </label>

    <label>
        <?= _("Second Password: Please repeat that one as well") ?>
        <input type="password" name="password2_repeated" required>
    </label>

    <label>
        <input type="checkbox" value="1" required>
        <?= _("I accept the terms of service.") ?>
    </label>

    <button><?= _("Register now.") ?></button>
</form>