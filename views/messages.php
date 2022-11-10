<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/documentelements.php');

start_content_full(1, 'messages');
?>

<div class="msg-box">
  <div class="convos">
    <div class="convo-h">
      <h3>Conversations</h3>
      <hr class="sep">
    </div>

    <div class="convo-box" user-id="14">
      <div class='pfp-wrap'>
        <div class="pfp"></div>
      </div>
      <div class="msg-info">
        <p class="msg-sender">ryan</p>
        <p class="msg-prev">Hey how's it going!</p>
      </div>
      <div class="msg-time">
        <p>4:48 PM</p>
      </div>
      <div class="msg-view">
        <span><i class='bx bx-chevron-right'></i></span>
      </div>
    </div>
    <hr class="sep">

  </div>
  <div class="chat">

  </div>
</div>

<?php end_content_full(1); ?>