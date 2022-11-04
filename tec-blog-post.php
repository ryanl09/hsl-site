<?php

header("Access-Control-Allow-Headers: Cache-Control, Pragma, Origin, Authorization, Content-Type, X-Requested-With, Auth-User, Auth-Token");
header("Access-Control-Allow-Origin: *");

if ($_SERVER['HTTP_ORIGIN'] !== 'https://tecesports.com' || $_SERVER['HTTP_REFERER'] !== 'https://tecesports.com/'){
    echo json_encode(
        array(
            'status' => 0,
            'error' => 'Unauthorized request'
        )
    );
    die();
}

$headers = getallheaders();

if (!isset($headers['Auth-User']) || !isset($headers['Auth-Token'])){
    echo json_encode(
        array(
            'status' => 0,
            'error' => 'Invalid authentication headers'
        )
    );
    die();
}

$user = $headers['Auth-User'];
$token = $headers['Auth-Token'];

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/wp-load.php');
global $wpdb;

$query=
"SELECT `id`
FROM `blog_api`
WHERE `user` = %s
    AND `token` = %s";
$res = $wpdb->get_results($wpdb->prepare($query, $user, $token));

if (empty($res)){
    echo json_encode(
        array(
            'status' => 0,
            'error' => "Invalid authentication headers"
        )
    );
    die();
}

function get_blog_posts(){
    $args = array(
        'posts_per_page' => 3,
        'post_type' => 'post',
        'post_status' => 'publish'
    );

    $posts = [];
    $i = 0;

    $query = new WP_Query( $args );
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $post = $query->posts[$i];

            $img = '';
            if(has_post_thumbnail($post->ID)){
                $img = get_the_post_thumbnail($post->ID);
            }

            $add=array(
                'url' => $post->guid,
                'title' => $post->post_title,
                'date' => $post->post_date,
                'content' => $post->post_content,
                'img' => $img
            );
            
            $posts[] = $add;
            $i++;
        }
        wp_reset_postdata();
    }

    return array(
        'status' => 1,
        'posts' => $posts
    );
}

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

$get = check_get();

if (!$get['status']){
    echo json_encode($get);
    die();
}

if (!isset($_GET['action'])){
    echo json_encode(
        array(
            'status' => 0,
            'error' => 'Action not set'
        )
    );
    die();
}

$action = $_GET['action'];

switch ($action){
    case 'get_blog_posts':
        $posts = get_blog_posts();
        echo json_encode($posts);
        break;
    default:
        echo json_encode(
            array(
                'status' => 0,
                'error' => 'Invalid action'
            )
        );
        break;
}

?>