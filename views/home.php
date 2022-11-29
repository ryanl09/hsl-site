<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/documentelements.php');

start_content_full(0, 'home'); ?>

<div class="top-wrapper">
    <nav class="top-menu">
        <ul>
            <li>
                <img src="https://tecesports.com/images/tec-white.png" alt="TEC" width="80" height="80">
            </li>
            <li class="mlink homelink"><a href="https://tecesports.com">Home</a></li>
            <li class="mlink"><a href="https://tecesports.com/home#about">About</a></li>
            <li class="mlink"><a href="https://tecesports.com/pricing">Pricing</a></li>
            <?php if (isset($_SESSION['user']) && $_SESSION['user']->get_id()) { ?>
                <li class="mlink">
                    <a href="https://tecesports.com/user/<?php echo $_SESSION['user']->get_username(); ?>" style="display:flex; align-items:center;">
                        <i class='bx bxs-user' style="margin-right:10px; font-size:18px;"></i>
                        <span style="font-weight:600;"><?php echo $_SESSION['user']->get_username(); ?></span>
                    </a>
                </li>
            <?php } else { ?>
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
            <?php } ?>
        </ul>
    </nav>
</div>

<div class="section sec1">

    <div class="sp200"></div>

    <div class="main-title">
        <p class="t">The Esport Company</p>
        <p class="b">High School Series</p>
        <p class="d">Creating the minor league system for esports utilizing technology, education, and community.</p>
    </div>

    <div class="sp200"></div>

    <!--<img src="https://tecesports.com/images/hss-crop.png" width="1920" height="1144" alt="" class="hss-img">-->
    <img src="https://tecesports.com/images/home-pc.png" width="300" height="293" alt="" class="pc-img">

    <div style="height: 150px; overflow: hidden;" class="wave">
        <svg viewBox="0 0 500 150" preserveAspectRatio="none" style="height: 100%; width: 100%;">
            <path d="M0.00,49.98 C149.99,150.00 349.20,-49.98 500.00,49.98 L500.00,150.00 L0.00,150.00 Z" style="stroke: none; fill: #fff;"></path>
        </svg>
    </div>
</div>

<div class="section sec2">

    <div class="s-title">
        <p id="about" class="qlink">What's Included?</p>
        <div class="bar"></div>
    </div>
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
                Every game is streamed to one of our Twitch channels.
            </div>
        </div>
    </div>

    <div class="sp200"></div>
    
    <div style="height: 150px; overflow: hidden;" class="wave">
        <svg viewBox="0 0 500 150" preserveAspectRatio="none" style="height: 100%; width: 100%;">
            <path d="M0.00,49.98 C149.99,150.00 349.20,-49.98 500.00,49.98 L500.00,150.00 L0.00,150.00 Z" style="stroke: none; fill: #e4e9e7;"></path>
        </svg>
    </div>

</div>

<div class="section sec3">

    <div class="s-title">
        <p id="pricing">Pricing</p>
        <div class="small-bar bar"></div>
    </div>

    <div class="div" style="width:100%; height:16px;"></div>
    <p class="pricing-desc desc">Pricing starts at $3,500 / year, and includes:</p>
    <div class="sp60"></div>

    <div class="cards small c2">
        <div class="card hov card-flex">
            <i class='bx bx-desktop' ></i>
            <p>Equipment</p>
        </div>
        <div class="card hov card-flex">
            <i class='bx bx-edit-alt'></i>
            <p>Curriculum</p>
        </div>
        <div class="card hov card-flex">
            <i class='bx bxs-t-shirt' ></i>
            <p>Jerseys</p>
        </div>
        <div class="card hov card-flex">
            <i class='bx bx-calendar' ></i>
            <p>Statewide Matches</p>
        </div>
    </div>

    <div class="sp120"></div>
</div>

<div class="section sec4">
    <div class="sp120"></div>

    <div class="s-title">
        <p>Want to Learn More?</p>
        <div class="bar"></div>
    </div>

    <a href="https://calendly.com/theesportcompany/intro" class="a-btn a-green">Schedule an Intro Call</a>
    <div class="sp120"></div>
</div>

<div class="section sec5">
    <div class="sp120"></div>

    <div class="s-title">
        <p>Quick Links</p>
        <div class="bar"></div>
    </div>

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
    <div class="sp60"></div>
</div>

<div class="footer">
    <div class="footer-top">
        <div class="footer-title">
            <p class="ft1">TEC Esports</p>
            <p class="ft2">High School Series</p>
        </div>
        <div class="social-icons">
            <a href="https://www.facebook.com/TheEsportCompany/"><i class='bx bxl-facebook'></i></a>
            <a href="https://www.instagram.com/theesportcompany/?hl=en"><i class='bx bxl-instagram' ></i></a>
            <a href="https://twitter.com/esportcompanyus"><i class='bx bxl-twitter' ></i></a>
            <a href="https://www.twitch.tv/theesportcompany"><i class='bx bxl-twitch' ></i></a>
            <a href="https://www.tiktok.com/@theesportcompany" style="position:relative;">
                <i class='bx bxl-tiktok b' ></i>
                <i class='bx bxl-tiktok r' ></i>
                <i class='bx bxl-tiktok' ></i>
            </a>
            <a href="https://www.linkedin.com/company/the-esport-company"><i class='bx bxl-linkedin' ></i></a>
        </div>
    </div>
    <div class="footer-bottom">

    </div>
</div>

<?php end_content_full(0); ?>