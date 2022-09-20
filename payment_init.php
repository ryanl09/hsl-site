<?php 

if (!session_id()) {
    session_start();
}
  
require_once('config.php'); 
require_once('tecdb.php'); 
require_once('stripe-php/init.php');
require_once('Ticket.php'); 
require_once('Cart.php');
require_once('phpqrcode/qrlib.php');

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path.'/wp-load.php');
 
// Set API key 
\Stripe\Stripe::setApiKey(STRIPE_API_KEY); 
 
// Retrieve JSON from POST body 
$jsonStr = file_get_contents('php://input'); 
$jsonObj = json_decode($jsonStr); 
 
if($jsonObj->request_type == 'create_payment_intent'){ 

     
    $code = !empty($jsonObj->fund_code)?$jsonObj->fund_code:'';
    
    $user_id = wp_get_current_user()->ID;
    $cart = new Cart($user_id);

    $items = $cart->get_items();

    $tickets = Ticket::get_all();

    $total = 0;

    $i_desc = '';


    foreach ($items as $i_num => $qty) {
        $qty = intval($qty);
        $total += floatval($tickets[$i_num]['price']) * $qty;

        $i_desc .= $i_num . 'x' . $qty . ', ';
    }

    // Define item price and convert to cents 
    $itemPriceCents = round($total*100); 
     
    // Set content type to JSON 
    header('Content-Type: application/json'); 
     
    try { 
        // Create PaymentIntent with amount and currency 
        $paymentIntent = \Stripe\PaymentIntent::create([ 
            'amount' => $itemPriceCents, 
            'currency' => $currency, 
            'description' => $i_desc, 
            'payment_method_types' => [ 
                'card' 
            ] 
        ]); 
     
        $output = [ 
            'id' => $paymentIntent->id, 
            'clientSecret' => $paymentIntent->client_secret
        ]; 
     
        echo json_encode($output); 
    } catch (Error $e) { 
        http_response_code(500); 
        echo json_encode(['error' => $e->getMessage()]); 
    } 
}elseif($jsonObj->request_type == 'create_customer'){ 
    $payment_intent_id = !empty($jsonObj->payment_intent_id)?$jsonObj->payment_intent_id:''; 

    $user = wp_get_current_user();
    $name = !empty($jsonObj->name)?$jsonObj->name:get_user_meta($user->ID, 'name', true); 
    $email = !empty($jsonObj->email)?$jsonObj->email:$user->user_email; 
     
    // Add customer to stripe 
    try {   
        $customer = \Stripe\Customer::create(array(  
            'name' => $name,  
            'email' => $email 
        ));  
    }catch(Exception $e) {   
        $api_error = $e->getMessage();   
    } 
     
    if(empty($api_error) && $customer){ 
        try { 
            // Update PaymentIntent with the customer ID 
            $paymentIntent = \Stripe\PaymentIntent::update($payment_intent_id, [ 
                'customer' => $customer->id 
            ]); 
        } catch (Exception $e) {  
            // log or do what you want 
        } 
         
        $output = [ 
            'id' => $payment_intent_id, 
            'customer_id' => $customer->id 
        ]; 
        echo json_encode($output); 
    }else{ 
        http_response_code(500); 
        echo json_encode(['error' => $api_error]); 
    } 
}elseif($jsonObj->request_type == 'payment_insert'){ 
    $payment_intent = !empty($jsonObj->payment_intent)?$jsonObj->payment_intent:''; 
    $customer_id = !empty($jsonObj->customer_id)?$jsonObj->customer_id:''; 

    $f_code = !empty($jsonObj->fund_code)?$jsonObj->fund_code:'';
     
    // Retrieve customer info 
    try {   
        $customer = \Stripe\Customer::retrieve($customer_id);  
    }catch(Exception $e) {   
        $api_error = $e->getMessage();   
    } 
     
    // Check whether the charge was successful 
    if(!empty($payment_intent) && $payment_intent->status == 'succeeded'){ 
        // Transaction details  
        $transactionID = $payment_intent->id; 
        $paidAmount = $payment_intent->amount; 
        $paidAmount = ($paidAmount/100); 
        $paidCurrency = $payment_intent->currency; 
        $payment_status = $payment_intent->status; 

        $user = wp_get_current_user();
         
        $name = $email = ''; 
        if(!empty($customer)){ 
            $name = !empty($customer->name)?$customer->name:get_user_meta($user->ID, 'name', true); 
            $email = !empty($customer->email)?$customer->email:$user->user_email; 
        } 

        $name = $name ? $name : get_user_meta($user->ID, 'name', true); 
        $email = $email ? $email : $user->user_email;
         
        // Check if any transaction data is exists already with the same TXN ID 
        $db = new tecdb();

        $query =
        "SELECT `id`
        FROM `transactions`
        WHERE `txn_id` = ?";

        $result = $db->query($query, $transactionID)->fetchArray();

        $payment_id = 0; 
        if(!empty($result) && $payment_id===1){ 
            $payment_id = $result['id']; 
        }else{ 
            // Insert transaction data into the database 
            $user_id = wp_get_current_user()->ID;
            $cart = new Cart($user_id);
            
            $items = $cart->get_items();
            $tickets = Ticket::get_all();

            $images = '';
            foreach ($items as $i_num => $qty) {

                $qty = intval($qty);

                $query = "INSERT INTO `transactions` (`customer_name`, `customer_email`, `item_name`, `item_number`, `item_price`, `item_price_currency`, 
                `paid_amount`, `paid_amount_currency`, `txn_id`, `payment_status`, `created`, `modified`, `qty`, `fund_code`, `user_id`) 
                VALUES (?,?,?,?,?,?,?,?,?,?,NOW(),NOW(), ?, ?, ?)";
    
                $cost = floatval($tickets[$i_num]['price']) * $qty;
                $result = $db->query($query, $name, $email, $tickets[$i_num]['ticket_desc'], $i_num, $cost, $currency, $paidAmount - ($paidAmount - $cost), $paidCurrency, $transactionID, $payment_status, $qty, $f_code, $user_id)->lastInsertID();
                if($result){ 
                    $payment_id = $result;
                    for ($j = 0; $j < $qty; $j++) {
                        $hash = Ticket::create_ticket($transactionID, $i_num);

                        $f = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/tec-tickets/img/qr/' . $hash . '.png';
                        $data = json_encode(
                            array(
                                'pre' => 'tec',
                                'hash' => $hash
                            )
                        );
                        QRCode::png($data, $f, 'L', 10, 10);

                        $images .= '
                        <tr>
                            <td style="border: 0;">
                                <img src="'.$tickets[$i_num]['image_url'].'" width="200">
                            </td>
                            <td style="border: 0;">
                                <img src="'.$_SERVER['SERVER_NAME'] . '/wp-content/plugins/tec-tickets/img/qr/' . $hash . '.png'.'" height="300">
                            </td>
                        </tr>';
                    }
                }
            }

            $cart->clear();
/*
            $stmt = $db->prepare($sqlQ); 
            $stmt->bind_param("ssssdsdsss", $db_customer_name, $db_customer_email, $db_item_name, $db_item_number, $db_item_price, $db_item_price_currency, $db_paid_amount, $db_paid_amount_currency, $db_txn_id, $db_payment_status); 
            $db_customer_name = $name; 
            $db_customer_email = $email; 
            $db_item_name = $itemName; 
            $db_item_number = $itemNumber; 
            $db_item_price = $itemPrice; 
            $db_item_price_currency = $currency; 
            $db_paid_amount = $paidAmount; 
            $db_paid_amount_currency = $paidCurrency; 
            $db_txn_id = $transactionID; 
            $db_payment_status = $payment_status; 
            $insert = $stmt->execute();*/
             
            $headers = 'Content-Type: text/html; charset=UTF-8;' . "\r\n" . 'From: TEC CON <no-reply@tecconvention.com>' . "\r\n";
            $subject = "TEC CON 2022 Tickets";
            $message = '<div style="width:100%; height:100%;">
                    <div style="width:100%;height:410px;background-color:#000;text-align:center;">
                        <img src="https://tecconvention.com/wp-content/uploads/2022/05/teccon-scaled.jpg" width="300" height="400" alt="TEC CON" style="display:block; margin: 0 auto;">
                    </div>
                    <p style="text-align:center;">Thank you for your purchase!</p>
                    <div style="width:100%; text-align:center;">
                        <table>
                            <thead>
                                <tr>
                                    <th>Item Number</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                            
                            </tbody>
                        </table>
                    </div>
                    <p style="text-align:center;">If you have any questions, please reach out to either <a href="mailto:info@theesportcompany.com">info@theesportcompany.com</a> or <a href="mailto:ryan@theesportcompany.com">ryan@theesportcompany.com</a></p>
                    <div style="width: 100%; background-color: #fff; text-align:center;">
                        <table style="border: 0; margin:auto;" cellspacing="10" cellpadding="0">
                            <tbody>'.$images.'</tbody>
                        </table>
                    </div>
                </div>';

            wp_mail($email, $subject, $message, $headers);
        } 
        
         
        $output = [ 
            'payment_id' => base64_encode($payment_id) 
        ]; 
        echo json_encode($output); 
    }else{ 
        http_response_code(500); 
        echo json_encode(['error' => 'Transaction has been failed!']); 
    } 
} 
 
?>