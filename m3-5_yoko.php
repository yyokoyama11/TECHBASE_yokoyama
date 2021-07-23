<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_3-5</title>
</head>
<body>
    
    <?php
        #ファイル名の指定
        $filename="mission_3-5.txt";
        
        /*- - - - - - - - - - - - - 投稿件数カウント- - - - - - - - - - - - - */
        $counter=0;
        #ファイルが存在する時
        if(file_exists($filename)){
            #ファイルの中身を配列に格納
            $lines=file($filename,FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
            #配列を1行ずつ取り出す
            foreach($lines as $line){
                #"<>"を取り除き新たな配列に格納
                $li=explode("<>",$line);
                #投稿番号をcounterに格納
                $counter=$li[0];
                #投稿番号を記録
                $postnumbers[]=$li[0];
            }
        }
        
        /*- - - - - - - - - - - - - 編集判定- - - - - - - - - - - - - */
        #編集パスワード判定用フラグ
        $eFlag=0;
        
        #編集ボタンが押されたとき
        if(isset($_POST["submit3"])){
            #編集番号の入力確認
            if(!empty($_POST["edit_num"])){
                #パスワードの入力確認
                if(isset($_POST["edit_pass"])&&$_POST["edit_pass"]!==""){
                    #フォーム内の文字列を格納
                    $enum=$_POST["edit_num"];
                    $epass=$_POST["edit_pass"];
                    #テキストファイルの読み取り
                    $fp0= fopen($filename,"r+");
                    #ファイルの中身を配列に格納
                    $lines=file($filename,FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
                    #編集番号が存在する時
                    if(in_array($enum,$postnumbers,true)==true){
                        #配列を1行ずつ取り出す
                        foreach($lines as $line){
                            #"<>"を取り除き新たな配列に格納
                            $li=explode("<>",$line);
                            #lineの0番目の要素が編集番号と同じとき
                            if($enum==$li[0]){
                                #lineの4番目の要素がパスワードと一致するとき
                                if($epass==$li[4]){
                                    $ename=$li[1];
                                    $ecom=$li[2];
                                }
                                #パスワードが一致しないとき
                                else {
                                    $eFlag=1;
                                    $enum="";
                                }
                            }
                        }
                    }
                    #編集番号が存在しない時
                    else $enum="";
                    fclose($fp0);
                }
            }
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
        #現在のファイルの最終番号+1
        $counter++;
        #echo $counter."<br>";
        
        /*- - - - - - - - - - - - - 警告文の表示- - - - - - - - - - - - -*/
        #投稿ボタンが押されたとき
        if(isset($_POST["submit1"])){
            #投稿フォームが入力されていないとき
            if($_POST["str1"]=="")echo "**名前を入力してください**"."<br>";
            if($_POST["str2"]=="")echo "**コメントを入力してください**"."<br>";
            if($_POST["pass"]=="")echo "**パスワードを入力してください1**"."<br>";
        }
        #削除ボタンが押されたとき
        elseif(isset($_POST["submit2"])){
            #削除番号が入力されていないとき
            if($_POST["delete_num"]=="")echo "**削除対象番号を入力してください**"."<br>";
            #削除番号が存在しないとき
            elseif(in_array($_POST["delete_num"],$postnumbers,true)==false)echo "**削除対象番号が不適切です**"."<br>";
            #パスワードが入力されていないとき
            if($_POST["delete_pass"]=="")echo "**パスワードを入力してください2**"."<br>";
        }
        #編集ボタンが押されたとき
        elseif(isset($_POST["submit3"])){
            #編集番号が入力されていないとき
            if($_POST["edit_num"]=="")echo "**編集対象番号を入力してください**"."<br>";
            #編集番号が存在しないとき
            elseif(in_array($_POST["edit_num"],$postnumbers,true)==false)echo "**編集対象番号が不適切です**"."<br>";
            #パスワードが入力されていないとき
            if($_POST["edit_pass"]=="")echo "**パスワードを入力してください3**"."<br>";
        }
        #編集パスワード判定用フラグが1のとき
        if($eFlag==1)echo "**パスワードが違います3**"."<br>";

        /*- - - - - - - - - - - - - 投稿および編集- - - - - - - - - - - - -*/
        #投稿ボタンが押されたとき
        if(isset($_POST["submit1"])){
            #フラグ用フォームが空のとき
            if(empty($_POST["hedit_num"])){
                #フォーム内の入力確認
                if(isset($_POST["str1"])&&isset($_POST["str2"])&&isset($_POST["pass"])){
                    if($_POST["str1"]!==""&&$_POST["str2"]!==""&&$_POST["pass"]!==""){
                        #フォーム内の文字列を格納
                        $str1=$_POST["str1"];
                        $str2=$_POST["str2"];
                        $pass=$_POST["pass"];
                        #投稿時間を変数に格納
                        $when=date("Y/m/d H:i:s");
                        #投稿情報を変数に格納
                        $comment1=$counter."<>".$str1."<>".$str2."<>".$when."<>".$pass;
                        #ファイル作成および追記モードを指定
                        $fp=fopen("$filename","a");
                        #ファイルの中身を配列に格納
                        $lines=file($filename,FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
                        #投稿をファイルへ書き込み
                        fwrite($fp,$comment1.PHP_EOL);
                        fclose($fp);
                        echo "書き込み成功！"."<br>";
                    }
                }
            }
            
            #フラグ用フォームが空でない状況で，編集番号が存在するとき        
            elseif(in_array($_POST["hedit_num"],$postnumbers,true)==true){
                #フォーム内の入力確認
                if(isset($_POST["str1"])&&isset($_POST["str2"])&&isset($_POST["pass"])){
                    if($_POST["str1"]!==""&&$_POST["str2"]!==""&&$_POST["pass"]!==""){
                        #フォーム内の文字列を格納
                        $editnum=$_POST["hedit_num"];
                        $str1=$_POST["str1"];
                        $str2=$_POST["str2"];
                        $pass=$_POST["pass"];
                        #投稿時間を変数に格納
                        $when=date("Y/m/d H:i:s");
                        #投稿情報を変数に格納
                        $comment2=$editnum."<>".$str1."<>".$str2."<>".$when."<>".$pass;
                        #投稿をファイルへ書き込み
                        $fp1=fopen($filename,"w+");
                        #配列を1行ずつ取り出す
                        foreach($lines as $line){
                            #"<>"を取り除き新たな配列に格納
                            $li=explode("<>",$line);
                            #lineの0番目の要素が編集番号と同じとき
                            if($editnum==$li[0]){
                                fwrite($fp1,$comment2.PHP_EOL);
                                echo "編集成功！"."<br>";
                            }
                            #lineの0番目の要素が編集番号と異なるとき
                            else fwrite($fp1,$line.PHP_EOL);
                        }
                        fclose($fp1);
                    }
                }
            }
        }
        /*- - - - - - - - - - - - - 削除- - - - - - - - - - - - - */
        #削除ボタンが押されたとき
        if(isset($_POST["submit2"])){
            #削除番号の入力確認
            if(!empty($_POST["delete_num"])){
                #削除番号が存在するとき
                if(in_array($_POST["delete_num"],$postnumbers,true)==true){
                    #パスワードの入力確認
                    if(isset($_POST["delete_pass"])&&$_POST["delete_pass"]!==""){
                        #フォーム内の文字列を格納
                        $dnum=$_POST["delete_num"];
                        $dpass=$_POST["delete_pass"];
                        #テキストファイルの上書き
                        $fp2= fopen($filename,"w+");
                        #配列を1行ずつ取り出す
                        foreach($lines as $line){
                            #"<>"を取り除き新たな配列に格納
                            $li=explode("<>",$line);
                            #lineの0番目の要素が削除番号と同じとき
                            if($dnum==$li[0]){
                                #パスワードが一致するとき
                                if($dpass==$li[4]){
                                    echo "削除成功！"."<br>";
                                }
                                #パスワードが一致しないとき
                                else{
                                    fwrite($fp2,$line.PHP_EOL);
                                    echo "**パスワードが違います2**"."<br>";
                                }
                            }
                            #lineの0番目の要素が削除番号と違うとき
                            else fwrite($fp2,$line.PHP_EOL);
                        }
                        fclose($fp2);
                    }
                }
            }
        }
        
        /*- - - - - - - - - - - - - 表示- - - - - - - - - - - - - */ 
        echo "<br>"." - - - - - - - - - 【投稿一覧】 - - - - - - - - - "."<br>";
        #ファイルが存在する時
        if(file_exists($filename)){
            #ファイルの中身を配列に格納
            $lines=file($filename,FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
            #配列を1行ずつ取り出す
            foreach($lines as $line){
                #"<>"を取り除き新たな配列に格納
                $li=explode("<>",$line);
                #0番目から3番目を表示(4番目のパスワードは表示しない)
                for($i=0;$i<=3;$i++){
                    echo $li[$i]." ";
                }
                echo "<br>";
            }
        }
    ?>

</body>
</html>