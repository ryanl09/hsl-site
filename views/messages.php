<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/documentelements.php');
require_once($path . '/classes/user/User.php');
require_once($path . '/classes/services/MessageService.php')

//start_content_full(1, 'messages');

$id = $_SESSION['user']->get_id();
?>


<!DOCTYPE html>
<html>
  <head>
    <title>Users List</title>
    <link rel="stylesheet" href="messages.css">
    <script src="messages.js"></script>
  </head>
  <body>
    <div id="chat-wrap">
    <!-- (A) CHAT MESSAGES -->
    <div id="chat-messages"></div>
    
    <!-- (B) SET NAME -->
    <form id="chat-name" onsubmit="return chat.start()">
        <input type="text" id="chat-name-set" placeholder="What is your name?" value="Jon Doe" required>
        <input type="submit" id="chat-name-go" value="Start" disabled>
    </form>
    
    <!-- (C) SEND MESSAGE -->
    <form id="chat-send" onsubmit="return chat.send()">
        <input type="text" id="chat-send-text" placeholder="Enter message" required>
        <input type="submit" id="chat-send-go" value="Send">
    </form>
    </div>
  </body>
</html>