<?php

require_once('documentelements.php');

require_once('classes/event/Event.php');

require_once('classes/services/CreateEventService.php');
require_once('classes/services/CreateTeamService.php');

require_once('classes/team/SubTeam.php');
require_once('classes/team/Team.php');

require_once('classes/user/User.php');
require_once('classes/user/Player.php');



//require_once('classes/util/TECDB.php');

?>

<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <!----======== CSS ======== -->
        <link rel="stylesheet" href="./css/sidebar.css">
        
        <!----===== Boxicons CSS ===== -->
        <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    </head>
    <body>
        
    <?php 
        $user = '';    
        if (isset($_GET['signon'])) {
            $user = new User($_GET['signon']);
        }
        print_navbar($user->get_username()); 
    ?>

        <section class="home">
            <div class="text">

                <?php
                    $user = new User(1);

                    echo $user->is_team_manager();
                ?>

            </div>
        </section>



        <script>
            (function() {
                const body = document.querySelector('body'),
                sidebar = body.querySelector('nav'),
                toggle = body.querySelector(".toggle"),
                searchBtn = body.querySelector(".search-box"),
                modeSwitch = body.querySelector(".toggle-switch"),
                modeText = body.querySelector(".mode-text");

                toggle.addEventListener("click" , () =>{
                    sidebar.classList.toggle("close");
                })

                searchBtn.addEventListener("click" , () =>{
                    sidebar.classList.remove("close");
                })

                modeSwitch.addEventListener("click" , () =>{
                    body.classList.toggle("dark");
                    
                    if(body.classList.contains("dark")){
                        modeText.innerText = "Light mode";
                    }else{
                        modeText.innerText = "Dark mode";
                        
                    }
                });

                $(window).resize(function() {
                    if(window.innerWidth<=800){
                        sidebar.classList.add('close');
                    }
                });
            })();

        </script>


    </body>
</html>