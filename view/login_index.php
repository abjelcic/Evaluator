<?php require_once __DIR__ . '/login_header.php'; ?>

    <p><?php echo $message?></p>

    <form method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
        <h2>Login</h2>

        <dl>
            <dt>Username:</dt>
            <dd><input type="text" name="username" /></dd>

            <dt>Password:</dt>
            <dd><input type="password" name="password" /></dd>
        </dl>

        <button type="submit" name="button" value="login">Log in!</button>
        
    </form>



    <form method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
        <h2>Register</h2>


        <dl>
            <dt>Username:</dt>
            <dd><input type="text" name="username" /></dd>

            <dt>Password:</dt>
            <dd><input type="password" name="password" /></dd>

            <dt>E-mail:</dt>
            <dd><input type="text" name="email" /></dd>
        </dl>

        <button type="submit" name="button" value="register">Register!</button>
        
    </form>


<?php require_once __DIR__ . '/login_footer.php'; ?>
