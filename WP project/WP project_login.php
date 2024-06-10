<?php
session_unset();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>HeoHeo Securities-login</title>
        <link href="WP project_style01.css" rel="stylesheet"><!--css 참조-->
    </head>
    <body>
        <header>
            <div class="top">
                <img class="logo" src="WP project resources\logo.jpg" alt="HeoHeo logo">
            </div>
            
        </header>

        <article class="login_page">
            <h1>Login</h1>
            <!--세션에 $_POST['id'], $_POST['password'] 받음-->
            <form method="post" action="WP project_domestic.php">
                <input type="text" placeholder="ID" name="id">
                <br/>
                <input type="password" placeholder="PASSWORD" name="password">
                <br/>
                <input type="hidden" name="source" value="loginpage">
                <input type="submit" name="login" value="Login" />
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