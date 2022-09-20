<?php

function is_ajax() {
    if ((isset($_SERVER['HTTP_X_REQUESTED_WITH'])) && ($_SERVER['HTTP_X_REQUESTED_WITH']==='XMLHttpRequest')) {
        return array(
            'status' => 1
        );
    }

    return array(
        'status' => 0,
        'error' => 'Invalid request'
    );
}

function check_origin() {
    return array(
        'status' => 1
    );

    if (isset($_SERVER['HTTP_ORIGIN'])) {
        $address = 'http://' . $_SERVER['SERVER_NAME'];
        if (strpos($address, $_SERVER['HTTP_ORIGIN']) !== 0) {
            return array(
                'status' => 0,
                'error' => 'Invalid origin header: ' . $_SERVER['HTTP_ORIGIN']
            );
        }

        return array(
            'status' => 1
        );
    } else {
        return array(
            'status' => 0,
            'error' => 'Missing origin header'
        );
    }
}

function check_get() {
    if (!is_ajax()) {
        return array(
            'status' => 0,
            'error' => 'Error sending request (GET)'
        );
    }

    if ($_SERVER['REQUEST_METHOD']!=='GET') {
        return array(
            'status' => 0,
            'error' => 'Invalid request (GET)'
        );
    }

    return array(
        'status' => 1
    );
}

function check_post() {
    if (!is_ajax()) {
        return array(
            'status' => 0,
            'error' => 'Error sending request (POST)'
        );
    }
    if ($_SERVER['REQUEST_METHOD']!=='POST') {
        return array(
            'status' => 0,
            'error' => 'Invalid request (POST)'
        );
    }

    return array(
        'status' => 1
    );
}

?>