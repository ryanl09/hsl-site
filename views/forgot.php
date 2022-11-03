<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/documentelements.php');
require_once($path . '/classes/services/ForgotPasswordService.php');



start_content_full(0, 'forgot');



$code='';
$page = $_SESSION['current_page'];

$errors = '<div class="row errors" style="display:none;"></div>';

if (count($page) > 2 && $page[2] != '') {
    $code = $page[2];

    $fps = new ForgotPasswordService($db);
    $verify = $fps->verify($code);

    if (!$verify){
        $code='';
        $errors='<div class="row errors" style="display:none;">Token is either invalid or expired.</div>';
    }
}

?>

<div class="login-box">
    <?php if ($code) { 
        
        $_SESSION['forgot_pass_token'] = $code;
        ?>
        <div class="login-header">
            <h1>Reset Password</h1>
            <img src="https://tecesports.com/images/tec-black.png" alt="TEC">
        </div>
        <div class="login-body">
            <div class="row errors" style="display:none;"></div>
            <div class="row">
                <div class="input">
                    <i class='bx bxs-envelope'></i>
                    <input type="text" name="password" id="password" placeholder="New password">
                </div>
            </div>
            <div class="row">
                <div class="input">
                    <i class='bx bxs-envelope'></i>
                    <input type="text" name="cpassword" id="cpassword" placeholder="Repeat password">
                </div>
            </div>
            <div class="row">
                <div class="input">
                    <input type="submit" value="Submit" id='submit-btn-2'>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <div class="login-header">
            <h1>Forgot Password</h1>
            <img src="https://tecesports.com/images/tec-black.png" alt="TEC">
        </div>
        <div class="login-body">
            <?php echo $errors; ?>
            <div class="row">
                <div class="input">
                    <i class='bx bxs-envelope'></i>
                    <input type="text" name="username" id="username" placeholder="Username or email">
                </div>
            </div>
            <div class="row">
                <div class="input">
                    <input type="submit" value="Submit" id='submit-btn'>
                </div>
            </div>
        </div>
        <div class="login-footer">
            <a href="<?php echo href('login'); ?>">Click here to login</a>
        </div>
    <?php } ?>
</div>

<?php end_content_full(0); ?>