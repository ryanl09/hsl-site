<?php
    $path = $_SERVER['DOCUMENT_ROOT'];
    require_once($path . '/documentelements.php');
    require_once($path . '/classes/services/ConfirmEmailService.php');

    start_content_full(0, 'activate');



    $page = $_SESSION['current_page'];

    $errors = '<div class="row errors" style="display:none;"></div>';
?>

<div class="activate-account">
    <?php 
        if ($p2) {
            $ces = new ConfirmEmailService($db);
            $verify = $ces->verify_email($p2);
            if ($verify) { ?>
        <div class="activate-header">
            <h1>Your account has been activated!</h1>
            <p><a href="https://tecesports.com/login">Click here to log in</a></p>
        </div>
        <?php } else { ?>
        <div class="activate-header">
            <h1>Your account is either already activated or does not exist :(</h1>
            <p><a href="https://tecesports.com/register">Click here to create an account</a></p>
        </div>
    <?php }
        } ?>
</div>

<?php end_content_full(0); ?>