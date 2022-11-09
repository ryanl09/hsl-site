<?php

$path = $_SERVER['DOCUMENT_ROOT'];

include_once($path . '/classes/graphics/Banner.php');
include_once($path . '/classes/user/User.php');

function base_header($params = [], $nosidebar = false){
    $styles='';
    if (isset($params['styles'])) {
        foreach ($params['styles'] as $style) {
            $styles .= '<link rel="stylesheet" href="/css/' . $style . '.css">';
        }
    }

    $scripts = '';
    if (isset($params['scripts'])) {
        foreach ($params['scripts'] as $script) {
            $scripts .= '<script type="text/javascript" src="/js/' . $script . '.js"></script>';
        }
    }

    $custom_style = '';

    if (isset($params['custom_style']) && $params['custom_style'] != ''){
        $custom_style = '<style>';
        foreach ($params['custom_style'] as $identifier => $rules) {
            $custom_style .= $identifier . '{' . $rules . '}';
        }
        $custom_style .= '</style>';
    }

    echo
    '<head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="/includes/jquery-3.6.0.min.js"></script>
        <script src="/js/consts.js"></script>
        <link rel="stylesheet" href="/css/general.css">
        <link rel="stylesheet" href="/css/transition.css">
        
        ' . ($nosidebar ? '' : '<link rel="stylesheet" href="/css/sidebar.css">') . '
        ' . $styles . '
        ' . $scripts . '
        
        <link href="/includes/boxicons.min.css" rel="stylesheet">

        ' . $custom_style . '
    </head>';
}

function href($page) {
    $link = 'https://tecesports.com';//'localhost';

    $link = '';

    if($link==='dashboard'){
        return 'https://tecesports.com';
    }

    switch ($page) {
        case 'profile':
            return $link . '/user' . '/' . $_SESSION['user']->get_username();
        default:
            return $link . '/' . $page;
    }
}

/**
 * sees if we should add the green color to sidebar link
 * @param   string  $page
 * @return  string
 */

function _s($page) {
    $c = '';
    if (!isset($_SESSION['current_page'])) {
        return $c;
    }

    $args = $_SESSION['current_page'];

    if($args[1]===$page){
        $c='current-select';
    }
    return $c;
}

function start_content_full($nav, $s) {
    if (!$s){
        $s = 'login';
    }

    $str = base_header(array('styles' => [$s],'scripts' => [$s]));
    echo '<!DOCTYPE html><html>'.$str.' 
    <body>';
    $args = func_get_args();
    if(count($args)>2){
        $args = array_slice($args, 2);
        start_content($nav, $args);
        return;
    }
    start_content($nav);
}

function end_content_full($ui){
    end_content($ui);
    echo '
        </body>
    </html>';
}

function start_content($nav) {
    $home = '';
    if ($nav){

        $add_cl='';
        $args=func_get_args();
        if (count($args) > 1){
            $add_cl = $args[1][0];
        }
        print_navbar();
        $home = '<section class="'.$add_cl.' home">';
        $p = strtolower($_SESSION['current_page'][1]);
        include_once($path . '/ajax/ajaxdb.php');
        $db = ajaxdb::get_instance();
        $banner = Banner::get($db, $p);
        if ($banner){
            $home .= '<div class="page-banner" style="background-image: url('.$banner.')">';
            $home .= '<h2>'.$p.'</h2>';
            $home .= '</div>';
        }
        $home .= '<div class="page-content">';
        
    }

    echo '
    <input type="hidden" id="csrf" value="'. $_SESSION['csrf'] .'">'.$home;
}

function end_content($ui) {
    if ($ui){
        ui_script();
        echo '
        </div>
    </section>';
    }
}

function print_navbar() {
    $u = 0;
    $user='Guest';
    $id=0;
    $pfp='https://tecesports.com/images/user.png';
    if (isset($_SESSION['user'])) {
        $u = $_SESSION['user'];
        $user = $u->get_username();
        $id = $u->get_id();
        $pfp = $u->profile_image();
    }
    echo '
    <nav class="sidebar close">
            <header>
                <div class="image-text">
                    <span class="image">
                        <img src="' . $pfp . '" alt="">
                    </span>

                    <div class="text logo-text">
                        <span class="name">TEC Esports</span>
                        <span class="profession">' . $user . '</span>
                    </div>
                </div>

                <i class="bx bx-chevron-right toggle"></i>
            </header>

            <div class="menu-bar">
                <div class="menu">' . ($id ? '
                    <li class="search-box">
                        <i class="bx bx-search icon"></i>
                        <input type="text" placeholder="Search...">
                    </li>

                    <ul class="menu-links">
                        <li class="nav-link p-dashboard">
                            <a href="'.href('dashboard').'" class="'._s('dashboard').'">
                                <i class="bx bx-home-alt icon" ></i>
                                <span class="text nav-text">Dashboard</span>
                            </a>
                        </li>
                        
                        <li class="nav-link p-league">
                            <a href="'.href('league').'" class="'._s('league').'">
                                <i class="bx bx-menu icon" ></i>
                                <span class="text nav-text">League</span>
                            </a>
                        </li>

                        <li class="nav-link p-feed" style="display:none;">
                            <a href="'.href('feed').'" class="'._s('feed').'">
                                <i class="bx bx-news icon"></i>
                                <span class="text nav-text">Feed</span>
                            </a>
                        </li>

                        <li class="nav-link p-events">
                            <a href="'.href('events').'" class="'._s('events').'">
                                <i class="bx bx-calendar-event icon" ></i>
                                <span class="text nav-text">Events</span>
                            </a>
                        </li>

                        <li class="nav-link p-messages">
                            <a href="'.href('messages').'" class="'._s('messages').'">
                                <i class="bx bx-message icon" ></i>
                                <span class="text nav-text">Messages</span>
                            </a>
                        </li>

                        <li class="nav-link p-settings">
                            <a href="'.href('settings').'" class="'._s('settings').'">
                                <i class="bx bxs-cog icon"></i>
                                <span class="text nav-text">Settings</span>
                            </a>
                        </li>

                        <li class="nav-link p-user">
                            <a href="'.href('profile').'" class="'._s('user').'">
                                <i class="bx bx-user icon" ></i>
                                <span class="text nav-text">Profile</span>
                            </a>
                        </li>' . ($_SESSION['user']->is_admin() ? ' 
                        <li class="nav-link p-user">
                            <a href="'.href('admin').'" class="'._s('adminpanel').'">
                            <i class="bx bxs-edit icon"></i>
                                <span class="text nav-text">Admin Panel</span>
                            </a>
                        </li>' : '')
                        : '
                        <li class="nav-link p-events">
                            <a href="'.href('events').'" class="'._s('events').'">
                                <i class="bx bx-calendar-event icon" ></i>
                                <span class="text nav-text">Events</span>
                            </a>
                        </li>
                        
                        <li class="nav-link">
                            <a href="'.href('login').'">
                            <i class="bx bxs-user-circle icon"></i>
                                <span class="text nav-text">Log In</span>
                            </a>
                        </li>

                        <li class="nav-link">
                            <a href="'.href('register').'">
                                <i class="bx bx-edit icon" ></i>
                                <span class="text nav-text">Register</span>
                            </a>
                        </li>') . '
                    </ul>
                </div>
                <div class="bottom-content">
                ' . ($id ? '
                    <li class="">
                        <a href="'.href('logout').'">
                            <i class="bx bx-log-out icon" ></i>
                            <span class="text nav-text">Logout</span>
                        </a>
                    </li>' : '') .
                    '<li class="mode" style="display:none;">
                        <div class="sun-moon">
                            <i class="bx bx-moon icon moon"></i>
                            <i class="bx bx-sun icon sun"></i>
                        </div>
                        <span class="mode-text text">Dark mode</span>

                        <div class="toggle-switch">
                            <span class="switch"></span>
                        </div>
                    </li>
                    
                </div>
            </div>

        </nav>';
}

function ui_script() {
    echo
    "<script>
    (function() {
        $(document).ready(function(){
            const sidebar = $('.sidebar');
            const toggle = $('.toggle');
            const searchBtn = $('.search-box');
            const modeSwitch = $('.toggle-switch');
            const modeText = $('.mode-text');
    
            toggle.on('click' , function(){
                sidebar.toggleClass('close');
            })
    
            searchBtn.on('click' , function(){
                sidebar.removeClass('close');
            })
    
            modeSwitch.on('click' , function(){
                $(document.body).toggleClass('dark');
                
                if($(document.body).hasClass('dark')){
                    modeText.text('Light mode');
                }else{
                    modeText.text('Dark mode');
                    
                }
            });
    
            $(window).resize(function() {
                if(window.innerWidth<=800){
                    sidebar.addClass('close');
                }
            });
        });
    })();
    </script>";
}

/**
 * make it easier to print table cells
 * @param   string  $date
 * @return  string
 */

function td($data){
    $args = func_get_args();
    $class='';
    if (count($args) > 1){
        array_shift($args);
        $arg = implode(' ', $args);
        $class=' class="'.$arg.'"';
    }
    return '<td'.$class.'>' . $data . '</td>';
}

/**
 * used on pages to redirect if user is not an administrator
 */

function admin_block(){    
    if (!isset($_SESSION['user']) || (isset($_SESSION['user']) && $_SESSION['user']->get_role() !== 'admin')) {
        header('Location: ' . href('dashboard'));
    }
}

?>