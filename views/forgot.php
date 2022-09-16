<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/documentelements.php');

$page = $_SESSION['current_page'];
if (count($page) > 2 && $page[2] != '') {
    $code = $page[2];
}

start_content_full(0, 'forgot');?>

<div class="login-box">
    <div class="login-header">
        <h1>Forgot Password</h1>
        <img src="https://tecesports.com/images/tec-black.png" alt="TEC">
    </div>
    <div class="login-body">
        <div class="row errors" style="display:none;"></div>
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
</div>

<?php end_content_full(0); ?>