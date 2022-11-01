<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/documentelements.php');

start_content_full(0, 'home'); ?>

<div class="top-wrapper">
    <nav class="top-menu">
        <ul>
            <li>
                <img src="https://tecesports.com/images/tec-black.png" alt="TEC" width="80" height="80">
            </li>
            <li>High School Series</li>
            <li class="mlink homelink"><a href="https://tecesports.com/pricing">Home</a></li>
            <li class="mlink"><a href="https://tecesports.com/pricing">About</a></li>
            <li class="mlink"><a href="https://tecesports.com/pricing">Pricing</a></li>
            <li class="np">
                <a href="https://tecesports.com/login">
                    <button class="btn">Login</button>
                </a>
            </li>
            <li class="np">
                <a href="https://tecesports.com/register">
                    <button class="btn">Register</button>
                </a>
            </li>
        </ul>
    </nav>
</div>

<div class="section sec1">
    
    <!--
    <div class="site-img">
        <img src="https://tecesports.com/images/site.png">
    </div>
    -->

    <div class="main-title">
        <p class="t">The Esport Company</p>
        <p class="b">High School Series</p>
    </div>

    <div class="sp200"></div>

    <div class="subsec">
        <p>What's Included?</p>
        <div class="cards c3 small">
            <div class="card hov">
                <div class="card-top">
                    <i class='bx bxs-user-circle' ></i>User Profiles
                </div>
                <div class="card-body">
                    For students to display their basic info, stats, and VODS.
                </div>
            </div>
            <div class="card hov">
                <div class="card-top">
                    <i class='bx bx-stats' ></i>Stat Tracking
                </div>
                <div class="card-body">
                    Every student's stats are tracked for each season.
                </div>
            </div>
            <div class="card hov">
                <div class="card-top">
                    <i class='bx bx-list-ol' ></i>League Standings
                </div>
                <div class="card-body">
                    View any team's standings, all in one place.
                </div>
            </div>
            <div class="card hov">
                <div class="card-top">
                    <i class='bx bx-chat' ></i>Messaging
                </div>
                <div class="card-body">
                    Instantly connect with recruiters!
                </div>
            </div>
            <div class="card hov">
                <div class="card-top">
                    <i class='bx bxs-t-shirt'></i>Jerseys
                </div>
                <div class="card-body">
                    Each team gets up to 15 jerseys for free!
                </div>
            </div>
            <div class="card hov">
                <div class="card-top">
                    <i class='bx bxl-twitch' ></i>Livestreamed Events
                </div>
                <div class="card-body">
                    Every game is streamed to one of our Twitch channels
                </div>
            </div>
        </div>
    </div>

    <div class="sp200"></div>

    <div class="subsec">
        <p>Quick Links</p>
        <div class="cards c3 small">
        
            <div class="card">
                <div class="card-top">
                    <i class='bx bxs-user-circle' ></i>Teams
                </div>
                <div class="btn-cont">
                    <a href="/teams">
                        <button class="btn smooth view-graphics">
                            <span>View teams this season</span>
                            <i class="bx bx-chevron-right"></i>
                        </button>
                    </a>
                </div>
            </div>
            <div class="card">
                <div class="card-top">
                    <i class='bx bx-stats' ></i>Stats
                </div>
                <div class="btn-cont">
                    <a href="/stats">
                        <button class="btn smooth view-graphics">
                            <span>View all stats</span>
                            <i class="bx bx-chevron-right"></i>
                        </button>
                    </a>
                </div>
            </div>
            <div class="card">
                <div class="card-top">
                    <i class='bx bx-calendar-event' ></i>Schedule
                </div>
                <div class="btn-cont">
                    <a href="/events">
                        <button class="btn smooth view-graphics">
                            <span>View all events</span>
                            <i class="bx bx-chevron-right"></i>
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
    

    <div class="endsec">
        
    </div>
</div>

<?php end_content_full(0); ?>