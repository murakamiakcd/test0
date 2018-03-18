<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>ログアウト</title>



</head>
<body style="width: 60em;" >
<h1>ログアウト画面</h1>


<?php
session_start();
//タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');



$_SESSION = array();
session_destroy();







?>
</body>


<!--<a href=></a></br>-->

<a href=/mission_3_home.php>ホームに戻る</a></br>


</html>