<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<title>chat test</title>
<!-- <link rel="stylesheet" href="./common.css"> -->
<?= Asset::css($css); ?>
<?= Asset::js($js,array("charset"=>"UTF-8"))?>
</head>
<body>
<div class="chat-box">
  <div class="chat-face">
    <img src="[自分の画像URL]" alt="自分のチャット画像です。" width="90" height="90">
  </div>
  <div class="chat-area">
    <div class="chat-hukidashi">
      ふきだしなのですーふきだしですーふきだーaaa
    </div>
  </div>
</div>

<div class="chat-box">
  <div class="chat-face">
    <img src="[相手の画像URL]" alt="誰かのチャット画像です。" width="90" height="90">
  </div>
  <div class="chat-area">
    <div class="chat-hukidashi someone">
      ふきだしだよ<br>
      へへへ
    </div>
  </div>
</div>
</body></html>