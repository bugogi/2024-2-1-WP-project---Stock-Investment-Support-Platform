<?php
session_start();

//데이터베이스 연결
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'wp_project_db';
$db = new mysqli($servername, $username, $password, $dbname) or die("Connection failed:");

//회원가입 시도가 있으면(post)
if ($_SERVER["REQUEST_METHOD"] == "POST" and isset($_POST["signup"])) {
    $new_user_ID = $_POST["new_id"];
    $new_user_PW = $_POST["new_password"];
    $new_user_name = $_POST["new_name"];
    $new_user_account = $_POST["new_account"];

    //동일한 아이디 가진 유저 탐색
    $query = "SELECT * FROM users WHERE user_ID = \"$new_user_ID\"";
    $result = mysqli_query($db, $query) or die(mysqli_error($db));
    $already_user = $result->fetch_assoc();
    //동일한 아이디 가진 유저가 없으면 회원가입 성공
    if(!isset($already_user)){
        //새로운 사용자 row 추가
        $query_insert_new_user = "INSERT INTO users (user_name, user_account, user_ID, user_PW) VALUES (\"$new_user_name\", \"$new_user_account\", \"$new_user_ID\", \"$new_user_PW\")";
        mysqli_query($db, $query_insert_new_user);
        //다시 유저 쿼리
        $query = "SELECT * FROM users WHERE user_ID = \"$new_user_ID\"";
        $result = mysqli_query($db, $query) or die(mysqli_error($db));
        $user = $result->fetch_assoc();
        //로그인
        $_SESSION["authuser"] = 1;
        $_SESSION["user_SID"] = $user["SID"];
        echo "<script>window.alert(\"Sign up success\");</script>";
        header("Location: WP project_domestic.php");
    }else{
        echo "<script>window.alert(\"ID not available\");</script>";
    }

    

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>HeoHeo Securities-sign up</title>
    <link href="WP project_style01_pro.css" rel="stylesheet">
</head>
<body>
    <header>
        <div class="top">
            <img class="logo" src="WP project resources/logo.jpg" alt="HeoHeo logo">
        </div>
    </header>

    <article class="signup_page">
        <h1>Sign Up</h1>
        <form method="post" action="">
            <input type="text" placeholder="ID" name="new_id" require>
            <br/>
            <input type="password" placeholder="PASSWORD" name="new_password" require>
            <br/>
            <input type="text" placeholder="Name" name="new_name" require>
            <br/>
            <input type="text" placeholder="Account" name="new_account" require>
            <br/>
            <input type="submit" name="signup" value="Sign Up">
        </form>
        
    </article>

    <footer>
        <img class="logo" src="WP project resources/logo.jpg" alt="HeoHeo logo" width="10%" height="10%">
        <div>
            <nav>
                <a href="WP project_domestic.php">Domestic Stocks</a>
                <a href="WP project_overseas.php">Overseas Stocks</a>
                <a href="WP project_trend.php">Trend</a>
            </nav>
        </div>
        <p>Copyright2024 | HeoSeungWoo 21011798</p>
    </footer>
</body>
</html>