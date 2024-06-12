<?php
    session_start();
    
    //데이터베이스 연결
    $servername = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'wp_project_db';
    $db = new mysqli($servername, $username, $password, $dbname) or die("Connection failed:");

    //사용자 정보 읽기
    $user_SID = $_SESSION["user_SID"];
    $query = "SELECT * FROM users WHERE SID = $user_SID";
    $result = mysqli_query($db, $query) or die(mysqli_error($db));
    $user = $result->fetch_assoc();
    $_SESSION["user_balance"] = $user["user_balance"];

    //보유 주식 정보 읽기(*stock_ID 오름차순 필요)
    $query_stock_held = "SELECT * FROM user_held_stocks WHERE user_ID = $user_SID";
    $result_stock_held = mysqli_query($db, $query_stock_held) or die(mysqli_error($db));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HeoHeo Securities-setting</title>
    <link href="WP project_style01.css" rel="stylesheet">
</head>
<body>
    <header>
        <div class="top">
            <img class="logo" src="WP project resources/logo.jpg" alt="HeoHeo logo">
            <div>
                <!--로그인 후에만 세팅 진입 가능 -->
                <div id="after_login" style="display: block;">
                    <p>Welcome!!</p>
                    <a href="WP project_setting.php" id="setting">Setting</a>
                    <!--로그아웃 시 국내주식 페이지로 -->
                    <form method="post" action="WP project_domestic.php">
                        <input type="submit" name="logout" value="Log Out" id="login_or_signup_orout"/>
                    </form>
                </div>
            </div>
        </div>
        <nav>
            <a href="WP project_domestic.php">Domestic Stocks</a>
            <a href="WP project_overseas.php">Overseas Stocks</a>
            <a href="WP project_trend.php">Trend</a>
        </nav>
    </header>

    <article>
        <h2 id="page_name">Setting</h2>
        <?php
            //계좌, 잔액 표시
            echo "<p id=\"my_name\">My Name: " . $user["user_name"] . "</p>";
            echo "<p id=\"my_account\">My Account: " . $user["user_account"] . "</p>";
            echo "<p id=\"my_balance\">My Balance: " . $user["user_balance"] . "</p>";
            echo "<br/>";

            //보유 주식 표시
            echo "<h3 id=\"stocks_held\">Stocks held</h3>";
            echo "<section class=\"held_stocks\" id=\"held_stocks\">";
            while ($row = $result_stock_held->fetch_assoc()) {
                //해당 주식 쿼리
                $query_stock_bySID = "SELECT * FROM stocks WHERE SID = " . $row["stock_ID"];
                $result_stock_bySID = mysqli_query($db, $query_stock_bySID) or die(mysqli_error($db));
                $stock = $result_stock_bySID->fetch_assoc();

                echo "<div class=\"stock_card\">";
                //주식 이름
                echo "<p id=\"stock_name\">" . $stock["stock_name"] . "</p>";
                //현재 가격(가격 상승, 하락에 따라 색 변경)
                echo "<p id=\"stock_current_price\"";
                if($stock["cur_price"] > $stock["pre_price"]){
                    echo "style=\"color: red\"";
                } elseif($stock["cur_price"] < $stock["pre_price"]){
                    echo "style=\"color: blue\"";
                } else{
                    echo "style=\"color: black\"";
                }
                echo ">" . $stock["cur_price"] . "</p>";
                //회사 정보
                echo "<p id=\"stock_information\">" . $stock["corp_info"] . "</p>";
                //소유 개수(보유한 주식들이라서 무조건 존재)(혹시몰라서 각 id 부여)
                echo "<p id=\"held_num_" . $row["SID"] . "\">Number of stocks held: " . $row["held_num"] . "</p>";
                echo "</div>";
            }
            echo "</section>";
        ?>
        <!--정보 수정 -->
        <h3>Modify information</h3>
        <form method="post">
           <input type="text" name="nameToChange" placeholder="Enter the name you want to change" class="modify_info" id="modify_name">
           <button type="submit" name="changeName">Modify</button>
           <br/>
           <input type="text" name="accountToChange" placeholder="Enter the account you want to change" class="modify_info" id="modify_account">
           <button type="submit" name="changeAccount">Modify</button>
        </form>
        <br/>
        <!--입금, 출금 -->
        <h3>Deposit / Withdrawal</h3>
        <form method="post">
           <input type="number" name="moneyToDeposit" placeholder="Please enter the money to be deposited" class="modify_info" id="deposit" min=0> WON
           <button type="submit" name="deposit" class="dep_or_with_button">Deposit</button>
           <br/>
           <input type="number" name="moneyToWithdraw" placeholder="Please enter the money to withdraw" class="modify_info" id="withdrawal" min=0> WON
           <button type="submit" name="withdraw" class="dep_or_with_button">Withdrawal</button>
        </form>
        <br/>
    </article>

    <?php
        //이름변경, 계좌변경, 입금, 출금 처리
        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            // 이름 변경
            if (isset($_POST["changeName"]) && !empty($_POST["nameToChange"])){
                $nameToChange = $_POST["nameToChange"];
                $update_name_query = "UPDATE users SET user_name = \"$nameToChange\" WHERE SID = $user_SID";
                mysqli_query($db, $update_name_query);
                echo "<script>document.getElementById(\"my_name\").innerText = \"My Name: $nameToChange\";</script>";
            }
            // 계좌 변경
            if (isset($_POST["changeAccount"]) && !empty($_POST["accountToChange"])){
                $accountToChange = $_POST["accountToChange"];
                $update_account_query = "UPDATE users SET user_account = \"$accountToChange\" WHERE SID = $user_SID";
                mysqli_query($db, $update_account_query);
                echo "<script>document.getElementById(\"my_account\").innerText = \"My Account: $accountToChange\";</script>";
            }
            // 입금
            if (isset($_POST["deposit"]) && !empty($_POST["moneyToDeposit"])){
                $new_balance = $user["user_balance"] + $_POST["moneyToDeposit"];
                $update_balance_query = "UPDATE users SET user_balance = $new_balance WHERE SID = $user_SID";
                mysqli_query($db, $update_balance_query);
                $_SESSION["user_balance"] = $new_balance;
                echo "<script>document.getElementById(\"my_balance\").innerText = \"My Balance: $new_balance\";</script>";
            }
            // 출금
            if (isset($_POST["withdraw"]) && !empty($_POST["moneyToWithdraw"])){
                if($_SESSION["user_balance"] >= $_POST["moneyToWithdraw"]){
                    $new_balance = $user["user_balance"] - $_POST["moneyToWithdraw"];
                    $update_balance_query = "UPDATE users SET user_balance = $new_balance WHERE SID = $user_SID";
                    mysqli_query($db, $update_balance_query);
                    $_SESSION["user_balance"] = $new_balance;
                    echo "<script>document.getElementById(\"my_balance\").innerText = \"My Balance: $new_balance\";</script>";
                }else{
                    echo "<script>window.alert(\"Insufficient balance\");</script>";
                }
            }
        }
    ?>

    <footer>
        <img class="logo" src="WP project resources/logo.jpg" alt="HeoHeo logo" width="10%" height="10%">
        <div>
            <nav>
                <a href="WP project_domestic.php">Domestic Stocks</a>
                <a href="WP project_overseas.php">Overseas Stocks</a>
                <a href="WP project_trend.php">Trend</a>
            </nav>
        </div>
        <p>Copyright 2024 | HeoSeungWoo 21011798</p>
    </footer>
</body>
</html>
