<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>ログイン確認</title>



</head>
<body style="width: 60em;" >
<h1>ログイン確認画面</h1>
<a href=\/mission_3_home.php>ホームに戻る</a></br>

<?php
session_start();

header("Content-type: text/html; charset=utf-8");
 

 
//echo "post".$_POST['token'];
//echo "session".$_SESSION['token'];
//クロスサイトリクエストフォージェリ（CSRF）対策のトークン判定
if ($_POST['token'] != $_SESSION['token']){
	echo "不正アクセスの可能性あり";
	//exit();
}


//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');




//タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');
//データベースの接続
$dsn = 'mysql:dbname=データベース名;host=ホスト名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn,$user,$password);

$id = $_POST['id'];
$name=$_POST['name'];
$passward=$_POST['passward'];
//echo $id."</br>".$name."</br>".$passward."</br>";

//データベース読み込み
$sql = 'SELECT * FROM test06 ORDER BY time';
$results = $pdo -> query($sql);
$linenumber=1 ;
$idnumber="";
foreach ($results as $value){
    //echo $value['id'];//$number
    //echo $value['name'];//$name
    //echo $value['mail'];//$mail
    //echo $value['time'];//$time
    //echo $value['passward'];//$passward
    //echo $value['frag']."</br>";//$frag
    if($id==$value['id'] and $name==$value['name'] and $passward==$value['passward']){
         $idnumber=$id;
         
	$_SESSION['id'] = $id;
	$_SESSION['name'] = $name;
        $_SESSION['passward'] = $passward;

        
    }
    if($value['frag']=="test"){
    //echo "</br>";  
    //echo $value['frag']; 
    //echo $value['time']; 
    //echo date("Y/m/d H:i:s",strtotime($value['time'] . "+1 day"));
    //echo date('Y/m/d H:i:s ');
    if(date("Y/m/d H:i:s",strtotime($value['time'] . "+1 day"))<date('Y/m/d H:i:s ')){
    //echo "testtest";
    //echo $value['id'];
$sql = $pdo -> prepare("delete from test06 where id = :id "); 
$sql -> bindParam(':id', $value['id'], PDO::PARAM_STR);

$sql -> execute();
    
    }
    }
}


if(!empty($idnumber)){
    echo "idnumber".$idnumber;

    header('Location: /mission_3_body.php');
    exit();
}else{
    echo "初めから入力しなおしてください";
}










?>
</body>


<!--<a href=></a></br>-->
<!--
<form method="post" action="mission_3_logincheck.php" name="emailform" onSubmit="return checkSubmit()">

<input type="hidden" name="name" ><br/>
<input type="hidden" name="passward" ><br/>
<input type="hidden" name="id" >



<input type="hidden" name="token" value="<?=$token?>">
<input type="submit"name="submitemail" value="送信"  >
</form>
-->


</html>