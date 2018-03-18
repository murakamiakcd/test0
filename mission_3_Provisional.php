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
<title仮登録</title>



</head>
<body style="width: 60em;" >
<h1>仮登録画面</h1>


<?php
//タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');

?>
<script type="text/javascript">
function checkSubmit() {
/////////////確認ポップアップ
	return confirm("この内容で登録してよろしいですか？");
}


</script>
<?=$_GET['em']?>

</body>
<form method="post" action="mission_3_Provisionalcheck.php" name="emailform" onSubmit="return checkSubmit()">

ニックネーム<input type="text" name="name" ><br/>
パスワード<input type="password" name="passward" ><br/>
メールアドレス<input type="email" name="email0" ></br>



<input type="hidden" name="token" value="<?=$token?>">
<input type="submit"name="submitemail" value="送信"  >
</form>


<a href=/mission_3_home.php>ホームに戻る</a></br>



</html>