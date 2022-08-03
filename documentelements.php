<?php

$path = $_SERVER['DOCUMENT_ROOT'];

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
        <link rel="stylesheet" href="/css/transition.css">
        
        ' . ($nosidebar ? '' : '<link rel="stylesheet" href="/css/sidebar.css">') . '
        ' . $styles . '
        ' . $scripts . '
        
        <link href="/includes/boxicons.min.css" rel="stylesheet">
        <link href="/css/loader.css" rel="stylesheet">

        ' . $custom_style . '
    </head>';
}

function href($page) {
    $link = '';//'localhost';

    if($link==='dashboard'){
        return 'localhost';
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
    if($args[3]===$page){
        $c='current-select';
    }
    return $c;
}

function print_navbar() {
    $u = 0;
    $user='Guest';
    $id=0;
    $pfp='https://tecconvention.com/images/user.png';
    if (isset($_SESSION['user'])) {
        $u = $_SESSION['user'];
        $user = $u->get_username();
        $id = $u->get_id();
        $pfp = $u->profile_image();
    }
    echo '<nav class="sidebar close">
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

                        <li class="nav-link p-mygames">
                            <a href="'.href('mygames').'" class="'._s('mygames').'">
                                <i class="bx bx-calendar-event icon" ></i>
                                <span class="text nav-text">My Games</span>
                            </a>
                        </li>

                        <li class="nav-link p-stats">
                            <a href="'.href('stats').'" class="'._s('stats').'">
                                <i class="bx bx-stats icon"></i>
                                <span class="text nav-text">Stats</span>
                            </a>
                        </li>

                        <li class="nav-link p-messages">
                            <a href="'.href('messages').'" class="'._s('messages').'">
                                <i class="bx bx-message icon" ></i>
                                <span class="text nav-text">Messages</span>
                            </a>
                        </li>
                        <li class="nav-link p-user">
                            <a href="'.href('profile').'" class="'._s('user').'">
                                <i class="bx bx-user icon" ></i>
                                <span class="text nav-text">Profile</span>
                            </a>
                        </li>'
                        : '
                        <li class="nav-link">
                            <a href="'.href('login').'">
                            <i class="bx bxs-user-circle icon"></i>
                                <span class="text nav-text">Log in</span>
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
        const body = document.querySelector('body'),
        sidebar = body.querySelector('nav'),
        toggle = body.querySelector('.toggle'),
        searchBtn = body.querySelector('.search-box'),
        modeSwitch = body.querySelector('.toggle-switch'),
        modeText = body.querySelector('.mode-text');

        toggle.addEventListener('click' , () =>{
            sidebar.classList.toggle('close');
        })

        searchBtn.addEventListener('click' , () =>{
            sidebar.classList.remove('close');
        })

        modeSwitch.addEventListener('click' , () =>{
            body.classList.toggle('dark');
            
            if(body.classList.contains('dark')){
                modeText.innerText = 'Light mode';
            }else{
                modeText.innerText = 'Dark mode';
                
            }
        });

        $(window).resize(function() {
            if(window.innerWidth<=800){
                sidebar.classList.add('close');
            }
        });
    })();
    </script>";
}

?>