<?php

require_once($path . '/wp-content/plugins/tec-tickets/util/Sessions.php');
Sessions::start();

header('Content-Type: application/json');

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/wp-content/plugins/tec-tickets/Ticket.php');
require_once($path . '/wp-content/plugins/tec-tickets/util/Safe.php');

if (isset($_SERVER['HTTP_ORIGIN'])) {
    $address = 'http://' . $_SERVER['SERVER_NAME'];
    if (strpos($address, $_SERVER['HTTP_ORIGIN']) !== 0) {
        echo json_encode(
            array(
                'error' => 'Invalid origin header: ' . $_SERVER['HTTP_ORIGIN']
            );
        );
        die();
    }
} else {
    echo json_encode(
        array(
            'error' => 'Missing origin header.'
        )
    );
    die();
}

if (isset($_SERVER['CsrfToken'])) {
    if ($_SERVER['CsrfToken']!==$_SESSION['csrf_token']){
        echo json_encode(
            array(
                'error' => 'Invalid CSRF token.'
            )
        );
        die();
    }
} else {
    echo json_encode(
        array(
            'error' => 'Missing CSRF token.'
        )
    );
}

if (isset($_POST['action'])) {
    $action = Safe::parse($_POST['action']);

    switch ($action) {
        case 'add_meta':
            if (!isset($_POST['ticket_id'])) {
                echo json_encode(
                    array(
                        'error' => 'Invalid ticket'
                    )
                );
                die();
            }

            if (!isset($_POST['meta_key'])) {
                echo json_encode(
                    array(
                        'error' => 'Missing meta key.'
                    )
                );
                die();
            }

            $meta_key = Safe::parse($_POST['meta_key']);

            if ($meta_key === 'tshirt') {
                if (!isset($_POST['size'])) {
                    echo json_encode(
                        array(
                            'error' => 'Missing t-shirt size.'
                        )
                    );
                }
                die();

                $meta_value = Safe::parse($_POST['size']);
            } else if ($meta_key === 'rl_team') {
                if (!isset($_POST['members'])) {
                    echo json_encode(
                        array(
                            'error' => 'Missing team members.'
                        )
                    );
                    die();
                }

                $meta_value = Safe::parse($_POST['members']);
            } else {
                echo json_encode(
                    array(
                        'error' => 'Invalid meta key.'
                    )
                );
            }

            $meta = Ticket::add_meta($ticket_id, $meta_key, $meta_value);
            echo json_encode($meta);
            break;
    }
}

?>