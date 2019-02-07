<div>
    <h2><?= _("I forgot my first password") ?></h2>
    <?= _("That's no big problem. We have you email-adress. Enter your username and your email below and we send you a new activation-code by email to you and you can reset your password.") ?>
    <form class="bn">
        <label>
            <?= _("Login") ?>
            <input type="text" name="login">
        </label>
        <label>
            <?= _("Email") ?>
            <input type="email" name="email">
        </label>
        <button><?= _("Request activation code") ?></button>
    </form>

    <h2><?= _("I have forgotten my second password") ?></h2>
    <?= _("Well, that's really bad. Even we don't know about your second password. We can reset it for you, yes. But you would lose a lot of your former contents, all non-public discussions for example. But sometimes that's just the way it is. Please take your time and think a few moments before you reset your second password. But if you want to do so, just try to login with login and password. And after you did that, you will get a notification that your second password was incorrect. You then could retry it or you can reset your second password. And yes, you will get asked a lot of times if you really want to reset it.") ?>
</div>