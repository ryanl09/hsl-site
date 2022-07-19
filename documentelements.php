<?php

function print_navbar($user) {

    echo '<nav class="sidebar close">
            <header>
                <div class="image-text">
                    <span class="image">
                        <!--<img src="logo.png" alt="">-->
                    </span>

                    <div class="text logo-text">
                        <span class="name">TEC School Esports</span>
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
                            <a href="#">
                                <i class="bx bx-home-alt icon" ></i>
                                <span class="text nav-text">Dashboard</span>
                            </a>
                        </li>
                        
                        <li class="nav-link">
                            <a href="#">
                                <i class="bx bx-menu icon" ></i>
                                <span class="text nav-text">League</span>
                            </a>
                        </li>

                        <li class="nav-link">
                            <a href="#">
                                <i class="bx bx-news icon"></i>
                                <span class="text nav-text">Feed</span>
                            </a>
                        </li>

                        <li class="nav-link">
                            <a href="#">
                                <i class="bx bx-calendar-event icon" ></i>
                                <span class="text nav-text">My Games</span>
                            </a>
                        </li>

                        <li class="nav-link">
                            <a href="#">
                                <i class="bx bx-bell icon"></i>
                                <span class="text nav-text">Stats</span>
                            </a>
                        </li>

                        <li class="nav-link">
                            <a href="#">
                                <i class="bx bx-message icon" ></i>
                                <span class="text nav-text">Messages</span>
                            </a>
                        </li>' : '
                        <li class="nav-link">
                            <a href="#">
                                <i class="bx bx-bell icon"></i>
                                <span class="text nav-text">Log in</span>
                            </a>
                        </li>

                        <li class="nav-link">
                            <a href="#">
                                <i class="bx bx-message icon" ></i>
                                <span class="text nav-text">Register</span>
                            </a>
                        </li>') . '
                    </ul>
                </div>
                <div class="bottom-content">
                ' . ($user ? '
                    <li class="">
                        <a href="#">
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

?>