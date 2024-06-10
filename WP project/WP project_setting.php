<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>HeoHeo Securities-setting</title>
        <link href="WP project_style01.css" rel="stylesheet"><!--css 참조-->
        <script>

        </script>
    </head>
    <body>
        <header>
            <div class="top">
                <img class="logo" src="WP project resources\logo.jpg" alt="HeoHeo logo">
                <div>
                    <!--<div id="before_login">
                        <button class="button">Login</button>
                        <button class="button">Sign In</button>
                    </div>로그인 후에만 setting 입장 가능-->
                    <div id="after_login" style="content-visibility: visible;">
                        <p>Welcome!!</p>
                        <a href="WP project_setting.php" id="setting">Setting</a>
                        <form method="post" action="WP project_domestic.php">
                            <input type="submit" name="logout" value="Log Out" id="log_in_or_out"/>
                        </form>
                    </div>
                </div>
            </div>
            <div>
                <nav><!--네비게이션 국내, 해외, 트렌드-->
                    <a href="WP project_domestic.php">Domestic Stocks</a>
                    <a href="WP project_overseas.php">Overseas Stocks</a>
                    <a href="WP project_trend.php">Trend</a>
                </nav>
            </div>
        </header>

        <article>
            <?php
                $servername = 'localhost';
                $username = 'root';
                $password = '';
                $dbname = 'wp_project_db';
    
                //wp_project_db 연결 / Create connection
                $db = new mysqli($servername, $username, $password, $dbname) or die("Connection failed:");
                
                //소유 주식 쿼리
                $query_stock = "SELECT * FROM stocks WHERE held_num_heo > 0";
                $result_stock = mysqli_query($db, $query_stock) or die(mysqli_error($db));

                //유저 heo 쿼리
                $query_user = "SELECT * FROM users WHERE user_ID = 1";
                $result_user = mysqli_query($db, $query_user) or die(mysqli_error($db));
                $user_heo = $result_user->fetch_assoc();
            ?>

            <h2 id="page_name">Setting</h2>
            <?php
                //계좌, 잔액 표시
                echo "<p id=\"my_name\">My Name: " . $user_heo["user_name"] . "</p>";
                echo "<p id=\"my_account\">My Account: " . $user_heo["user_account"] . "</p>";
                echo "<p id=\"my_balance\">My Balance: " . $user_heo["user_balance"] . "</p>";
                echo "<br/>";
                echo "<h3 id=\"stocks_held\">Stocks held</h3>";
                //보유주 표시
                echo "<section class\"held_stocks\" id=\"held_stocks\">";
                while($row = $result_stock->fetch_assoc()) {
                    echo "<div class=\"stock_card\">";
                    echo "<p id=\"stock_name\">" . $row["stock_name"] . "</p>";
                    echo "<p id=\"stock_current_price\"";
                    if($row["cur_price"] > $row["pre_price"]){
                        echo "style=\"color: red\"";
                    } elseif($row["cur_price"] < $row["pre_price"]){
                        echo "style=\"color: blue\"";
                    } else{
                        echo "style=\"color: black\"";
                    }
                    echo ">" . $row["cur_price"] . "</p>";
                    echo "<br/>";
                    echo "<p id=\"stock_information\">" . $row["corp_info"] . "</p>";
                    echo "<br/>";
                    if($_SESSION['authuser'] == 1){
                        echo "<p id=\"held_num_" . $row["stock_ID"] . "\">Number of stocks held: " . $row["held_num_heo"] . "</p>";
                    }
                    echo "</div>";
                }
                echo "</section>";
            ?>
            <!--정보 수정-->
            <h3>Modify information</h3>
            <form method="post">
               <input type="text" name="nameToChange" placeholder="Enter the name you want to change" class="modify_info" id="modify_name">
               <button type="submit" name="changeName">Modify</button>
               <br/>
               <input type="text" name="accountToChange" placeholder="Enter the account you want to change" class="modify_info" id="modify_account">
               <button type="submit" name="changeAccount">Modify</button>
            </form>
            <br/>
            <!--입금, 출금-->
            <h3>Deposit / Withdrawal</h3>
            <form method="post">
               <input type="number" name="moneyToDeposit" placeholder="Please enter the money to be deposited" class="modify_info" id="deposit">WON
               <button type="submit" name="deposit" class="dep_or_with_button">Deposit</button>
               <br/>
               <input type="number" name="moneyToWithdraw" placeholder="Please enter the money to withdraw" class="modify_info" id="withdrawal">WON
               <button type="submit" name="withdraw" class="dep_or_with_button">Withdrawal</button>
            </form>
            <br/>
        </article>

        <?php
            //이름변경, 계좌변경, 입금, 출금
            if ($_SERVER["REQUEST_METHOD"] == "POST" and (!isset($_POST["login"])) and $_SESSION['authuser'] == 1){
                //이름 변경
                if (isset($_POST["changeName"]) and ($_POST["nameToChange"] != NULL)){
                    $nameToChange = $_POST["nameToChange"];

                    $update_name_query = "UPDATE users SET user_name = '$nameToChange' WHERE user_ID = 1";
                    mysqli_query($db, $update_name_query);

                    echo "<script>document.getElementById(\"my_name\").innerText = \"My Name: $nameToChange\";</script>";
                    
                }
                //계좌 변경
                if (isset($_POST["changeAccount"]) and ($_POST["accountToChange"] != NULL)){
                    $accountToChange = $_POST["accountToChange"];

                    $update_account_query = "UPDATE users SET user_account = '$accountToChange' WHERE user_ID = 1";
                    mysqli_query($db, $update_account_query);

                    echo "<script>document.getElementById(\"my_account\").innerText = \"My Account: $accountToChange\";</script>";
                    
                }
                //입금
                if (isset($_POST["deposit"]) and ($_POST["moneyToDeposit"] != NULL)){
                    $new_balance = $user_heo["user_balance"] + $_POST["moneyToDeposit"];

                    $update_balance_query = "UPDATE users SET user_balance = $new_balance WHERE user_ID = 1";
                    mysqli_query($db, $update_balance_query);

                    echo "<script>document.getElementById(\"my_balance\").innerText = \"My Balance: $new_balance\";</script>";
                    
                }
                //출금
                if (isset($_POST["withdraw"]) and ($_POST["moneyToWithdraw"] != NULL)){
                    $new_balance = $user_heo["user_balance"] - $_POST["moneyToWithdraw"];

                    $update_balance_query = "UPDATE users SET user_balance = $new_balance WHERE user_ID = 1";
                    mysqli_query($db, $update_balance_query);

                    echo "<script>document.getElementById(\"my_balance\").innerText = \"My Balance: $new_balance\";</script>";
                }
                
            }
        ?>

        <footer>
            <img class="logo" src="WP project resources/logo.jpg" alt="HeoHeo logo" width="10%" height="10%">
            <div>
                <nav>
                    <a href="">Domestic Stocks</a>
                    <a href="">Overseas Stocks</a>
                    <a href="">Trend</a>
                </nav>
            </div>
            <p>Copyright2024 | HeoSeungWoo 21011798</p>
        </footer>
    </body>
</html>