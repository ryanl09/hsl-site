<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/documentelements.php');

if (isset($_SESSION['user']) && $_SESSION['user']->get_id()) {
    header('Location: ' . href('dashboard'));
}

?>

<html>
    <?php 
    base_header(array(
        'styles' => ['login'],
        'scripts' => ['login']
        ), true
    ); 
    ?>
    <body>
        <section class="home">
            <div class="page-content">
                <input type="hidden" id="csrf" value="<?php echo $_SESSION['csrf']; ?>">
                <div class="login-box">
                    <div class="login-header">
                        <h1>Login</h1>
                        <img src="https://tecconvention.com/images/tec-transparent.png" alt="TEC">
                    </div>
                    <div class="login-body">
                        <div class="row errors" style="display:none;"></div>
                        <div class="row">
                            <div class="input">
                                <i class='bx bxs-user' ></i>
                                <input type="text" name="username" id="username" placeholder="Username or email">
                            </div>
                        </div>
                        <div class="row">
                            <div class="input">
                                <i class='bx bxs-key' ></i>
                                <input type="password" name="password" id="password" placeholder="Password">
                            </div>
                        </div>
                        <div class="check">
                            <input type="checkbox" name="showpass" id="showpass">
                            <label for="showpass" id="showpass-label">Show password</label>
                        </div>
                        <div class="check">
                            <input type="checkbox" name="rememberme" id="rememberme">
                            <label for="rememberme">Remember me</label>
                        </div>
                        <div class="row">
                            <div class="input">
                                <input type="submit" value="Login" id='login-btn'>
                            </div>
                        </div>
                    </div>
                    <div class="login-footer">
                        <a href="<?php echo href('register'); ?>">Click here to register</a>
                    </div>
                </div>
            </div>
        </section>
    </body>
</html>