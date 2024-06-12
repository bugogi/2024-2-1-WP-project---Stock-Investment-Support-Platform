<?php
session_start();

//데이터베이스 연결
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'wp_project_db';
$db = new mysqli($servername, $username, $password, $dbname) or die("Connection failed:");


//입장방식 - 처음: 게스트 로그인: auth1되서옴, 세션(user_SID있음)
//처음입장시 게스트, 로그인 시 해당 유저 쿼리
if(!isset($_SESSION["authuser"])){
    $_SESSION["authuser"] = 2;
}elseif($_SESSION["authuser"] == 1){
    $user_SID = $_SESSION["user_SID"];
    $query = "SELECT * FROM users WHERE SID = $user_SID";
    $result = mysqli_query($db, $query) or die(mysqli_error($db));
    $user = $result->fetch_assoc();
    $_SESSION["user_balance"] = $user["user_balance"];
}
//로그아웃 시
if (isset($_POST["logout"])) {
    $_SESSION["authuser"] = 2;
}



?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>HeoHeo Securities-domestic</title>
        <link href="WP project_style01.css" rel="stylesheet">
        <script>
            //보유 주식 수 최신화 함수
            function updateHeldNum(stockID, newHeldNum) {
                var heldNumElement = document.getElementById("held_num_" + stockID);
                if (heldNumElement) {
                    heldNumElement.innerText = "Number of stocks held: " + newHeldNum;
                } else {
                    window.alert("Update Fail");
                }
            }

            //좋아요 상태 최신화 함수
            function updateLike(stockID, isLike, newLikeNum) {//해당 주식 ID, 좋아요인지 취소인지, 새로운 개수
                var isInput = document.getElementById("like_button_" + stockID);
                if (isInput) {
                    //스타일 변화
                    if (isLike == 1) {
                        document.getElementById("like_button_" + stockID).style.backgroundColor = "pink";
                        document.getElementById("like_button_" + stockID).style.color = "orangered";
                    } else {
                        document.getElementById("like_button_" + stockID).style.backgroundColor = "#F0F0F0";
                        document.getElementById("like_button_" + stockID).style.color = "black";
                    }
                    //개수 변화
                    document.getElementById("like_num_" + stockID).innerText = newLikeNum;
                } else {
                    window.alert("Update Fail");
                }
            }
        </script>
    </head>
<body>
    <header>
        <div class="top">
            <img class="logo" src="WP project resources/logo.jpg" alt="HeoHeo logo">
            <div>
                <!--로그인 전(로그인, 회원가입)-->
                <div id="before_login" style="display: none;">
                    <form method="post" action="WP project_login.php">
                        <input type="submit" name="" value="Login" id="login_or_signup_orout"/>
                    </form>
                    <form method="post" action="WP project_signup.php">
                        <input type="submit" name="" value="Sign Up" id="login_or_signup_orout"/>
                    </form>
                </div>
                <!--로그인 후(문구, 세팅, 로그아웃)-->
                <div id="after_login" style="display: none;">
                    <p>Welcome!!</p>
                    <a href="WP project_setting.php" id="setting">Setting</a>
                    <form method="post" action="">
                        <input type="submit" name="logout" value="Log Out" id="login_or_signup_orout"/>
                    </form>
                </div>
                <!--로그인 여부에 따라 표시-->
                <?php
                if($_SESSION["authuser"] == 1){
                    echo "<script>document.getElementById(\"before_login\").style.display = \"none\";";
                    echo "document.getElementById(\"after_login\").style.display = \"block\";</script>";
                }else{
                    echo "<script>document.getElementById(\"before_login\").style.display = \"block\";";
                    echo "document.getElementById(\"after_login\").style.display = \"none\";</script>";
                }
                ?>
            </div>
        </div>
        <nav>
            <a href="WP project_domestic.php">Domestic Stocks</a>
            <a href="WP project_overseas.php" style="text-decoration: underline;">Overseas Stocks</a>
            <a href="WP project_trend.php">Trend</a>
        </nav>
    </header>

    <article>
        <h2 id="page_name">Overseas Stocks</h2>
        <?php
        //국내 주식 쿼리 생성
        $query_stock = "SELECT * FROM stocks WHERE dom_or_over = 1";
        $result_stock = mysqli_query($db, $query_stock);
        //(로그인 시)현재 잔액
        if ($_SESSION["authuser"] == 1) {
            echo "<p id=\"user_balance\">You have now: " . $_SESSION["user_balance"] . "</p>";
        }
        ?>




        <section class="stocks">
            <?php
            //각 국내 주식
            while ($row = $result_stock->fetch_assoc()) {
                echo "<div class=\"stock_card\">";
                //주식 이름
                echo "<p id=\"stock_name\">" . $row["stock_name"] . "</p>";
                //현재 가격(가격 상승, 하락에 따라 색 변경)
                echo "<p id=\"stock_current_price\"";
                if($row["cur_price"] > $row["pre_price"]){
                    echo "style=\"color: red\"";
                } elseif($row["cur_price"] < $row["pre_price"]){
                    echo "style=\"color: blue\"";
                } else{
                    echo "style=\"color: black\"";
                }
                echo ">" . $row["cur_price"] . "</p>";
                //회사 정보
                echo "<p id=\"stock_information\">" . $row["corp_info"] . "</p>";
                //(로그인 시)소유 개수
                if ($_SESSION["authuser"] == 1) {
                    //소유 쿼리 생성
                    $query_stock_held = "SELECT * FROM user_held_stocks WHERE stock_Id = {$row["SID"]} and user_ID = $user_SID";
                    $result_stock_held = mysqli_query($db, $query_stock_held);
                    $stock_held = $result_stock_held->fetch_assoc();
                    if(isset($stock_held)){
                        $held_num = $stock_held["held_num"];
                    }else{
                        $held_num = 0;
                    }
                    echo "<p id=\"held_num_" . $row["SID"] . "\">Number of stocks held: " . $held_num . "</p>";
                }
                //구매, 판매, 좋아요
                echo "<form method=\"post\">";
                echo "<input type=\"hidden\" name=\"stock_ID\" value=\"" . $row["SID"] . "\">";//hidden으로 SID(stock) 전달
                echo "<input type=\"number\" name=\"buynum\" placeholder=\"Number of stocks to buy\" min=\"0\">";//buynum 전달
                echo "<button type=\"submit\" name=\"buy\">Buy</button>";//buy신호 전달
                echo "<br/>";
                echo "<input type=\"number\" name=\"sellnum\" placeholder=\"Number of stocks to sell\" min=\"0\">";//sellnum 전달
                echo "<button type=\"submit\" name=\"sell\">Sell</button>";//sell신호 전달
                echo "<br/>";
                echo "<div class=\"like\">";
                //로그인 상태에서 좋아요상태면 색상 전환
                echo "<button type=\"submit\" name=\"like\" class=\"like_button\" id=\"like_button_" . $row["SID"] . "\"";
                if ($_SESSION["authuser"] == 1){
                    //좋아요 쿼리 추가
                    $query_user_like = "SELECT * FROM user_like WHERE stock_Id = " . $row["SID"] . " AND user_ID = " . $user_SID;
                    $result_user_like = mysqli_query($db, $query_user_like);
                    $user_like = $result_user_like->fetch_assoc();
                    //존재하지 않을 시(좋아요상태x)
                    if(!isset($user_like)){
                        echo "style=\"background-color: #F0F0F0; color: black;\"";
                    }else{//존재할 시(좋아요 상태)
                        echo "style=\"background-color: pink; color: orangered;\"";
                    }
                }
                echo ">♥</button>";
                echo "<p id=\"like_num_" . $row["SID"] . "\">" . $row["like_num"] . "</p>";
                echo "</div>";
                echo "</form>";
                echo "</div>";
            }

            //로그인 제외, 로그인상태에서 post 들어왔을 시
            if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST["login"]) && $_SESSION["authuser"] == 1) {
                //해당 주식 쿼리
                $stock_ID = $_POST["stock_ID"];
                $query = "SELECT * FROM stocks WHERE SID = $stock_ID";
                $result = mysqli_query($db, $query);
                $stock = $result->fetch_assoc();

                $cur_price = $stock["cur_price"];
                $like_num = $stock["like_num"];
                
                //구매
                if (isset($_POST["buy"]) && !empty($_POST["buynum"])) {
                    $buynum = $_POST["buynum"];
                    $total_cost = $cur_price * $buynum;
                    //구매 성공
                    if ($_SESSION["user_balance"] >= $total_cost) {
                        $_SESSION["user_balance"] -= $total_cost;
                        //사용자가 보유한 주식 수 업데이트
                        //보유 쿼리 생성
                        $query_stock_held = "SELECT * FROM user_held_stocks WHERE stock_Id = $stock_ID and user_ID = $user_SID";
                        $result_stock_held = mysqli_query($db, $query_stock_held);
                        $stock_held = $result_stock_held->fetch_assoc();
                        //기존에 보유하지 않았다면
                        if(!isset($stock_held)){
                            //새 row 추가!!
                            $query_insert_new_held = "INSERT INTO user_held_stocks (stock_Id, user_ID, held_num) VALUES ($stock_ID, $user_SID, 0)";
                            mysqli_query($db, $query_insert_new_held);
                            //다시 보유 쿼리 생성
                            $query_stock_held = "SELECT * FROM user_held_stocks WHERE stock_Id = $stock_ID and user_ID = $user_SID";
                            $result_stock_held = mysqli_query($db, $query_stock_held);
                            $stock_held = $result_stock_held->fetch_assoc();
                        }
                        $held_num = $stock_held["held_num"];
                        $held_num += $buynum;
                        $query_update_held = "UPDATE user_held_stocks SET held_num = $held_num WHERE stock_ID = $stock_ID AND user_ID = $user_SID";
                        mysqli_query($db, $query_update_held);
                        
                        //사용자의 잔액 업데이트
                        $query_update_balance = "UPDATE users SET user_balance = " . $_SESSION["user_balance"] . " WHERE SID = " . $_SESSION["user_SID"];
                        mysqli_query($db, $query_update_balance);

                        //성공 메시지와 화면 업데이트
                        echo "<script>document.getElementById(\"user_balance\").innerText = \"You have now: " . $_SESSION["user_balance"] . "\";</script>";
                        echo "<script>updateHeldNum($stock_ID, $held_num);</script>";
                    } else {
                        //잔액이 부족한 경우 경고 표시
                        echo "<script>alert(\"Not enough balance\");</script>";
                    }
                }
                //판매
                if (isset($_POST["sell"]) && !empty($_POST["sellnum"])) {
                    $sellnum = $_POST["sellnum"];
                    $total_cost = $cur_price * $sellnum;
                    //보유 쿼리 생성
                    $query_stock_held = "SELECT * FROM user_held_stocks WHERE stock_Id = $stock_ID and user_ID = $user_SID";
                    $result_stock_held = mysqli_query($db, $query_stock_held);
                    $stock_held = $result_stock_held->fetch_assoc();
                    $held_num = $stock_held["held_num"];
                    //판매 성공(소지 수 >= 판매 수)
                    if ($held_num >= $sellnum) {
                        $_SESSION["user_balance"] += $total_cost;
                        $held_num -= $sellnum;
                        //사용자가 보유한 주식 수 업데이트
                        //보유 개수가 0이 되면
                        if($held_num == 0){
                            //해당 row 제거
                            $query_delete_held = "DELETE FROM user_held_stocks WHERE stock_ID = $stock_ID AND user_ID = $user_SID";
                            mysqli_query($db, $query_delete_held);
                        }else{
                            $query_update_held = "UPDATE user_held_stocks SET held_num = $held_num WHERE stock_ID = $stock_ID AND user_ID = $user_SID";
                            mysqli_query($db, $query_update_held);
                        }
                        
                        //사용자의 잔액 업데이트
                        $query_update_balance = "UPDATE users SET user_balance = " . $_SESSION["user_balance"] . " WHERE SID = " . $_SESSION["user_SID"];
                        mysqli_query($db, $query_update_balance);

                        //성공 메시지와 화면 업데이트
                        echo "<script>document.getElementById(\"user_balance\").innerText = \"You have now: " . $_SESSION["user_balance"] . "\";</script>";
                        echo "<script>updateHeldNum($stock_ID, $held_num);</script>";
                    } else {
                        //보유 개수가 부족한 경우 경고 표시
                        echo "<script>alert(\"Not enough stocks\");</script>";
                    }
                }
                //좋아요
                if (isset($_POST["like"])) {
                    //좋아요 쿼리 생성
                    $query_user_like = "SELECT * FROM user_like WHERE stock_Id = $stock_ID and user_ID = $user_SID";
                    $result_user_like = mysqli_query($db, $query_user_like);
                    $user_like = $result_user_like->fetch_assoc();
                    //존재하지 않을 시(좋아요상태x)->생성
                    if(!isset($user_like)){
                        $query_insert_new_user_like = "INSERT INTO user_like (stock_Id, user_ID) VALUES ($stock_ID, $user_SID)";
                        mysqli_query($db, $query_insert_new_user_like);
                        $is_like = 1;
                        $like_num++;
                    }else{//존재할 시(좋아요 상태)->삭제
                        $query_delete_user_like = "DELETE FROM user_like WHERE stock_ID = $stock_ID AND user_ID = $user_SID";
                        mysqli_query($db, $query_delete_user_like);
                        $is_like = 0;
                        $like_num--;
                    }
                    //좋아요 수 최신화
                    $query = "UPDATE stocks SET like_num = $like_num WHERE SID = $stock_ID";
                    mysqli_query($db, $query);
                    //좋아요 스타일 최신화
                    echo "<script>updateLike($stock_ID, $is_like, $like_num);</script>";
                }
            }
            ?>
        </section>
    </article>

    <footer>
        <img class="logo" src="WP project resources/logo.jpg" alt="HeoHeo logo" width="10%" height="10%">
        <nav>
            <a href="WP project_domestic.php">Domestic Stocks</a>
            <a href="WP project_overseas.php">Overseas Stocks</a>
            <a href="WP project_trend.php">Trend</a>
        </nav>
        <p>Copyright 2024 | HeoSeungWoo 21011798</p>
    </footer>
</body>
</html>
