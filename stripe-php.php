<?php
/**
 * Plugin Name: TEC Stripe Integration
 * Description: My Plugin description.
 * Author: Your name
 * Version: 1.0
**/

require_once 'init.php';

use Stripe\Stripe;

add_action('init', function() {

    Stripe::setApiKey('sk_test_51HNIKABkqkeaFYuhW86wpRcK8EGBt9bzgUE0QYePBpc3exCRvu3FComCbKz8IrrFOTyPE9fazD4fHPqXI1f5iuft00TKEsKzDK');
    //Stripe::setClientId('my-client-id');

});

?>