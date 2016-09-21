<?php
    ob_start();
    session_start();
    $user_id = $_SESSION['user_id'];

    if($user_id != ''){
        $permission = $_SESSION['user_permission'];
    }

    $articlePerPage = 8;

    include "connect.php";
    $getArticles = mysqli_query($con, "SELECT * FROM articles") or die("EEEE");
    $articles = mysqli_num_rows($getArticles); 
    $pages = ceil($articles / $articlePerPage);
    echo "<div id='pagination-info' style='display: none;'>".$pages."</div>";
    mysqli_close($con);
?>
    <!DOCTYPE html>
    <html>
        <?php
            include "head.html";
        ?>
        <body>
            <script src="./pagination.js"></script>
            <?php
                include "nav.html";
            ?>
            <div class="c-articlessite">
                <div class="c-jumbotron">
                    <div class="c-jumbo-shad">
                        <div class="c-jumbo-top">
                            <span class="c-jumbo_label-top">
                                Featured article:
                            </span>
                        </div>
                        <div class="c-jumbo-bot">
                            <span class="c-jumbo_label-bot">
                                Not done yet 8)
                            </span>
                        </div>
                    </div>
                </div>
                <ul class="c-articleslist">
                    
                </ul>
                <div class="c-articlespagination">
                    <div class="c-pagination">
                        <a href="#" class="first c-btn">
                            &laquo;
                        </a>
                        <a href="#" class="previous c-btn">
                            &lsaquo;
                        </a>
                        <input type="text" class="pageindex" maxlength="3"/>
                        <a href="#" class="next c-btn">
                            &rsaquo;
                        </a>
                        <a href="#" class="last c-btn">
                            &raquo;
                        </a>
                    </div>
                </div>
            </div>
            <?php
                include "footer.html";
            ?>
        </body>
    </html>
<?php
    ob_end_flush();
?>