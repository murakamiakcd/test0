<?php
session_start();
 
header("Content-type: text/html; charset=utf-8");
 
//クロスサイトリクエストフォージェリ（CSRF）対策
$_SESSION['token'] = base64_encode(rand());
$token = $_SESSION['token'];


//echo "post".$token;
//echo "session".$_SESSION['token'];

//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');
 
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>ログイン</title>



</head>
<body style="width: 60em;" >
<h1>ログイン画面</h1>


<?php
//タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');


?>
</body>


<!--<a href=></a></br>-->

<form method="post" action="mission_3_logincheck.php" name="emailform" onSubmit="return checkSubmit()">

ニックネーム<input type="text" name="name" ><br/>
パスワード<input type="password" name="passward" ><br/>
ID<input type="text" name="id" ><br/>



<input type="hidden" name="token" value="<?=$token?>">
<input type="submit"name="submitemail" value="送信"  >
</form>



</html>