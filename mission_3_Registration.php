<?php
session_start();
 
header("Content-type: text/html; charset=utf-8");
 
//クロスサイトリクエストフォージェリ（CSRF）対策
$_SESSION['token'] = base64_encode(rand());
$token = $_SESSION['token'];
 
//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');
 
?>


<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>本登録</title>



</head>
<body style="width: 60em;" >
<h1>本登録画面</h1>


<?php

$charlength=256;
$charlengthsmall=floor($charlength/3);
//データベースの接続
$dsn = 'mysql:dbname=データベース名;host=ホスト名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn,$user,$password);


$id = $_GET['id'];
echo $id;

$frag=real;

$sql = $pdo -> prepare("update test06 set frag=:frag where id = :id"); 
$sql -> bindParam(':frag', $frag, PDO::PARAM_STR);
$sql -> bindParam(':id', $id, PDO::PARAM_STR);

$sql -> execute();

//データベース読み込み
$sql = 'SELECT * FROM test06 ORDER BY time';
$results = $pdo -> query($sql);

foreach ($results as $value){

    if($id==$value['id']){
        echo "登録完了しました";
    }
}


//タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');












?>
<script type="text/javascript">
function checkSubmit() {
/////////////確認ポップアップ
	return confirm("このメールアドレスで登録してよろしいですか？");
}


</script>


</body>



<a href=/mission_3_home.php>ホームに戻る</a></br>



</html>