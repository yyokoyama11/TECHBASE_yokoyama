<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
    <style>
	.class1{
		font-size:16px;
		color:red;
    }
	.class2{
		font-size:16px;
		color:green;
    }    
    </style>
</head>
<body>

    <?php 
        /* - - - - - - - DB接続設定 - - - - - - - */
        $dsn = "mysql:dbname=**********;host=localhost";
        $user = "tb-******";
        $password = "**********";
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        $sql = "CREATE TABLE IF NOT EXISTS tbdata1"
        ." ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name char(32),"
        . "comment TEXT,"
        . "time TEXT,"
        . "pass char(32)"
        .");";
        $stmt = $pdo->query($sql);
        
        /* - - - - - - - 投稿用の関数を定義 - - - - - - - */
        function postfunc(){
            #名前の入力があるとき
            if(isset($_POST["str1"])&&$_POST["str1"]!==""){
                #コメントの入力があるとき
                if(isset($_POST["str2"])&&$_POST["str2"]!==""){
                    #パスワードの入力があるとき
                    if(isset($_POST["pass"])&&$_POST["pass"]!==""){
                        #グローバル変数の宣言(pdoは正直よくわからん)
                        global $pdo;
                        #新規投稿
                        $sql = $pdo -> prepare("INSERT INTO tbdata1 (name, comment, time, pass) 
                        VALUES (:name, :comment, :time, :pass)");
                        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                        $sql -> bindParam(':time', $when, PDO::PARAM_STR);
                        $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
                        #フォームの入力を変数に格納
                        $name=$_POST["str1"];
                        $comment=$_POST["str2"];
                        $when=date("Y/m/d H:i:s");
                        $pass=$_POST["pass"];
                        $sql -> execute();
                        echo "<div class='class2'>コメントを投稿しました<br></div>";
                    }
                    #パスワードの入力がないとき
                    else echo "<div class='class1'>**投稿フォームにパスワードが入力されていません**<br></div>";
                }
                #コメントの入力がないとき
                else echo "<div class='class1'>**投稿フォームにコメントが入力されていません**<br></div>";
            }
            #名前の入力がないとき
            else echo "<div class='class1'>**投稿フォームに名前が入力されていません**<br></div>";
            
        }
        
        /* - - - - - - - 削除用の関数を定義 - - - - - - - */
        function deletefunc(){
            #削除対象番号の入力があるとき
            if(isset($_POST["delete_num"])&&$_POST["delete_num"]!==""){
                #削除フォームにパスワードの入力があるとき
                if(isset($_POST["delete_pass"])&&$_POST["delete_pass"]!==""){
                    #グローバル変数の宣言
                    global $pdo;
                    #フォームの入力を変数に格納
                    $id=$_POST["delete_num"];
                    $dpass=$_POST["delete_pass"];
                    $sql="SELECT * FROM tbdata1";
                    $stmt = $pdo->query($sql);
                    $results = $stmt->fetchAll();
                    #resultsに格納したデータを1列ずつ取り出す
                    foreach($results as $row){
                        #投稿番号の記録
                        $postnumbers[]=$row["id"];
                        #投稿番号と削除番号が一致するとき
                        if($id==$row["id"]){
                            #パスワードが一致するとき
                            if($dpass==$row["pass"]){
                                #削除
                                $sql = "delete from tbdata1 where id=:id";
                                $stmt = $pdo->prepare($sql);
                                $stmt->bindParam(":id", $id, PDO::PARAM_INT);
                                $stmt->execute();
                                echo "<div class='class2'>$id 番目の投稿を削除しました<br></div>";
                            }
                            #パスワードが一致しないとき
                            else echo "<div class='class1'>**削除フォームのパスワードが違います**<br></div>";
                        }
                    }
                    #削除番号が存在しないとき
                    if(in_array($id,$postnumbers,true)==false)
                    echo "<div class='class1'>**削除対象番号が存在しません**<br></div>";
                }
                #削除フォームにパスワードの入力がないとき
                else echo "<div class='class1'>**削除フォームのパスワードが入力されていません**<br></div>";
            }
            #削除番号の入力がないとき
            else echo "<div class='class1'>**削除対象番号が入力されていません**<br></div>";
        }
        
        /* - - - - - - - 編集確認用の関数を定義 - - - - - - - */
        function echeckfunc(){
            #編集対象番号の入力がないとき
            if(isset($_POST["edit_num"])&&$_POST["edit_num"]!==""){
                #編集フォームにパスワードの入力がないとき
                if(isset($_POST["edit_pass"])&&$_POST["edit_pass"]!==""){
                    #グローバル変数の宣言(enumやenameなど関数の外でも使用するものを宣言)
                    global $pdo,$enum,$ename,$ecom;
                    #フォームの入力を変数に格納
                    $enum=$_POST["edit_num"];
                    $epass=$_POST["edit_pass"];
                    #編集
                    $sql="SELECT * FROM tbdata1";
                    $stmt = $pdo->query($sql);
                    $results = $stmt->fetchAll();
                    #resultsに格納したデータを1列ずつ取り出す
                    foreach($results as $row){
                        #投稿番号の記録
                        $postnumbers[]=$row["id"];
                        #投稿番号と編集番号が一致するとき
                        if($enum==$row["id"]){
                            #パスワードが一致するとき
                            if($epass==$row["pass"]){
                                $ename=$row["name"];
                                $ecom=$row["comment"];
                                return 1;
                            }
                            #パスワードが一致しないとき2を返す
                            else return 2;
                        }
                    }
                    #編集番号が存在しないとき3を返す
                    if(in_array($enum,$postnumbers,true)==false)return 3;
                }
                #パスワードの入力がないとき4を返す
                else return 4;
            }
            #編集番号の入力がないとき5を返す
            else{
                $enum="";
                return 5;
            }
        }
        
        /* - - - - - - - 編集用の関数を定義 - - - - - - - */
        function editfunc(){
            #名前の入力があるとき
            if(isset($_POST["str1"])&&$_POST["str1"]!==""){
                #コメントの入力があるとき
                if(isset($_POST["str2"])&&$_POST["str2"]!==""){
                    #パスワードの入力があるとき
                    if(isset($_POST["pass"])&&$_POST["pass"]!==""){
                        global $pdo;
                        #フォームの入力を変数に格納
                        $id = $_POST["hedit_num"];
                        $name = $_POST["str1"];
                        $comment = $_POST["str2"];
                        $pass = $_POST["pass"];
                        $when=date("Y/m/d H:i:s")." *編集済*";
                        #編集
                        $sql = 'UPDATE tbdata1 SET name=:name,comment=:comment,pass=:pass,time=:time WHERE id=:id';
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                        $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
                        $stmt->bindParam(':time', $when, PDO::PARAM_STR);
                        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                        $stmt->execute();
                        echo "<div class='class2'>$id 番目の投稿を編集しました<br></div>";
                    }
                    else echo "<div class='class1'>**パスワードが未入力のため編集できませんでした**<br></div>";
                }
                else echo "<div class='class1'>**コメントが未入力のため編集できませんでした<br></div>";
            }
            else echo "<div class='class1'>**名前が未入力のため編集できませんでした**<br></div>";
        }
        
        #編集ボタンが押されたとき
        if(isset($_POST["submit3"])){
            #編集確認
            echeckfunc();
        }
    ?>
    
    <form action="" method="post">
    <label>【投稿フォーム】<br>
    <input type="text" name="str1" placeholder="名前" value="<?php if(empty($enum)){echo "";}else echo $ename; ?>">
    <input type="hidden" name="hedit_num" value="<?php if(empty($enum)){echo "";}else echo $enum; ?>"><br>
    <input type="text" name="str2" placeholder="コメント" value="<?php if(empty($enum)){echo "";}else echo $ecom; ?>"><br>
    <input type="password" name="pass" placeholder="パスワード">
    <input type="submit" name="submit1" value="送信"><br>
    <br>
    <label>【削除フォーム】<br>
    <input type="number" name="delete_num" placeholder="削除対象番号"><br>
    <input type="password" name="delete_pass" placeholder="パスワード">        
    <input type="submit" name="submit2"value="削除"><br>
    <br>
    <label>【編集フォーム】<br>
    <input type="number" name="edit_num" placeholder="編集対象番号"><br>
    <input type="password" name="edit_pass" placeholder="パスワード">
    <input type="submit" name="submit3"value="編集"><br>
    </form>    

    <?php
        #送信ボタンが押されたとき
        if(isset($_POST["submit1"])){
            #フラグ用フォームが空でないとき
            if(!empty($_POST["hedit_num"])){
                #編集開始
                editfunc();
            }
            #空のとき投稿開始
            else postfunc();
        }
        
        #削除ボタンが押されたとき
        if(isset($_POST["submit2"])){
            #削除開始
            deletefunc();
        }
        
        #編集ボタンが押されたとき
        if(isset($_POST["submit3"])){
            #返り値に応じてコメントを表示
            if(echeckfunc()==1)echo "<div class='class2'>**投稿フォームから投稿を編集してください**<br></div>";
            elseif(echeckfunc()==2)echo "<div class='class1'>**編集フォームのパスワードが違います**<br></div>";
            elseif(echeckfunc()==3)echo "<div class='class1'>**編集対象番号が存在しません**<br></div>";
            elseif(echeckfunc()==4)echo "<div class='class1'>**編集フォームのパスワードが入力されていません**<br></div>";
            elseif(echeckfunc()==5)echo "<div class='class1'>**編集対象番号が入力されていません**<br></div>";
        }
        
        /* - - - - - - - データの表示 - - - - - - - */
        echo "<br>【投稿一覧】<br>";
        $sql = 'SELECT * FROM tbdata1';
        $stmt = $pdo->query($sql);
        $alldata = $stmt->fetchAll();
        foreach ($alldata as $data){
            #$dataに格納されている各カラムを表示
            echo $data["id"]."　";
            echo $data["name"]."　";
            echo $data["comment"]."　";
            echo $data["time"];
            #echo $data["pass"]."<br>";
        echo "<hr>";
    }
    ?>    
    
</body>
</html>