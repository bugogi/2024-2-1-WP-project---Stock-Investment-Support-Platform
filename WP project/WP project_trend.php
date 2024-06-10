<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>HeoHeo Securities-trend</title>
        <link href="WP project_style01.css" rel="stylesheet"><!--css 참조-->
        <script>
            function updateHeldNum(stockID, newHeldNum) {
                var heldNumElement = document.getElementById("held_num_" + stockID);
                if (heldNumElement) {
                    heldNumElement.innerText = "Number of stocks held: " + newHeldNum;
                }else{
                    window.alert("Update Fail");
                }
            }
            function updateLike(stockID, isLike, newLikeNum) {
                var isInput = document.getElementById("like_button_" + stockID);
                if (isInput) {
                    if(isLike == 1){
                        document.getElementById("like_button_" + stockID).style.backgroundColor = "pink";
                        document.getElementById("like_button_" + stockID).style.color = "orangered";
                    }else{
                        document.getElementById("like_button_" + stockID).style.backgroundColor = "#F0F0F0";
                        document.getElementById("like_button_" + stockID).style.color = "black";
                    }

                    document.getElementById("like_num_" + stockID).innerText = newLikeNum;
                }else{
                    window.alert("Update Fail");
                }
            }
        </script>
    </head>
    <body>
        <header>
            <div class="top">
                <img class="logo" src="WP project resources\logo.jpg" alt="HeoHeo logo">
                <div>
                    <div id="before_login">
                        <form method="post" action="WP project_login.php">
                            <input type="submit" name="login" value="Login" id="log_in_or_out"/>
                        </form>
                    </div>
                    <div id="after_login">
                        <p>Welcome!!</p>
                        <a href="WP project_setting.php" id="setting">Setting</a>
                        <form method="post" action="WP project_domestic.php">
                            <input type="submit" name="logout" value="Log Out" id="log_in_or_out"/>
                        </form>
                    </div>
                    <?php
                        if($_SESSION['authuser'] == 1){
                            echo "<script>document.getElementById(\"before_login\").style = \"content-visibility: hidden;\";</script>";
                            echo "<script>document.getElementById(\"after_login\").style = \"content-visibility: visible;\";</script>";
                        } else{
                            echo "<script>document.getElementById(\"before_login\").style = \"content-visibility: visible;\";</script>";
                            echo "<script>document.getElementById(\"after_login\").style = \"content-visibility: hidden;\";</script>";
                        }
                    ?>
                </div>
            </div>
            <div>
                <nav>
                    <a href="WP project_domestic.php">Domestic Stocks</a>
                    <a href="WP project_overseas.php">Overseas Stocks</a>
                    <a href="WP project_trend.php" style="text-decoration: underline;">Trend</a>
                </nav>
            </div>
        </header>

        <article>
            <h2 id="page_name">Trend</h2>

            <?php
                $servername = 'localhost';
                $username = 'root';
                $password = '';
                $dbname = 'wp_project_db';
    
                //wp_project_db 연결 / Create connection
                $db = new mysqli($servername, $username, $password, $dbname) or die("Connection failed:");
                //추천 주식 쿼리
                //전문가 ryu
                $query_stock_ryu = "SELECT * FROM stocks WHERE rec_ryu = 1";
                $result_stock_ryu = mysqli_query($db, $query_stock_ryu) or die(mysqli_error($db));
                //전문가 woo
                $query_stock_woo = "SELECT * FROM stocks WHERE rec_woo = 1";
                $result_stock_woo = mysqli_query($db, $query_stock_woo) or die(mysqli_error($db));

                if($_SESSION['authuser'] == 1){
                    //사용자 heo 쿼리(result_user->user_heo)
                    $query_user = "SELECT * FROM users WHERE user_ID = 1";
                    $result_user = mysqli_query($db, $query_user) or die(mysqli_error($db));
                    $user_heo = $result_user->fetch_assoc();
                    $user_balance = $user_heo["user_balance"];

                    //현재자금표시
                    echo "<p id=\"user_balance\">you have now: " . $user_balance . "</p>";
                }
            ?>

            <h3 style="background-color: #CDE8E5; text-align: center;">RYU expert's recommendation</h3>
            <section class="ryu_stocks">
                <?php
                    while($row = $result_stock_ryu->fetch_assoc()) {
                        echo "<div class=\"stock_card\">";
                        echo "<p id=\"stock_name\">" . $row["stock_name"] . "</p>";//주식 이름
                        echo "<p id=\"stock_current_price\"";
                        //가격 상승, 하락에 따라 현재 가격 색 변경
                        if($row["cur_price"] > $row["pre_price"]){
                            echo "style=\"color: red\"";
                        } elseif($row["cur_price"] < $row["pre_price"]){
                            echo "style=\"color: blue\"";
                        } else{
                            echo "style=\"color: black\"";
                        }
                        echo ">" . $row["cur_price"] . "</p>";// 현재 가격
                        echo "<br/>";
                        echo "<p id=\"stock_information\">" . $row["corp_info"] . "</p>";//회사 정보
                        echo "<br/>";
                        //구매, 판매 시 보유 개수 최신화를 위해 개별 id 할당
                        if($_SESSION['authuser'] == 1){
                            echo "<p id=\"held_num_" . $row["stock_ID"] . "\">Number of stocks held: " . $row["held_num_heo"] . "</p>";
                        }

                        echo "<form method=\"post\">";

                        echo "<input type=\"hidden\" name=\"stock_ID\" value=\"" . $row["stock_ID"] . "\">";//숨겨진 input으로 해당 stock_id post
                        //구매, 판매
                        echo "<input type=\"number\" name=\"buynum\" placeholder=\"Number of stocks to buy\" min=\"0\">";
                        echo "<button type=\"submit\" name=\"buy\" >Buy</button>";
                        echo "<br/>";
                        echo "<input type=\"number\" name=\"sellnum\" placeholder=\"Number of stocks to sell\" min=\"0\">";
                        echo "<button type=\"submit\" name=\"sell\">Sell</button>";
                        echo "<br/>";
                        //좋아요
                        echo "<div class=\"like\">";
                        echo "<button type=\"submit\" name=\"like\" class=\"like_button\" id=\"like_button_" . $row["stock_ID"] . "\"";
                        if($row["like_heo"] == 1 and $_SESSION['authuser'] == 1){//로그인 상태에서 좋아요상태면 색상 전환
                            echo "style=\"background-color: pink; color: orangered;\"";
                        }
                        echo ">♥</button>";
                        echo "<p id=\"like_num_" . $row["stock_ID"] . "\">" . $row["like_num"] . "</p>";//좋아요 수
                        echo "</div>";
                        echo "</form>";
                        echo "</div>";
                    }
                ?>
            </section>

            <h3 style="background-color: #CDE8E5; text-align: center;">WOO expert's recommendation</h3>
            <section class="woo_stocks">
                <?php
                    while($row = $result_stock_woo->fetch_assoc()) {
                        echo "<div class=\"stock_card\">";
                        echo "<p id=\"stock_name\">" . $row["stock_name"] . "</p>";
                        echo "<p id=\"stock_current_price\"";
                        //가격 상승, 하락에 따라 현재 가격 색 변경
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
                        //구매, 판매 시 보유 개수 최신화를 위해 개별 id 할당
                        if($_SESSION['authuser'] == 1){
                            echo "<p id=\"held_num_" . $row["stock_ID"] . "\">Number of stocks held: " . $row["held_num_heo"] . "</p>";
                        }

                        echo "<form method=\"post\">";

                        echo "<input type=\"hidden\" name=\"stock_ID\" value=\"" . $row["stock_ID"] . "\">";//숨겨진 input으로 해당 stock_id post
                        //구매, 판매
                        echo "<input type=\"number\" name=\"buynum\" placeholder=\"Number of stocks to buy\" min=\"0\">";
                        echo "<button type=\"submit\" name=\"buy\" >Buy</button>";
                        echo "<br/>";
                        echo "<input type=\"number\" name=\"sellnum\" placeholder=\"Number of stocks to sell\" min=\"0\">";
                        echo "<button type=\"submit\" name=\"sell\">Sell</button>";
                        echo "<br/>";
                        //좋아요
                        echo "<div class=\"like\">";
                        echo "<button type=\"submit\" name=\"like\" class=\"like_button\" id=\"like_button_" . $row["stock_ID"] . "\"";
                        if($row["like_heo"] == 1 and $_SESSION['authuser'] == 1){//로그인 상태에서 좋아요상태면 색상 전환
                            echo "style=\"background-color: pink; color: orangered;\"";
                        }
                        echo ">♥</button>";
                        echo "<p id=\"like_num_" . $row["stock_ID"] . "\">" . $row["like_num"] . "</p>";//좋아요 수
                        echo "</div>";
                        echo "</form>";
                        echo "</div>";
                    }
                ?>
            </section>
            <?php
                //로그인 상태에서 구입 / 판매 요청이 들어오면(로그인POST 제외)
                if ($_SERVER["REQUEST_METHOD"] == "POST" and (!isset($_POST["login"])) and $_SESSION['authuser'] == 1){
                    //히든으로 받은 stock_ID로 쿼리 만들기(result_new_stock->new_stock_by_id)
                    $stock_ID = $_POST["stock_ID"];
                    $new_query = "SELECT cur_price, held_num_heo, like_num, like_heo FROM stocks WHERE stock_ID = $stock_ID";
                    $result_new_stock = mysqli_query($db, $new_query) or die(mysqli_error($db));
                    $new_stock_by_id = $result_new_stock->fetch_assoc();

                    $cur_price = $new_stock_by_id["cur_price"];//현재가격
                    $held_num_heo = $new_stock_by_id["held_num_heo"];//보유수

                    //구매 요청
                    if (isset($_POST["buy"]) and ($_POST["buynum"] != NULL)){
                        $buynum = $_POST["buynum"];//구매량
                        $total_cost = $cur_price * $buynum;//구매비용

                        if($user_balance >= $total_cost){//자금이 충분하면
                            //잔액 업데이트
                            $new_balance = $user_balance - $total_cost;
                            $update_balance_query = "UPDATE users SET user_balance = $new_balance WHERE user_ID = 1";
                            mysqli_query($db, $update_balance_query);

                            // 보유 주식 수 업데이트
                            $new_held_num = $held_num_heo + $buynum;
                            $update_held_query = "UPDATE stocks SET held_num_heo = $new_held_num WHERE stock_ID = $stock_ID";
                            mysqli_query($db, $update_held_query);

                            echo "<script>document.getElementById(\"user_balance\").innerText = \"you have now: $new_balance\";</script>";
                            echo "<script>updateHeldNum(" . $stock_ID . ", " . $new_held_num . ")</script>";
                        }else{
                            echo "<script>window.alert(\"Not enough balance\");</script>";
                        }
                    }
                    //판매 요청
                    if (isset($_POST["sell"]) and ($_POST["sellnum"] != NULL)){
                        $sellnum = $_POST["sellnum"];//판매량
                        $total_price = $cur_price * $sellnum;//판매비용

                        
                        if($held_num_heo >= $sellnum){//소유 수가 충분하면
                            //잔액 업데이트
                            $new_balance = $user_balance + $total_price;
                            $update_balance_query = "UPDATE users SET user_balance = $new_balance WHERE user_ID = 1";
                            mysqli_query($db, $update_balance_query);

                            // 보유 주식 수 업데이트
                            $new_held_num = $held_num_heo - $sellnum;
                            $update_held_query = "UPDATE stocks SET held_num_heo = $new_held_num WHERE stock_ID = $stock_ID";
                            mysqli_query($db, $update_held_query);

                            echo "<script>document.getElementById(\"user_balance\").innerText = \"you have now: $new_balance\";</script>";
                            echo "<script>updateHeldNum(" . $stock_ID . ", " . $new_held_num . ")</script>";
                        }else{
                            echo "<script>window.alert(\"Not enough stocks\");</script>";
                        }
                    }
                    //좋아요 버튼 눌리면
                    if(isset($_POST["like"])){
                        $is_like = $new_stock_by_id["like_heo"];
                        if($is_like == 0){
                            //좋아요 여부 업데이트
                            $update_like_heo_query = "UPDATE stocks SET like_heo = 1 WHERE stock_ID = $stock_ID";
                            mysqli_query($db, $update_like_heo_query);
                            //좋아요 개수 업데이트
                            $new_like_num = $new_stock_by_id["like_num"] + 1;
                            $update_like_num_query = "UPDATE stocks SET like_num = $new_like_num WHERE stock_ID = $stock_ID";
                            mysqli_query($db, $update_like_num_query);

                            echo "<script>updateLike(" . $stock_ID . ", " . 1 . ", " . $new_like_num . ");</script>";
                        }else{
                            //좋아요 여부 업데이트
                            $update_like_heo_query = "UPDATE stocks SET like_heo = 0 WHERE stock_ID = $stock_ID";
                            mysqli_query($db, $update_like_heo_query);
                            //좋아요 개수 업데이트
                            $new_like_num = $new_stock_by_id["like_num"] - 1;
                            $update_like_num_query = "UPDATE stocks SET like_num = $new_like_num WHERE stock_ID = $stock_ID";
                            mysqli_query($db, $update_like_num_query);

                            echo "<script>updateLike(" . $stock_ID . ", " . 0 . ", " . $new_like_num . ");</script>";
                        }
                    }
                    
                }
            ?>

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