<?php

$path = $_SERVER['DOCUMENT_ROOT'];
include_once($path . '/test.php');

function wp_get_current_user() {
    $u = new test();
    return $u;
}

function base_header(){
    echo
    '<head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <!----======== CSS ======== -->
        <link rel="stylesheet" href="' . $path . '/css/sidebar.css">
        
        <!----===== Boxicons CSS ===== -->
        <link href="https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    </head>';
}

function href($page) {
    $link = '';//'localhost';
    if ($page!=='index'){
        $link .= '/pages';
    }
    $suf = '.php';
    return $link . '/' . $page . $suf;
}

function print_navbar($user) {

    echo '<nav class="sidebar close">
            <header>
                <div class="image-text">
                    <span class="image">
                        <img src="images/user.png" alt="">
                    </span>

                    <div class="text logo-text">
                        <span class="name">TEC Esports</span>
                        <span class="profession">' . ($user ? $user : 'Welcome!') . '</span>
                    </div>
                </div>

                <i class="bx bx-chevron-right toggle"></i>
            </header>

            <div class="menu-bar">
                <div class="menu">' . ($user ? '
                    <li class="search-box">
                        <i class="bx bx-search icon"></i>
                        <input type="text" placeholder="Search...">
                    </li>

                    <ul class="menu-links">
                        <li class="nav-link">
                            <a href="'.href('index').'">
                                <i class="bx bx-home-alt icon" ></i>
                                <span class="text nav-text">Dashboard</span>
                            </a>
                        </li>
                        
                        <li class="nav-link">
                            <a href="'.href('league').'">
                                <i class="bx bx-menu icon" ></i>
                                <span class="text nav-text">League</span>
                            </a>
                        </li>

                        <li class="nav-link">
                            <a href="'.href('feed').'">
                                <i class="bx bx-news icon"></i>
                                <span class="text nav-text">Feed</span>
                            </a>
                        </li>

                        <li class="nav-link">
                            <a href="'.href('mygames').'">
                                <i class="bx bx-calendar-event icon" ></i>
                                <span class="text nav-text">My Games</span>
                            </a>
                        </li>

                        <li class="nav-link">
                            <a href="'.href('stats').'">
                                <i class="bx bx-stats icon"></i>
                                <span class="text nav-text">Stats</span>
                            </a>
                        </li>

                        <li class="nav-link">
                            <a href="'.href('messages').'">
                                <i class="bx bx-message icon" ></i>
                                <span class="text nav-text">Messages</span>
                            </a>
                        </li>
                        <li class="nav-link">
                            <a href="'.href('profile').'">
                                <i class="bx bx-user icon" ></i>
                                <span class="text nav-text">Profile</span>
                            </a>
                        </li>'
                        : '
                        <li class="nav-link">
                            <a href="'.href('login').'">
                                <i class="bx bx-bell icon"></i>
                                <span class="text nav-text">Log in</span>
                            </a>
                        </li>

                        <li class="nav-link">
                            <a href="'.href('register').'">
                                <i class="bx bx-message icon" ></i>
                                <span class="text nav-text">Register</span>
                            </a>
                        </li>') . '
                    </ul>
                </div>
                <div class="bottom-content">
                ' . ($user ? '
                    <li class="">
                        <a href="'.href('logout').'">
                            <i class="bx bx-log-out icon" ></i>
                            <span class="text nav-text">Logout</span>
                        </a>
                    </li>' : '') .
                    '<li class="mode">
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