<?php

    ob_flush();
    session_start();

    $user_id = $_SESSION['user_id'];
    $articleId = $_GET['id'];
?>

<!DOCTYPE html>
<html>
    <head>
        <?php
            include 'head.html';
        ?>
    </head>
    <body>
        <?php
            include 'nav.html';
        ?>

        <div class="c-articlesite">
            <div class="c-read-box">
                <?php
                    if($articleId != ''){
                        include 'connect.php';
                        $getArticle = mysqli_query($con, "SELECT title, author, date, text, img
                                                        FROM articles
                                                        WHERE id = '$articleId'
                                                        ") or die("Couldn't fetch the article!");
                        
                        if(mysqli_num_rows($getArticle) > 0){
                            $article = mysqli_fetch_assoc($getArticle);
                            $title = $article['title'];

                            $rawAuthor = $article['author'];
                            $getAuthor = mysqli_query($con, "SELECT username
                                                    FROM users
                                                    WHERE id='$rawAuthor'
                                                    ");
                            $authorResult = mysqli_fetch_assoc($getAuthor);
                            $author = $authorResult['username'];

                            $date = $article['date'];
                            $content = $article['text'];
                            $img = $article['img'];

                            if($img == ''){
                                $img = "defaultArticle.png";
                            }

                            echo "
                                <div class='c-read-article'>
                                    <div class='c-read-top' style='background: url(./images/articles/".$img.") no-repeat center center; background-size: 100%;'>
                                        <div class='c-read-title'>
                                            <span class='c-read-label_big'>".$title."</span>
                                        </div>
                                    </div>
                                    <div class='c-read-center'>
                                        <span class='c-read-content'>".$content."</span>
                                    </div>
                                    <div class='c-read-bottom'>
                                        <span class='c-read-author'><strong>Posted by ".$author." on ".$date."</strong>
                                    </div>
                                </div>
                            ";
                        }
                        else{
                            $error_msg = "There's no article with provided ID!";
                        }
                    }
                    else{
                        $error_msg = "You need to provide article's ID in order to see it!";
                    }

                    if(@$error_msg){
                        echo "
                            <div class='c-read-noarticle'>
                                <span class='c-read-errorlabel_big'>".$error_msg."</span>
                            </div>
                        ";
                    }
                ?>
            </div>
        </div>
        <?php
            include 'footer.html';
            ob_end_flush();
        ?>
    <body>
</html>