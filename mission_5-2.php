<!DOCTYPE>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>mission_5-2</title>
    </head>
    <body>
        <?php
            date_default_timezone_set("asia/tokyo");
            //
            $name = filter_input(INPUT_POST,"name");
            $comment = filter_input(INPUT_POST,"comment");
            $delete = filter_input(INPUT_POST,"deleteNo");
            $edit = filter_input(INPUT_POST,"editNo");
            $eform = filter_input(INPUT_POST,"eform");
            $pass = filter_input(INPUT_POST,"passward");
            //DB接続
            $dsn = 'データベース名';
            $user = 'ユーザー名';
            $password = 'パスワード';
            $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
            //create
            $sql = "CREATE TABLE IF NOT EXISTS tbtest_501"
            ." ("
            . "id INT AUTO_INCREMENT PRIMARY KEY,"
            . "name char(32),"
            . "comment TEXT,"
            . "date DATETIME,"
            . "pass varchar(32)"
            .");";
            $stmt = $pdo->query($sql);
            //show tables
            $sql ='SHOW TABLES';
            $result = $pdo -> query($sql);
            foreach ($result as $row){
                echo $row[0];
                echo '<br>';
            }
            echo "<hr>";
            if(isset($_POST["submit"]) && !empty($name && $comment)){
                if(empty($eform)){
                    $sql = $pdo -> prepare("INSERT INTO tbtest_501 (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
                    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                    $sql -> bindParam(':date', $date, PDO::PARAM_STR);
                    $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
                    $name = $_POST["name"];
                    $comment = $_POST["comment"];
                    $date = date("Y/m/d H:i:s");
                    $pass = $_POST["pass"];
                    $sql -> execute();
                }elseif(!empty($eform)){
                    $id = $eform; //変更する投稿番号
                    $name = $_POST["name"];
                    $comment = $_POST["comment"];
                    $pass = $_POST["pass"];
                    $sql = 'UPDATE tbtest_501 SET name=:name,comment=:comment,pass=:pass WHERE id=:id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                    $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                }
            }elseif(isset($_POST["dsubmit"])  && isset($_POST["deletepass"]) && !empty($delete)){
                $delete = $_POST["deleteNo"];
                $deletepass = $_POST["deletepass"];
                //
                $id = $delete;
                $sql = 'SELECT * FROM tbtest_501 WHERE id=:id ';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                    $results = $stmt->fetchAll();
                    foreach ($results as $row){
                        if($row['pass'] == $deletepass && !empty($row['pass'])){
                        echo $row['id'].',';
                        echo $row['name'].',';
                        echo $row['comment'].',';
                        echo $row['date'].'<br>';
                    echo "<hr>";
                    echo "を削除しました";
                        //
                    $id = $delete;
                    $sql = 'delete from tbtest_501 where id=:id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                    }
                    }
            }elseif(isset($_POST["esubmit"]) && isset($_POST["editpass"]) && !empty($edit)){
                $edit = $_POST["editNo"];
                $editpass = $_POST["editpass"];
                //
                $id = $edit;
                $sql = 'SELECT * FROM tbtest_501 WHERE id=:id ';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                    $results = $stmt->fetchAll();
                    foreach ($results as $row){
                        if($row['pass'] == $editpass && !empty($row['pass'])){
                        echo $row['id'].',';
                        echo $row['name'].',';
                        echo $row['comment'].',';
                        echo $row['date'].'<br>';
                    echo "<hr>";
                    echo "を編集します";
                    $e1 = $row['name'];
                    $e2 = $row['pass'];
                    $e3 = $row['comment'];
                    }
                    }
            }
        ?>
        <h3>コメントを投稿する</h3>
        <form method="POST">
        <!--投稿フォーム-->    
            <input type="name" name="name" size="15px" placeholder="名前" value="<?php if (isset($e1)) {echo $e1;} else {echo "太郎";}?>"><br>
            <input type="text" name="pass" size="15px" placeholder="パスワード" value="<?php if (isset($e2)) {echo $e2;} ?>"><br>
            <input type="text" name="comment" size="40px" placeholder="コメントを入力して下さい。" value="<?php if (isset($e3)) {echo $e3;} ?>">
            <input type="hidden" name="eform" value="<?php if (!empty($_POST["editNo"])) {echo $_POST["editNo"];} else {echo "";} ?>">
            <input type="submit" name="submit"><br>
        <!--投稿削除フォーム-->
            <input type="number" name="deleteNo" placeholder="削除番号指定">
            <input type="text" name="deletepass" placeholder="パスワード">
            <input type="submit" name="dsubmit" value="削除"><br>
        <!--投稿編集フォーム-->
            <input type="number" name="editNo" placeholder="編集番号指定">
            <input type="text" name="editpass" placeholder="パスワード">
            <input type="submit" name="esubmit" value="編集"><br>
        </form>
        <small>※　パスワード欄を入力せずに投稿すると削除・編集が出来ません。</small>
        <hr>
        <h3>掲示板</h3>
        <?php
            $sql = 'SELECT * FROM tbtest_501';
                    $stmt = $pdo->query($sql);
                    $results = $stmt->fetchAll();
                    foreach ($results as $row){
                    //$rowの中にはテーブルのカラム名が入る
                        echo $row['id'].',';
                        echo $row['name'].',';
                        echo $row['comment'].',';
                        echo $row['date'].'<br>';
                    echo "<hr>";
                    }
        ?>
    </body>
</html>