<?php
session_start();

//데이터베이스 연결
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'wp_project_db';
$db = new mysqli($servername, $username, $password, $dbname) or die("Connection failed:");

//로그인 시도가 있으면(post)
if ($_SERVER["REQUEST_METHOD"] == "POST" and isset($_POST["login"])) {
    $user_ID = $_POST["id"];
    $user_PW = $_POST["password"];

    //ID가 일치하는 사용자 쿼리 생성
    $query = "SELECT * FROM users WHERE user_ID = \"$user_ID\"";
    $result = mysqli_query($db, $query) or die(mysqli_error($db));
    $user = $result->fetch_assoc();

    //로그인 성공 or 실패
    if ($user && $user_PW === $user["user_PW"]) {
        $_SESSION["authuser"] = 1;
        $_SESSION["user_SID"] = $user["SID"];
        echo "<script>window.alert(\"Login success\");</script>";
        header("Location: WP project_domestic.php");
    } else {
        echo "<script>window.alert(\"Invalid ID or password\");</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>HeoHeo Securities-login</title>
    <link href="WP project_style01.css" rel="stylesheet">
</head>
<body>
    <header>
        <div class="top">
            <img class="logo" src="WP project resources/logo.jpg" alt="HeoHeo logo">
        </div>
    </header>

    <article class="login_page">
        <h1>Login</h1>
        <form method="post" action="">
            <input type="text" placeholder="ID" name="id" required>
            <br/>
            <input type="password" placeholder="PASSWORD" name="password" required>
            <br/>
            <input type="submit" name="login" value="Login">
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
