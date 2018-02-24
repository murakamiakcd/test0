<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>ミッション2</title>

<style>
body{

   word-wrap: break-word;

}
</style>

</head>
<body style="width: 60em;" >
<h1>掲示板</h1>


<?php
//タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');




$errormessage ="";
//書きこむ場合のエラーメッセージ
if($_POST['mode']=="write"){
//送られてきた情報が空の場合に行う
if(empty($_POST['name'])){
$errormessage = $errormessage . "名前を入力してください</br>";
}
//送られてきた情報が空の場合に行う
if(empty($_POST['comment'])){
$errormessage = $errormessage . "コメントを入力してください</br>";
}
//送られてきた情報が空の場合に行う
if(empty($_POST['passward'])){
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
if(empty($semidata[mb_convert_kana($_POST['del'], "n", "utf-8") - 1][1])){
if(mb_convert_kana($_POST['del'], "n", "utf-8")<$cnt){
$errormessage = $errormessage . "そのコメントは既に削除されています</br>";
}else{
$errormessage = $errormessage . "削除番号は正しく入力してください</br>";
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
if(mb_convert_kana($_POST['chnum'], "n", "utf-8")<$cnt){
if(empty($semidata[mb_convert_kana($_POST['chnum'], "n", "utf-8") - 1][1])){
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



//ファイル名を$filenameに代入
$filename = 'kadai2-6(削除後再編集不可).txt';
//fopenのaモードでファイルを開く
$fp = fopen($filename,'a');
//テキストファイルの読み込み
$data = file_get_contents( 'kadai2-6(削除後再編集不可).txt' );
//配列の分割
$data = explode("\n", $data);
//配列の数
$cnt = count( $data );
//書き込むときの番号
$linenumber = $cnt ;

//配列をさらに分割
for( $i=0;$i<$cnt;$i++ )
{
$semidata[$i] = explode("<>", $data[$i]);
//配列の数
$semicnt[$i] = count( $semidata[$i] );
}





//送られてきた情報が空でない場合に行う
if(!empty($_POST['name'])){
//受け取った情報を$nameに代入;
$name = $_POST['name'];
//送られてきた情報が空でない場合に行う
if(!empty($_POST['comment'])){
//受け取った情報を$commentに代入;
$comment = $_POST['comment'];
$comment = str_replace("\n", "", $comment);


//送られてきた情報が空でない場合に行う
if(!empty($_POST['passward'])){
//受け取った情報を$passwardに代入;
$passward = mb_convert_kana($_POST['passward'], "rnqsk", "utf-8");

//日時の情報を格納
$time=date('Y/m/d H:i:s ');


$chhid = $_POST['chhid'];

$chhid2 = $_POST['chhid'];




//書きこむ場合
if($chhid!="on"){

//開いたテキストファイルに書き込む
fwrite($fp,$linenumber."<>".$name."<>".$comment."<>".$time."<>".$passward."\n");





}else{
//編集モード時の処理



//ファイルを空にする
$fpw = fopen($filename,'w');
fwrite($fpw,"");
fclose($fpw);




//編集対象の変数を変更にする

$time=date('Y/m/d H:i:s ');
$test01="1";
$semidata[mb_convert_kana($_POST['chnumhid'], "n", "utf-8") - 1][1]="$name";
$semidata[mb_convert_kana($_POST['chnumhid'], "n", "utf-8") - 1][2]="$comment";
$semidata[mb_convert_kana($_POST['chnumhid'], "n", "utf-8") - 1][3]="$time";
$semidata[mb_convert_kana($_POST['chnumhid'], "n", "utf-8") - 1][4]="$passward";

//テキストファイルに書き込む
for( $i=0;$i<$cnt-1;$i++ )
{
//fopenのaモードでファイルを開く
$fp = fopen($filename,'a');
fwrite($fp,$semidata[$i][0]."<>".$semidata[$i][1]."<>".$semidata[$i][2]."<>".$semidata[$i][3]."<>".$semidata[$i][4]);
if($i<$cnt-1){
fwrite($fp,"\n");
}

}
$chhid = "off";
if($_POST['chhid']=="on"){
$chhid2="off";
}else{
$chhid2="off";
}



}
}
}
}









//テキストファイルを閉じる
fclose($fp);




//テキストファイルの読み込み
$data = file_get_contents( 'kadai2-6(削除後再編集不可).txt' );
//配列の分割
$data = explode("\n", $data);
//配列の数
$cnt = count( $data );

//配列をさらに分割
for( $i=0;$i<$cnt;$i++ )
{
$semidata[$i] = explode("<>", $data[$i]);
//配列の数
$semicnt[$i] = count( $semidata[$i] );

}
//ここまで入力処理


//削除欄が空でない場合に行う
if(!empty($_POST['del'])){

//パスワードが正しい場合に実行
if(mb_convert_kana($_POST['passward'], "rnqsk", "utf-8")==$semidata[mb_convert_kana($_POST['del'], "n", "utf-8") - 1][4]){


//受け取った情報を$delnumに代入;
$delnum = mb_convert_kana($_POST['del'], "n", "utf-8") ;

//数値か判定
if(ctype_digit ($delnum)){










//テキストファイルの読み込み
$data = file_get_contents( 'kadai2-6(削除後再編集不可).txt' );
//配列の分割
$data = explode("\n", $data);
//配列の数
$cnt = count( $data );
for( $i=0;$i<$cnt;$i++ )
{
$semidata[$i] = explode("<>", $data[$i]);
//配列の数
$semicnt[$i] = count( $semidata[$i] );
}





//ファイルを空にする
$fpw = fopen($filename,'w');
fwrite($fpw,"");
fclose($fpw);
//削除対象の変数を空にする
$semidata[$delnum - 1][1]="";
$semidata[$delnum - 1][2]="削除されました";
//$semidata[$delnum - 1][3]="削除されました";


for( $i=0;$i<$cnt-1;$i++ )
{
//fopenのaモードでファイルを開く
$fp = fopen($filename,'a');
fwrite($fp,$semidata[$i][0]."<>".$semidata[$i][1]."<>".$semidata[$i][2]."<>".$semidata[$i][3]."<>".$semidata[$i][4]);
if($i<$cnt-1){
fwrite($fp,"\n");
}
fclose($fp);
}


}else{
//echo("パスワードを入力してください");
//echo("</br>");





}//パスワードのif文終了




}else{
//削除欄が空でない場合に行う
if(!empty($_POST['passward'])){
$errormessage = $errormessage . "パスワードが間違っています</br>";
}
}
}
//削除処理終了


//編集番号欄が空でない場合に行う
if(!empty($_POST['chnum'] )and $_POST['chnum']<$cnt){


//パスワードが正しい場合に実行
if(mb_convert_kana($_POST['passward'], "rnqsk", "utf-8")==$semidata[mb_convert_kana($_POST['chnum'], "n", "utf-8") - 1][4]){
//名前が削除されていない場合に実行
if(!empty($semidata[mb_convert_kana($_POST['chnum'], "n", "utf-8") - 1][1])){




//受け取った情報を$chnumに代入;
$chnum = mb_convert_kana($_POST['chnum'], "n", "utf-8") ;


//数値か判定
if(preg_match('/^[0-9]+$/',$chnum)){

//テキストファイルの読み込み
$data = file_get_contents( 'kadai2-6(削除後再編集不可).txt' );
//配列の分割
$data = explode("\n", $data);
//配列の数
$cnt = count( $data );






for( $i=0;$i<$cnt;$i++ )
{
$semidata[$i] = explode("<>", $data[$i]);
//配列の数
$semicnt[$i] = count( $semidata[$i] );
}


//編集対象の変数
$chname=$semidata[$chnum - 1][1];
$chcomment=$semidata[$chnum - 1][2];
$chtime=$semidata[$chnum - 1][3];
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
}
}




}
else{
//パスワードが空でない場合に行う
if(!empty($_POST['passward'])){


//数値か判定
if(!preg_match('/^[0-9]+$/',$chnum)){
//編集番号が数字でない場合に行う
$errormessage = $errormessage . "パスワードが間違っています</br>";
}else{
$errormessage = $errormessage . "パスワードが間違っています</br>";
}


}
}

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
for( $i=0;$i<$cnt-1;$i++ )
{
//名前が削除されていない場合に実行
if(!empty($semidata[$i][1])){


echo("投稿番号".$semidata[$i][0]."  ");
echo("名前"."  ".$semidata[$i][1]."  ");
echo("コメント"."  ".$semidata[$i][2]."  ");
echo("投稿時間".$semidata[$i][3]);

echo "<br />";

}
}


//編集番号欄が空でない場合に行う
if(!empty($_POST['chnum'])){


//数値か判定
if(preg_match('/^[0-9]+$/',$chnum)){
$errormessage = $errormessage . "編集番号".$chnum;

}


//数値か判定
if(preg_match('/^[0-9]+$/',$chnum)){
$chhid2="on";
}


}
if($chhid2=="on"){
$errormessage = $errormessage . "編集モード".$_POST['chhid'];

}
//if($_POST['chhid']=="on"){
//$testtest="on";
//}else{
//$testtest="off";
//}

?>
</body>




<!--入力フォームの情報をmission2-6.phpに送信-->
<form method="post" action="mission_2-6(削除後再編集不可).php" style="position: absolute; right: 0px; top: 50px" align="right" >
名前<input type="text" name="name" value="<?=$chname?>"><br/>
<a >コメント</a></br>
<textarea name="comment"  rows="4" cols="40"><?=$chcomment?></textarea></br>

パスワード<input type="text" name="passward"  ><br/>
<input type="hidden" name="chhid" value="<?=$chhid2?>" >
<input type="hidden" name="chnumhid" value="<?=$_POST['chnum']?>" >
<input type="hidden" name="mode" value="write" >
<input type="submit"name="submit" value="送信">
</form>



<script type="text/javascript">
function checkSubmit() {
	return confirm("本当に削除しますか？");
}
</script>

<form method="post" action="mission_2-6(削除後再編集不可).php" onSubmit="return checkSubmit()" style="position: absolute; right: 0px; top: 250px" align="right" >
削除対象番号<input type="text" name="del"><br/>
パスワード<input type="text" name="passward"  ><br/>
<input type="hidden" name="chhid" value="<?=$_POST['chhid']?>" >
<input type="hidden" name="mode" value="del" >
<input type="submit"name="submit2" value="送信">
</form>

<form method="post" action="mission_2-6(削除後再編集不可).php"style="position: absolute; right: 0px; top: 350px"align="right">
編集番号<input type="text" name="chnum"><br/>
パスワード<input type="text" name="passward" ><br/>
<input type="hidden" name="chhid" value="on" >
<input type="hidden" name="mode" value="change" >
<input type="submit"name="chsubmit1" value="送信">

</form>

<!--<form method="post" action="mission_2-6(削除後再編集不可).php">
<input type="hidden" name="chhid" value="off" >
<input type="submit"name="chsubmit2" value="編集off">
</form>//手動編集off
-->
<!--<form method="post" action="mission_2-6(削除後再編集不可).php">
//編集用名前<input type="text" name="chname"value="<?=$chname?>" ><br/>
//編集用コメント<input type="text" name="chcomment"value="<?=$chcomment?>" ><br/>
//<input type="submit"name="chsubmit" value="送信">
//</form>
-->
<form style="position: absolute; right: 800px; top: 0px"align="right">
<?=$errormessage?>
</form>




</html>