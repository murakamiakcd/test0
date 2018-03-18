<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>掲示板</title>
<style>
body{
   word-wrap: break-word;//強制改行
}
</style>




</head>
<body style="width: 58em;"  >
<h1>掲示板</h1>


















<?php
session_start();
$referer　= $_SERVER['HTTP_REFERER'];
$url = parse_url($referer);
$host = $url['host'];

echo $host."</br>";
if(!empty($_SESSION['name'])){
echo "<h4>";
echo $_SESSION['name']."さんでなければログアウトしてください";
echo "</h4>";
}
if(empty($_SESSION['id']) or empty($_SESSION['name']) or empty($_SESSION['passward'])){
    echo "ログインしなおしてください";
exit();
}





$charlength=256;
$charlengthsmall=floor($charlength/3);
//データベースの接続
$dsn = 'mysql:dbname=データベース名;host=ホスト名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn,$user,$password);

//データベースの作成
$sql= "CREATE TABLE tbtalk4"
." ("
. "number INT,"
. "name char(255),"
. "comment TEXT,"
. "time TEXT,"
. "passward char(255)"
.");";
$stmt = $pdo->query($sql);


//データベース読み込み
$sql = 'SELECT * FROM tbtalk4 ORDER BY number';
$results = $pdo -> query($sql);
$linenumber=1 ;
foreach ($results as $value){
    //$rowの中にはテーブルのカラム名が入る
    $linenumber=$linenumber + 1 ;
    $semidata[$linenumber - 1][0]=$value['number'];//$number
    $semidata[$linenumber - 1][1]=$value['name'];//$name
    $semidata[$linenumber - 1][2]=$value['comment'];//$comment
    $semidata[$linenumber - 1][3]=$value['time'];//$time
    $semidata[$linenumber - 1][4]=$value['passward'];//$passward

   // echo $value['number'].',';
   // echo $value['name'].',';
   // echo $value['comment'].',';
   // echo $value['passward'].',';
   // echo $value['time'].'<br>';
}

$linemax=$linenumber;







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


//書きこむ場合のエラーメッセージ
if($_POST['mode']=="write"){

//送られてきた情報が空の場合に行う
if(empty($_POST['name'])){
////////chmode
if($_POST['chmode']==on){
$errormessage = $errormessage . "初めから入力し直してください</br>";
}
$errormessage = $errormessage . "名前を入力してください</br>";


}
//送られてきた情報が空の場合に行う
if(empty($_POST['comment'])){
////////chmode
if($_POST['chmode']==on){
//送られてきた情報が空の場合に行う
if(!empty($_POST['name'])){
$errormessage = $errormessage . "初めから入力し直してください</br>";
}}
$errormessage = $errormessage . "コメントを入力してください</br>";

}

if(substr_count($_POST['comment'], "\n")>15){
$errormessage = $errormessage . "改行が多すぎます</br>";
}




$passward = mb_convert_kana($_POST['passward'], "rnqsk", "utf-8");
$passempty = str_replace(" ", "", $passward);
//送られてきた情報が空の場合に行う
if(empty($passempty)){
////////chmode
if($_POST['chmode']==on){
//送られてきた情報が空の場合に行う
if(!empty($_POST['name'])){
//送られてきた情報が空の場合に行う
if(!empty($_POST['comment'])){
$errormessage = $errormessage . "初めから入力し直してください</br>";
}}}
$errormessage = $errormessage . "パスワードを入力してください</br>";

}
}//書きこむ場合のエラーメッセージ終わり
//削除する場合のエラーメッセージ
if($_POST['mode']=="del"){

//送られてきた情報が空の場合に行う

if(empty($_POST['passward'])){
$errormessage = $errormessage . "パスワードを入力してください</br>";
}

//送られてきた情報が空の場合に行う
if(empty($_POST['del'])){
$errormessage = $errormessage . "削除番号を入力してください</br>";
}else{

//数値か判定
if(ctype_digit (mb_convert_kana($_POST['del'], "n", "utf-8"))){
//名前が削除されていない場合に実行
if(empty($semidata[mb_convert_kana($_POST['del'], "n", "utf-8") ][1])){
if(mb_convert_kana($_POST['del'], "n", "utf-8")<$linenumber){
$errormessage = $errormessage . "そのコメントは既に削除されています</br>";
}else{
$errormessage = $errormessage . "削除番号は正しく入力してください1</br>";
}

}
}else{
$errormessage = $errormessage . "削除番号は数字を入力してください</br>";
}}
}//削除する場合のエラーメッセージ終わり
//編集する場合のエラーメッセージ
if($_POST['mode']=="change"){
//送られてきた情報が空の場合に行う
if(empty($_POST['chnum'])){
$errormessage = $errormessage . "編集番号を入力してください</br>";
}else{
//数値か判定
if(ctype_digit (mb_convert_kana($_POST['chnum'], "n", "utf-8"))){
//名前が削除されていない場合に実行
if(mb_convert_kana($_POST['chnum'], "n", "utf-8")<$linenumber){
if(empty($semidata[mb_convert_kana($_POST['chnum'], "n", "utf-8") ][1])){
$errormessage = $errormessage . "そのコメントは既に削除されています</br>";
}
}else{
$errormessage = $errormessage . "編集番号は正しく入力してください</br>";
}
}


}


//送られてきた情報が空の場合に行う
if(empty($_POST['passward'])){
$errormessage = $errormessage . "パスワードを入力してください</br>";
}
}//編集する場合のエラーメッセージ終わり


//編集番号が大きい場合に行う
//if($_POST['mode']=="change"){
//if(!empty($_POST['chnum'])){
//if($_POST['chnum']>=$cnt){
//$errormessage = $errormessage . "編集番号は正しく入力してください</br>";
//}}}
//削除番号が大きい場合に行う
//if($_POST['mode']=="del"){
//if(!empty($_POST['del'])){
//if($_POST['del']>=$cnt){
//$errormessage = $errormessage . "削除番号は正しく入力してください</br>";
//}}}

if(mb_strlen($_POST['name'])>20){
$errormessage = $errormessage . "";
}

$alllength=strlen($linenumber.$_POST['name']."".$_POST['comment']."".date('Y/m/d H:i:s ')."".$_POST['passward']);
$allmblength=mb_strlen($linenumber.$_POST['name']."".$_POST['comment']."".date('Y/m/d H:i:s ')."".$_POST['passward']);
//////////////////////////////test///////////////////////////////
//$errormessage = $errormessage . "バイト数";
//$errormessage = $errormessage . $alllength;
//$errormessage = $errormessage . "文字数";
//$errormessage = $errormessage . $allmblength;
//$errormessage = $errormessage . "</br>";
//$namemblength=mb_strlen($_POST['name']);
//$errormessage = $errormessage . "名前文字数";
//$errormessage = $errormessage . $namemblength;
//$errormessage = $errormessage . "</br>";
//$passwardmblength=mb_strlen($_POST['passward']);
//$errormessage = $errormessage . "パスワード文字数";
//$errormessage = $errormessage . $passwardmblength;
//$errormessage = $errormessage . "</br>";
//$commentmblength=mb_strlen($_POST['comment']);
//$errormessage = $errormessage . "コメント文字数";
//$errormessage = $errormessage . $commentmblength;
//$errormessage = $errormessage . "</br>";
///////////////////////////////////////////////////////////test

//スクロール用フォーム生成時のエラーメッセージ
if($_POST['mode']=="scroll"){

//送られてきた情報が空の場合に行う
if(empty($_POST['scrollnumber'])){
$errormessage = $errormessage . "番号を入力してください</br>";
}

//名前が削除されていない場合に実行
if($_POST['scrollnumber']<$linenumber){
if(empty($semidata[$_POST['scrollnumber']][1])){
$errormessage = $errormessage . "そのコメントは既に削除されています</br>";
}
}else{
$errormessage = $errormessage . "投稿されていない番号です</br>";
}

}
////




////




//送られてきた情報が空でない場合に行う
if(!empty($_POST['name'])){
//受け取った情報を$nameに代入;
$name = $_POST['name'];
$nametest =str_replace(" ", "", $name);
if(!empty($nametest)){
//送られてきたパスワードが格納可能な文字数の場合に行う
if(mb_strlen($name)<$charlength){



//送られてきた情報が空でない場合に行う
if(!empty($_POST['comment'])){
//受け取った情報を$commentに代入;
$comment = $_POST['comment'];

$comment = str_replace("\n", "//ここで改行します。", $comment);


//送られてきた情報が空でない場合に行う
if(!empty($_POST['passward'])){
//受け取った情報を$passwardに代入;
$passward = mb_convert_kana($_POST['passward'], "rnqsk", "utf-8");
$passempty = str_replace(" ", "", $passward);
//送られてきた情報が空でない場合に行う
if(!empty($passempty)){

//送られてきたパスワードが格納可能な文字数の場合に行う
if(mb_strlen($passward)<$charlength){
//改行回数制限
if(substr_count($_POST['comment'], "\n")<16){

//日時の情報を格納
$time=date('Y/m/d H:i:s ');




$chmode = $_POST['chmode'];



//if($chhid!="on"){
if($chmode!="on" ){
//データベースにに書き込む

$sql = $pdo -> prepare("INSERT INTO tbtalk4 (number,name, comment,time,passward ) VALUES (:number,:name, :comment, :time, :passward )"); 
$sql -> bindParam(':number', $linenumber, PDO::PARAM_STR);
$sql -> bindParam(':name', $name, PDO::PARAM_STR);
$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);  
$sql -> bindParam(':time', $time, PDO::PARAM_STR);  
$sql -> bindParam(':passward', $passward, PDO::PARAM_STR);

$sql -> execute();

    $semidata[$linenumber][0]=$linenumber;//line$number
    $semidata[$linenumber][1]=$name;//$name
    $semidata[$linenumber][2]=$comment;//$comment
    $semidata[$linenumber][3]=$time;//$time
    $semidata[$linenumber][4]=$passward;//$passward

//編集モードでないときの書き込み終了
}else{
//編集モード時の処理







//編集対象の変数を変更にする

$time=date('Y/m/d H:i:s ');
$test01="1";
$semidata[mb_convert_kana($_POST['chnumhid'], "n", "utf-8") ][1]="$name";
$semidata[mb_convert_kana($_POST['chnumhid'], "n", "utf-8") ][2]="$comment";
$semidata[mb_convert_kana($_POST['chnumhid'], "n", "utf-8") ][3]="$time";
$semidata[mb_convert_kana($_POST['chnumhid'], "n", "utf-8") ][4]="$passward";
$chnumhid=$_POST['chnumhid'];

//$sql = "update tbtalk4 set name='$name' , comment='$comment' ,time='$time' ,passward='$passward' where number = $chnumhid";
//$result = $pdo->query($sql);


$sql = $pdo -> prepare("update tbtalk4 set name=:name , comment=:comment ,time=:time ,passward=:passward where number = :number"); 
$sql -> bindParam(':number', $chnumhid, PDO::PARAM_STR);
$sql -> bindParam(':name', $name, PDO::PARAM_STR);
$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);  
$sql -> bindParam(':time', $time, PDO::PARAM_STR);  
$sql -> bindParam(':passward', $passward, PDO::PARAM_STR);

$sql -> execute();



$chmodenotwrite="off";
}
$chmode="off";


}


}}}}}}
$chmode="off";






}
//書き込み処理終了
//削除欄が空でない場合に行う
if(!empty($_POST['del'])){
//パスワードが正しい場合に実行

if(mb_convert_kana($_POST['passward'], "rnqsk", "utf-8")==$semidata[mb_convert_kana($_POST['del'], "n", "utf-8")][4]){
//受け取った情報を$delnumに代入;
$delnum = mb_convert_kana($_POST['del'], "n", "utf-8") ;
//数値か判定
if(ctype_digit ($delnum)){
//削除対象の変数を空にする
$semidata[$delnum ][1]="";
$semidata[$delnum ][2]="";
$time=date('Y/m/d H:i:s ');
$passward=$semidata[$delnum ][4];


//$sql = "update tbtalk4 set  name='' , comment=''  where number = $delnum"; 
//$result = $pdo->query($sql);


$sql = $pdo -> prepare("update tbtalk4 set  name='' , comment=''  where number = :number"); 
$sql -> bindParam(':number', $delnum, PDO::PARAM_STR);

$sql -> execute();













}else{
//echo("パスワードを入力してください");
//echo("</br>");
}//パスワードのif文終了
}else{
//削除欄が空でない場合に行う
if(!empty($_POST['passward'])){
$errormessage = $errormessage . "パスワードが間違っています</br>";
}}}
//削除処理終了///////////////////////////////////////////////////////////////////////////////////////////


//編集番号欄が空でない場合に行う//////////////////////////////////////////////////////////////////////////
if(!empty($_POST['chnum'] )and $_POST['chnum']<$linenumber+1){
//パスワードが正しい場合に実行
if(mb_convert_kana($_POST['passward'], "rnqsk", "utf-8")==$semidata[mb_convert_kana($_POST['chnum'], "n", "utf-8")][4]){
//名前が削除されていない場合に実行
if(!empty($semidata[mb_convert_kana($_POST['chnum'], "n", "utf-8")][1])){
//受け取った情報を$chnumに代入;
$chnum = mb_convert_kana($_POST['chnum'], "n", "utf-8") ;
//数値か判定
if(preg_match('/^[0-9]+$/',$chnum)){
//編集対象の変数
$chname=$semidata[$chnum ][1];
$chcomment=$semidata[$chnum ][2];
$chtime=$semidata[$chnum ][3];
$chhid=$_POST['chhid'];
}else{
//編集番号が数字でない場合に行う
//$errormessage = $errormessage . "編集番号は数字を入力してください</br>";
}

}else{
//既に削除されている場合に行う
//数値か判定
if(preg_match('/^[0-9]+$/',$chnum)){
$errormessage = $errormessage . "そのコメントは削除されています</br>";
}}}
else{
//パスワードが空でない場合に行う
if(!empty($_POST['passward'])){
//数値か判定
if(!preg_match('/^[0-9]+$/',$chnum)){
//編集番号が数字でない場合に行う
$errormessage = $errormessage . "パスワードが間違っています</br>";
}else{
$errormessage = $errormessage . "パスワードが間違っています</br>";
}}}
//数値か判定
if(!preg_match('/^[0-9]+$/',mb_convert_kana($_POST['chnum'], "n", "utf-8"))){
$errormessage = $errormessage . "編集番号は数字を入力してください</br>";
}
}else{
}
//
//echo("投稿番号"."名前"."コメント"."投稿時間");
//echo "<br />";


//ブラウザに表示
//for( $i=0;$i<$linenumber + 1 ;$i++ ){
//名前が削除されていない場合に実行
//if(!empty($semidata[$i][1])){
//データベース読み込み
//$sql = 'SELECT * FROM tbtalk4 ORDER BY number';
//$results = $pdo -> query($sql);
//$linenumber=1 ;
//foreach ($results as $value){
    //$valueの中にはテーブルのカラム名が入る
//if(!empty($value['name'])){
//    echo ("投稿番号".$value['number']."  ");
//    echo("名前"."  ".$value['name']."  ");
//    echo("コメント"."  ".$value['comment']."  ");
//    echo("投稿時間".str_replace("-", "/", $value['time']));
//    echo "<br />";
//}}
//for( $i=0;$i<15 ;$i++ ){
// echo "<br />";
//}


//echo("投稿番号".$semidata[$i][0]."  ");
//echo("名前"."  ".$semidata[$i][1]."  ");
//echo("コメント"."  ".$semidata[$i][2]."  ");
//echo("投稿時間".$semidata[$i][3]);
//echo "<br />";

//}}
////////////////////////////エラーの出ている場合に入力内容を変数に代入
if(!empty($errormessage)){
$keepname = $_POST['name'];
$keepcomment = $_POST['comment'];
$keeppassward = $_POST['passward'];
$keepdel = $_POST['del'];
$keepchnum = $_POST['chnum'];
}
///////////////////


//編集番号欄が空でない場合に行う
if(!empty($_POST['chnum'])){
//数値か判定
if(preg_match('/^[0-9]+$/',$chnum)){
$errormessage = $errormessage . "編集番号".$chnum;
}
//数値か判定
if(preg_match('/^[0-9]+$/',mb_convert_kana($chnum, "rnqsk", "utf-8"))){
$chmode="on";
}}
if($chmode=="on"){
$errormessage = $errormessage . "編集モード";
}
//ポップアップ用エラーメッセージ
$alerterrormessage=str_replace("</br>", "\\n", $errormessage);






?>
<!--ブラウザ読み込み時に実行-->

<script type="text/javascript">
window.onload = function(){

Scroll();
checkerrormessage();
//alert('<?=$_POST['scroll_top']?>');
}
</script>
</body>
<!--特定の座標にスクロールする-->

<script type="text/javascript">
function Scroll() {
   window.scrollBy( 0,<?=$_POST['scroll_top']?> );
}
</script>


<!--現在の座標の記録-->

<script type="text/javascript">
function Printposi(){
    var x = document.documentElement.scrollLeft || document.body.scrollLeft;
    var y = document.documentElement.scrollTop || document.body.scrollTop;

     document.getElementById('scroll1').value=y;
     document.getElementById('scroll2').value=y;
     document.getElementById('scroll3').value=y;
     document.getElementById('scroll4').value=y;


}
function Printposibottom(){



}


</script>
<!--番号指定でスクロール-->
<script type="text/javascript">
///////////////////////指定されたIDへ移動
function Printid(){

    
    var scrollid =console.log('number:', form.scrollnumber.value);
    var idposition = $("#\scrollid").offset().top;


    $("html,body").animate({scrollTop:$('#100').offset().top});
}

$(function(){
    $('#pagetop').on('click', function(){
        $('html,body').animate({
            scrollTop: 0
        }, 500);
        return false;
    });
});





</script>
<!--
<form style="position: fixed; right: 0px; top: 650px"align="right">
<input type="button" value="スクロール" onclick="Scroll();" />
<input type="button" value="test" onclick="Printposi();" />
</form>
-->
<script type="text/javascript">
function checkSubmit() {
/////////////削除時の確認ポップアップ
	return confirm("本当に削除しますか？");
}

function checkerrormessage() {	
///////////////////errormessageのポップアップ
	var a="<?=$alerterrormessage?>";
        if(a !== ""){
	return alert("<?=$alerterrormessage?>");
	}	

}
</script>

<?php

//データベース読み込み
$sql = 'SELECT * FROM tbtalk4 ORDER BY number';
$results = $pdo -> query($sql);
$linenumber=0 ;
///////////////////////////////$numbertaguを二次元配列として初期化
foreach ($results as $value){
    $linenumber=$linenumber + 1 ;
}
$numbertagu = array();
for( $i=0;$i<$linenumber + 1 ;$i++ ){
$numbertagu[$i]=array();
for( $j=0;$j<$linenumber + 1 ;$j++ ){
$numbertagu[$i][$j]="";
}}
//echo $linenumber;
//echo $numbertagu[100][400];

//ブラウザに表示
for( $i=0;$i<$linenumber + 1 ;$i++ )
{
//名前が削除されていない場合に実行
if(!empty($semidata[$i][1])){
//データベース読み込み
$sql = 'SELECT * FROM tbtalk4 ORDER BY number';
$results = $pdo -> query($sql);
$linenumber=1 ;

/////////////コメントをスクロールへの変換用の関数
function myFunc2( $replacetestcomment = "" , $replacetestbefore = "" , $replacetestafter = "") {
  if(!empty($replacetestcomment)){
    return preg_replace("/".$replacetestbefore."/", $replacetestafter, $replacetestcomment,1);//一回のみ
  }
}
///////////////





foreach ($results as $value){
    //$valueの中にはテーブルのカラム名が入る
if(!empty($value['name'])){







    $pattern='/to\d{1,4}/';

//////////////to数字の検出
    preg_match_all($pattern, $value['comment'] , $match);
///////////to数字使われている回数
    $countmatch = count($match[0]);
  

    ///コメントをスクロール用変数に代入
    $replacetestcomment=$commentdownon;

   if($countmatch>0){
    //スクロール用フォームとして表示
    for ($i=0;$i<$countmatch;$i++){//要素数分ループ
        $matchnumber = $match[0][$i];
        $matchnumber2 =str_replace("to", "", $matchnumber);
        $matchcomment = mb_convert_kana($matchnumber2, "n", "utf-8");       
        if(!empty($semidata[$matchcomment][1])){

		///コメントをスクロール用フォームへ変換
	     //$replacetestbefore=$matchnumber;
	     //$replacetestafter="<a href=\"#$matchnumber2\">$matchnumber2</a>";
	     //$func2 = 'myFunc2';

	     //$replacetestcomment=$func2($replacetestcomment,$replacetestbefore,$replacetestafter);



////////////どの投稿でどの投稿にリンクをつけているか
             $numbertagu[$matchnumber2][$value['number']]="on";



        }
    }
   }

}}
}}

//foreach ($results as $value){
//    echo $numbertagu[$value[0]];
//}///////////////////////////////////////////////////////////////////////////////////////////
//データベース読み込み
$sql = 'SELECT * FROM tbtalk4 ORDER BY number';
$results = $pdo -> query($sql);
$linenumber=0 ;
foreach ($results as $value){
    $linenumber=$linenumber + 1 ;
}

//echo $linenumber;
//echo $numbertagu[100][400];

//ブラウザに表示
for( $i=0;$i<$linenumber + 1 ;$i++ )
{
$linenumber=1;
//名前が削除されていない場合に実行
if(!empty($semidata[$i][1])){
//データベース読み込み
$sql = 'SELECT * FROM tbtalk4 ORDER BY number';
$results = $pdo -> query($sql);


/////////////コメントをスクロールへの変換用の関数

///////////////





foreach ($results as $value){
    //$valueの中にはテーブルのカラム名が入る
if(!empty($value['name'])){
    echo("<form id=".$value['number'].">");
    echo ("投稿番号".$value['number']."  名前"."  ".$value['name']."</br>");

    echo "linkby";

    for ($i=0;$i<$linemax+1;$i++){

      if($numbertagu[$value['number']][$i]=="on"){
    echo "<a href=\"#$i\"> $i </a>";

      }
    }
    


    echo ("</br>コメント"."</br>");
    $commentdownon = str_replace("//ここで改行します。", "</br>", $value['comment']);
    //echo($commentdownon);
    //echo "</br>";






    $pattern='/to\d{1,4}/';


    preg_match_all($pattern, $value['comment'] , $match);
    $countmatch = count($match[0]);
  

    ///コメントをスクロール用変数に代入
    $replacetestcomment=$commentdownon;

   if($countmatch>0){
    //スクロール用フォームとして表示
    for ($i=0;$i<$countmatch;$i++){//要素数分ループ
        $matchnumber = $match[0][$i];
        $matchnumber2 =str_replace("to", "", $matchnumber);
        $matchcomment = mb_convert_kana($matchnumber2, "n", "utf-8");       
        if(!empty($semidata[$matchcomment][1])){

		///コメントをスクロール用フォームへ変換
	     $replacetestbefore=$matchnumber;
	     $replacetestafter="<a href=\"#$matchnumber2\">@$matchnumber2 </a>";
	     $func2 = 'myFunc2';
/////////コメントをリンクに置換
	     $replacetestcomment=$func2($replacetestcomment,$replacetestbefore,$replacetestafter);







        }
    }
   }
//判定
if(preg_match('\/files\//',$replacetestcomment)){
}else{
    ////////URLをリンクへ置換
   $replacetestcomment = mb_ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]", "<a href=\"\\0\">\\0</a>", $replacetestcomment);
}
    echo $replacetestcomment."</br>";//スクロール用フォームへ変換後のコメント

    echo("</br>"."投稿時間".str_replace("-", "/", $value['time']."</br>"));
    echo("</br></br>");
    echo("</form>");

}}
}}
?>


</br>


<!--入力フォームの情報をmission_3_body.phpに送信-->
<form method="post" action="mission_3_body.php" style="position: fixed; right: 0px; top: 10px" align="right" name="writeform">
<a href=\/mission_3_home.php>ホームに戻る</a></br>
<a href=\/mission_3_logout.php>ログアウト</a></br>
名前<input type="text" name="name" value="<?=$_SESSION['name']?>" readonly="readonly"><br/>
<a >コメント</a></br>
<textarea name="comment"  rows="4" cols="40"><?=$commentdownon = str_replace("//ここで改行します。", "\n", $chcomment).$keepcomment?></textarea></br>
<a >to投稿番号でリンクが付きます</a></br>
パスワード<input type="password" name="passward" value="<?=$_SESSION['passward']?>" readonly="readonly" ><br/>

<input type="hidden" name="chnumhid" value="<?=mb_convert_kana($_POST['chnum'], "n", "utf-8")?>" >
<input type="hidden" name="chmode" value="<?=$chmode?>" >
<input type="hidden" name="mode" value="write" >
<input type="hidden" name="chnumnotwrite" value="<?=$chnumnotwrite?>" >
<input type="hidden" name="chmodenotwrite" value="<?=$chmodenotwrite?>" >

<input id='scroll1' type="hidden" name="scroll_top" value="" >
<input type="submit"name="submit" value="送信"  onclick="Printposi();">
</form>







<form method="post" action="mission_3_body.php" onSubmit="return checkSubmit()" style="position:  fixed; right: 0px; top: 250px" align="right" >
削除対象番号<input type="text" name="del" value="<?=$keepdel?>"><br/>
パスワード<input type="password" name="passward"  ><br/>

<input type="hidden" name="mode" value="del" >
<input type="hidden" name="chmode" value="off" >
<input type="hidden" name="scroll_top" value="<?=$scroll['x']?>" >
<input id='scroll2' type="hidden" name="scroll_top" value="" >

<input type="submit"name="submit2" value="送信" onclick="Printposi();">
</form>

<form method="post" action="mission_3_body.php"style="position:  fixed; right: 0px; top: 350px"align="right">
編集番号<input type="text" name="chnum" value="<?=$keepchnum?>"><br/>
パスワード<input type="password" name="passward" ><br/>

<input type="hidden" name="mode" value="change" >
<input type="hidden" name="chmode" value="<?=$chmode?>" >
<input type="hidden" name="scroll_top" value="" >
<input id='scroll3' type="hidden" name="scroll_top" value="" >
<input type="submit"name="chsubmit1" value="送信" onclick="Printposi();">

</form>


<form method="post" action="mission_3_body.php"style="position:  fixed; right: 0px; top: 430px"align="right">
指定番号への移動用リンク作成</br><input type="number" name="scrollnumber" ><br/>
<input type="hidden" name="mode" value="scroll" >

<input type="hidden" name="scroll_top" value="" >
<input id='scroll4' type="hidden" name="scroll_top" value="" >

<input type="submit"name="scrollsubmit" value="送信"  onclick="Printposi();Printid();">
</form>



<form  style="position: fixed; right: 0px; top: 500px" align="right" >
<?PHP
//指定番号へスクロール
$test = $_POST['scrollnumber'];
if(!empty($semidata[mb_convert_kana($_POST['scrollnumber'], "n", "utf-8") ][1])){
echo "<a href=\"#$test\">$test</a></br>";
}
?>
</form>
<form  style="position: fixed; right: 200px; top: 500px" align="right" >
<?PHP


//データベース読み込み
$sql = 'SELECT * FROM tbtalk4 ORDER BY number';
$results = $pdo -> query($sql);
$linenumber=0 ;
foreach ($results as $value){
    $linenumber=$linenumber + 1 ;
    $namecheck[$linenumber]=$value['name'];
}

//最新の番号へスクロール

while(empty($namecheck[$linenumber])){
if(empty($value['name'][$linenumber])){
$linenumber=$linenumber - 1;
}else{
break;
}
}



echo "<a href=\"#$linenumber\">最新の投稿</a></br>";

?>
</form>
<form action="/mission_3_res.php" method="post" enctype="multipart/form-data" style="position: fixed; right: 0px; top: 600px" align="right" >
  <input type="file" name="upfile" size="30" /><br />
  <input type="submit" value="アップロード" />
</form>


<form method="post" action="mission_3_body.php"style="position:  fixed; right: 100px; top: 500px"align="right">
<input type="hidden" name="scroll_top" value="" >
<a href=>ページトップ</a></br>
</form>




<!--<form method="post" action="mission_3_body.php">
<input type="hidden" name="chmode" value="off" >
<input type="submit"name="chsubmit2" value="編集off">
</form>//手動編集off
-->
<font size=4>
<b>

<!--エラーメッセージ-->
<form style="position: fixed; right: 10px; top: 520px"align="right" >
<?=$errormessage?>
</form>
</font>
</b>
</html>


