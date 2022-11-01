<?php
if (isset($_POST["req"])) {
  require "MessageService.php";
  switch ($_POST["req"]) {
    // Invalid
    default: echo "Invalid request"; break;

    // Show Messages
    case "show":
      $msg = $_MSG->getMsg($_POST["uid"], $_SESSION["user"]["id"]);
      if (count($msg)>0) { foreach ($msg as $m) {
        $out = $m["id_from"] == $_SESSION["user"]["id"]; ?>
        <div class="mRow <?=$out?"mOut":"mIn"?>">
          <div class="mDate"><?=$m["time_sent"]?></div>
        </div>
        <div class="mRow <?=$out?"mOut":"mIn"?>"><div class="mRowMsg">
          <div class="mSender"><?=$m["name"]?></div>
          <div class="mTxt"><?=$m["message"]?></div>
        </div></div>
      <?php }}
      break;

    // Send Message
    case "send":
      echo $_MSG->send($_SESSION["user"]["id"], $_POST["to"], $_POST["msg"])
        ? "OK" : $_MSG->error ;
      break;
  }
}