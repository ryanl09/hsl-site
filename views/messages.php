<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/documentelements.php');
require_once($path . '/classes/util/tecdb.php');
require_once($path . '/classes/user/User.php');
require_once($path . '/classes/services/MessageService.php')

start_content_full(1, 'messages');

$view = User::get_class_instance(0, $username);
?>


<!DOCTYPE html>
<html>
  <head>
    <title>Users List</title>
    <link rel="stylesheet" href="messages.css">
    <script src="messages.js"></script>
  </head>
  <body>
    <?php
    // Get Users
    require "MessageService.php";
    $users = $_MSG->getUsers($_SESSION["user"]["id"]); ?>

    <!--  LEFT : USER LIST -->
    <div id="uLeft">
      <!-- CURRENT USER -->
      <div id="uNow">
      <div id="uNow">
      <img src="<?php echo $view->profile_image(); ?>" width="50" height="50" alt="">
        <?=$_SESSION["user"]["name"]?>
      </div>
      <!-- USER LIST -->
      <?php foreach ($users as $uid=>$u) { ?>
      <div class="uRow" id="usr<?=$uid?>" onclick="msg.show(<?=$uid?>)">
        <div class="uName"><?=$u["user_name"]?></div>
        <div class="uUnread"><?=isset($u["unread"])?$u["unread"]:0?></div>
      </div>
      <?php } ?>
    </div>

    <!-- (C) RIGHT : MESSAGES LIST -->
    <div id="uRight">
      <!-- (C1) SEND MESSAGE -->
      <form id="uSend" onsubmit="return msg.send()">
        <input type="text" id="mTxt" required>
        <input type="submit" value="Send">
      </form>

       <!-- (C2) MESSAGES -->
       <div id="uMsg"></div>
    </div>
  </body>
</html>