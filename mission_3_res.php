



<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>画像、動画</title>
</head>
<body>



<?PHP
//データベースの接続
$dsn = 'mysql:dbname=データベース名;host=ホスト名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn,$user,$password);

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



echo "<a href=/mission_3_body.php#$linenumber>掲示板に戻る</a></br>";

?>

<!--<a href=/mission_3_body.php>掲示板に戻る</a></br>-->
<p><?php
session_start();
//データベースの接続
$dsn = 'mysql:dbname=データベース名;host=ホスト名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn,$user,$password);

//データベースの作成
$sql= "CREATE TABLE media"
." ("
. "id INT,"
. "fname TEXT,"
. "extension TEXT,"
. "raw_data LONGBLOB"
.");";
$stmt = $pdo->query($sql);




$raw_data = file_get_contents($_FILES["upfile"]["tmp_name"]);
//echo $raw_data;





if (is_uploaded_file($_FILES["upfile"]["tmp_name"])) {
  if (move_uploaded_file($_FILES["upfile"]["tmp_name"], "files/" . $_FILES["upfile"]["name"])) {
    chmod("files/" . $_FILES["upfile"]["name"], 0644);
    echo $_FILES["upfile"]["name"] . "をアップロードしました。";
    
    $ext = pathinfo($_FILES["upfile"]["name"], PATHINFO_EXTENSION);//拡張子
    $extension = pathinfo($_FILES["upfile"]["name"], PATHINFO_EXTENSION);//拡張子
    echo $ext;



    //echo $raw_data;

    $sql = "SELECT * FROM media ORDER BY id;";
    $stmt = $pdo->prepare($sql);
    $stmt -> execute();
        $id= 1;
        while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
        $id=$id+1; 
        //echo ($id);
        }





//画像
            if($extension == "jpg" || $extension == "jpeg" || $extension == "JPG" || $extension == "JPEG"){
                $extension = "jpeg";
            $target=$_FILES["upfile"]["name"];
            $comment="<img src='/files/$target'";
            $comment=$comment.'alt="" width="314" height="229" border="0" />';

            }
            elseif($extension == "png" || $extension == "PNG"){
                $extension = "png";
            $target=$_FILES["upfile"]["name"];
            $comment="<img src='/files/$target'";
            $comment=$comment.'alt="" width="314" height="229" border="0" />';

            }
            elseif($extension == "gif" || $extension == "GIF"){
                $extension = "gif";
            $target=$_FILES["upfile"]["name"];
            $comment="<img src='/files/$target'";
            $comment=$comment.'alt="" width="314" height="229" border="0" />';
            }

            elseif($extension == "bmp" || $extension == "BMP"){
                $extension = "bmp";
            $target=$_FILES["upfile"]["name"];
            $comment="<img src='/files/$target'";
            $comment=$comment.'alt="" width="314" height="229" border="0" />';
            }


//動画
	   elseif($extension == "mp4" || $extension == "MP4"){
                $extension = "mp4";
                $target=$_FILES["upfile"]["name"];
                $comment="<video src='/files/$target' width=\"426\" height=\"240\" controls></video>";
            }
	   elseif($extension == "ogg" || $extension == "ogv"||$extension == "OGG" || $extension == "OGV"){
                $extension = "ogg";
                $target=$_FILES["upfile"]["name"];
                $comment="<video src='/files/$target' width=\"426\" height=\"240\" controls></video>";
            }




        else{
        echo "対応していないファイルです";
        //unset($id);
        //echo $id."test" ;
        }






            //DBに格納するファイルネーム設定
            //サーバー側の一時的なファイルネームと取得時刻を結合した文字列にsha256をかける．
            $date = getdate();
            //$fname = $_FILES["upfile"]["tmp_name"].$date["year"].$date["mon"].$date["mday"].$date["hours"].$date["minutes"].$date["seconds"];
            //$fname = hash("sha256", $fname);
$fname = $_FILES["upfile"]["name"];
            if(!empty($id)){
            //画像・動画をDBに格納．
            $raw_dataencode = base64_encode($raw_data);//base64encode
            $sql = "INSERT INTO media(id, fname, extension, raw_data) VALUES (:id, :fname, :extension, :raw_data);";
            $stmt = $pdo->prepare($sql);
            $stmt -> bindValue(":id",$id, PDO::PARAM_STR);
            $stmt -> bindValue(":fname",$fname, PDO::PARAM_STR);
            $stmt -> bindValue(":extension",$extension, PDO::PARAM_STR);
            $stmt -> bindValue(":raw_data",$raw_dataencode, PDO::PARAM_STR);
            $stmt -> execute();
            //echo "_name_".$fname."_ext_".$extension."_raw_".$raw_data;

            //echo test;
            }


  } else {
    echo "ファイルをアップロードできません。";
  }
} else {
  echo "ファイルが選択されていません。";
}



    $sql = "SELECT * FROM media ORDER BY id  DESC;";
    $stmt = $pdo->prepare($sql);
    $stmt -> execute();
    while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
        echo ("<br/>");
        echo ($row["id"]."<br/>");

        //動画と画像で場合分け
        $target = $row["fname"];
        if($row["extension"] == "mp4" || $row["extension"] == "ogg"){
            //$raw_dataencode = base64_encode($row["raw_data"]);
            //echo $raw_dataencode ;
            //echo ("<video src=");
            //echo ("\"data:video/mp4;base64,");
            $raw_data = $row["raw_data"];
            //echo ("$raw_data \"");
            //echo ('width=\"426\" height=\"240\" controls></video>');




            echo ("<video src='/files/$target' width=\"426\" height=\"240\" controls></video>");
	    echo "動画";
            //$comment="<video src='/files/$target' width=\"426\" height=\"240\" controls></video>";
        }
        elseif($row["extension"] == "jpeg" || $row["extension"] == "png" || $row["extension"] == "gif" || $row["extension"] == "bmp"){

            //$raw_dataencode = base64_encode($row["raw_data"]);
            //echo $raw_dataencode ;
            //echo ("<img src=");
            //echo ("\"data:image/gif;base64,");
            $raw_data = $row["raw_data"];
            //echo ("$raw_data \"");
            //echo ('alt="" width="314" height="229" border="0" />');



            echo ("<img src='/files/$target'");
            echo ('alt="" width="314" height="229" border="0" />');
	    echo "画像";
            //echo $row["raw_data"] ;


        //header('Content-type: image/jpeg');
        //echo $row["raw_data"] ;


            //$comment="<img src='/files/$target'";
            //$comment=$comment.'alt="" width="314" height="229" border="0" />';
        }else{
            echo ($row["extension"]."は対応していないファイル形式です");
        }
        echo ("<br/><br/>");
    }



//echo test;
//掲示板用データベースに書き込み

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


$name=$_SESSION['name'];
//日時の情報を格納
$time=date('Y/m/d H:i:s ');
$passward=$_SESSION['passward'];
if(!empty($comment)){
$sql = $pdo -> prepare("INSERT INTO tbtalk4 (number,name, comment,time,passward ) VALUES (:number,:name, :comment, :time, :passward )"); 
$sql -> bindParam(':number', $linenumber, PDO::PARAM_STR);
$sql -> bindParam(':name', $name, PDO::PARAM_STR);
$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);  
$sql -> bindParam(':time', $time, PDO::PARAM_STR);  
$sql -> bindParam(':passward', $passward, PDO::PARAM_STR);

$sql -> execute();
}

    $linemax=$linemax-2;
    $linemax="";
    $locaturl='/mission_3_body'.$linemax.'.php';
    //echo $locaturl;
    //header('Location: '.$locaturl);
    //exit();



?></p>
</body>
<?php

//$fname0="%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88%20(1).png";
//echo ("<img src=");
//echo ('\'/files/');
//echo ($fname.'\'');
//echo ('alt="" width="314" height="229" border="0" />');
//echo ("</br>");
//echo test ;
?>
<!--<img src='/files/%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88%20(1).png' alt="" width="314" height="229" border="0" />-->
<!--
<?php
echo tes ;
$fname0="mp4_h264_aac.mp4";
echo ("<video src=");
echo ('\'/files/');
echo ($fname0.'\'');
echo (' width="314" height="229"  controls autoplay preload>');
echo ("</br>");
echo test ;
?>
-->
<!--
<video src='/files/mp4_h264_aac.mp4' width="314" height="229"  controls autoplay preload>
-->
</html>
