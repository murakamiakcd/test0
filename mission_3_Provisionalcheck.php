<!DOCTYPE html>
<html lang="ja">
<head>
<?php
session_start();
 
header("Content-type: text/html; charset=utf-8");
 
//クロスサイトリクエストフォージェリ（CSRF）対策のトークン判定
if ($_POST['token'] != $_SESSION['token']){
	echo "不正アクセスの可能性あり";
	exit();
}
 
//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');



$errormessage = "";
$urltoken = hash('sha256',uniqid(rand(),1));

///////////////////

$charlength=256;
$charlengthsmall=floor($charlength/3);
//データベースの接続
$dsn = 'mysql:dbname=データベース名;host=ホスト名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn,$user,$password);

$sql= "CREATE TABLE test06"
." ("
. "id INT,"
. "name char(32),"
. "passward TEXT,"
. "mail TEXT,"
. "time TEXT,"
. "frag TEXT"
.");";
$stmt = $pdo->query($sql);










//タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');
$errormessage ="";
//文字数制限
if(mb_strlen($_POST['passward'])>$charlength){
$errormessage = $errormessage . "パスワードは".$charlengthsmall."文字以下にしてください</br>";
}
if(mb_strlen($_POST['name'])>$charlength){
$errormessage = $errormessage . "名前は".$charlengthsmall."文字以下にしてください</br>";
}




//送られてきた情報が空の場合に行う
if(empty($_POST['name'])){
$errormessage = $errormessage . "名前を入力してください</br>";

}







$passward = mb_convert_kana($_POST['passward'], "rnqsk", "utf-8");
$passempty = str_replace(" ", "", $passward);
//送られてきた情報が空の場合に行う
if(empty($passempty)){
$errormessage = $errormessage . "パスワードを入力してください</br>";

}

//送られてきた情報が空の場合に行う
if(empty($_POST['email0'])){
$errormessage = $errormessage . "メールアドレスを入力してください</br>";

}



if(!empty($errormessage)){
header('Location: /mission_3_Provisional.php?em='.$errormessage);
    exit();
}
//エラーメッセージ終わり
echo $errormessage;



//送られてきた情報が空でない場合に行う
if(!empty($_POST['name'])){
//受け取った情報を$nameに代入;
$name = $_POST['name'];
$nametest =str_replace(" ", "", $name);

if(!empty($nametest)){
//送られてきた名前が格納可能な文字数の場合に行う

if(mb_strlen($name)<$charlength){

//送られてきた情報が空でない場合に行う
if(!empty($_POST['passward'])){

//受け取った情報を$passwardに代入;
$passward = mb_convert_kana($_POST['passward'], "rnqsk", "utf-8");
$passempty = str_replace(" ", "", $passward);

//送られてきた情報が空でない場合に行う
if(!empty($passempty)){

//送られてきたパスワードが格納可能な文字数の場合に行う
if(mb_strlen($passward)<$charlength){


//日時の情報を格納



//echo "</br>".$_POST['name'].$_POST['passward'].$_POST['email0']."</br>";



//idが重複の場合ループで再生成
$cnt=1;
$sql = 'SELECT * FROM test06';
$results = $pdo -> query($sql);
while($cnt>0){
$cnt=0;
$id=rand();
foreach ($results as $row){
if($id==$row['id']){
$cnt=$cnt+1;
}
if($_POST['name']==$row['name']){
  echo "既に登録されている名前です";
    header('Location: /mission_3_Provisional.php?em=既に登録されている名前です');
    exit();
}
}}








$name = $_POST['name'];
$passward = $_POST['passward'];
$mail = $_POST['email0'];
$time=date('Y/m/d H:i:s ');
$frag = 'test';


echo "下記の情報で登録しました</br>";
echo "id:".$id."</br>";
echo "name:".$name."</br>";
echo "passward:".$passward."</br>";
echo "mail:".$mail."</br>";
//echo $time."</br>";
//echo $frag."</br>";
//データベースにに書き込む

$sql = $pdo -> prepare("INSERT INTO test06 (id,name, passward,mail,time,frag) VALUES (:id,:name, :passward,:mail,:time,:frag)"); 
$sql -> bindParam(':id', $id, PDO::PARAM_STR);
$sql -> bindParam(':name', $name, PDO::PARAM_STR);
$sql -> bindParam(':passward', $passward, PDO::PARAM_STR);  

$sql -> bindParam(':mail', $mail, PDO::PARAM_STR);  
$sql -> bindParam(':time', $time, PDO::PARAM_STR);  
$sql -> bindParam(':frag', $frag, PDO::PARAM_STR);



$sql -> execute();
$sql = 'SELECT * FROM test06';
$results = $pdo -> query($sql);
foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
    //echo $row['id'].',';
    //echo $row['name'].',';
    //echo $row['passward'].',';
    //echo $row['mail'].',';
    //echo $row['time'].',';
    //echo $row['frag'].'<br>';
}

	$url = "/mission_3_Registration.php"."?urltoken=".$urltoken."&id=".$id;
        $mailto=$_POST['email0'];//送信先
	//Return-Pathに指定するメールアドレス
	//$returnMail = 'web@sample.com';
	$subject = "mission3会員登録のご案内";//タイトル
	$message = "仮登録完了しました。下記URLより本登録をお願いします。\n".$url;//メッセージ
	$headers = "From: from@testbyme.jp";//fromヘッダー
 
	mail($mailto, $subject, $message, $headers);

	echo $_POST['email0'];
	echo "にメールを送信しました\n";


}}}}}}
//echo test9;


///////////////



$sql = 'SELECT * FROM test06';
$results = $pdo -> query($sql);
if(is_array($results)){
foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
    //echo $row['id'].',';
    //echo $row['name'].',';
    //echo $row['frag'].',';
    //echo $row['passward'].',';
    //echo $row['mail'].',';
    //echo $row['time'].'<br>';

}}else{
//echo nodata;
//echo "初めから入力しなおしてください";
}


?>


<meta charset="utf-8">
<title>仮登録確認</title>



</head>
<body style="width: 60em;" >
<h1>仮登録確認画面</h1>


<?php
//タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');

?>
</body>





<a href=/mission_3_home.php>ホームに戻る</a></br>


</html>