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
        'scripts' => ['register']
        ), true
    ); 
    ?>
    <body>
        <section class="home">
            <div class="page-content">
                <input type="hidden" id="csrf" value="<?php echo $_SESSION['csrf']; ?>">
                <div class="login-box">
                    <div class="login-header">
                        <h1>Register</h1>
                        <img src="https://tecconvention.com/images/tec-transparent.png" alt="TEC">
                    </div>
                    <div class="login-body">
                        <div class="row errors" style="display:none;"></div>
                        <div class="row e2">
                            <div class="input">
                                <i class='bx bxs-user-detail' ></i>
                                <input type="text" name="firstname" id="firstname" placeholder="First name">
                            </div>
                            <div class="input">
                                <input type="text" name="lastname" id="lastname" placeholder="Last name">
                            </div>
                        </div>
                        <div class="row">
                            <div class="input">
                                <i class='bx bx-user-pin' ></i>
                                <input type="text" name="pronouns" id="pronouns" placeholder="Pronouns">
                            </div>
                        </div>
                        <div class="row">
                            <div class="input">
                                <i class='bx bxs-envelope' ></i>
                                <input type="email" name="email" id="email" placeholder="Email">
                            </div>
                        </div>
                        <div class="row">
                            <div class="input">
                                <i class='bx bxs-user' ></i>
                                <input type="text" name="username" id="username" placeholder="Username">
                            </div>
                        </div>
                        <div class="row e2">
                            <div class="input">
                                <i class='bx bxs-key'></i>
                                <input type="password" name="password" id="password" placeholder="Password">
                            </div>
                            <div class="input">
                                <input type="password" name="c_password" id="c_password" placeholder="Confirm password">
                            </div>
                        </div>
                        <div class="check">
                            <input type="checkbox" name="showpass" id="showpass">
                            <label for="showpass" id="showpass-label">Show password</label>
                        </div>
                        <div class="check">
                            <input type="checkbox" name="terms" id="terms">
                            <label for="terms">I have read and accept both the <a href="<?php echo href('terms'); ?>">Terms & Conditions</a> and <a href="<?php echo href('privacy'); ?>">Privacy Policy</a></label>
                        </div>
                        <div class="row">
                            <div class="input">
                                <input type="submit" value="Register" id='register-btn'>
                            </div>
                        </div>
                    </div>
                    <div class="login-footer">
                        <a href="<?php echo href('login'); ?>">Click here to login</a>
                    </div>
                </div>
            </div>
        </section>
    </body>
</html>