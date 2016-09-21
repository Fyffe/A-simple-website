<?php
    ob_start();
    session_start();
    $user_id = $_SESSION['user_id'];
    $global_msg = $_SESSION['global_msg'];

    function elo(){
        echo "EOOO";
    }
?>

<!DOCTYPE html>
<html>
    <?php
        include 'head.html';
    ?>
    <body>
        <script>
            $(document).ready(function(){
                if($('.c-messagebox').is(':visible')){
                    setTimeout(function(){ disableMessage(); }, 3000);
                }

                $('.c-addbox-form').validate({
                    rules: {
                        title: {
                            required: true,
                        },
                        content: {
                            required: true,
                        }
                    },
                    messages: {
                        title: {
                            required: "Your article requires a title!"
                        },
                        content: {
                            required: "Article can't be empty :("
                        }
                    }
                });
            });

            function showLogin(){
                $('.c-userpanel-list').fadeOut("fast", function(){
                    $('.c-login').fadeIn("fast");
                });
            };

            function disableMessage(){
                $('.c-messagebox').fadeOut("slow");         
            };

            function createArticle(){
                $('.c-addbox-dismiss').fadeIn("fast", function(){
                     $('.c-addbox-dismiss').css("display", "flex");
                });
                $('.c-addbox-content').fadeIn("fast", function(){
                    $('.c-addbox-content').css("display", "block");
                });
            };

            function turnOffAddbox(){
                $('.c-addbox-dismiss').fadeOut("fast");
                $('.c-addbox-content').fadeOut("fast");
            };

            function confirm_reset() {
                document.getElementById('img-prev').src = "";
                return confirm("Are you sure you want to reset the form?");
            };

            var loadFile = function(event) {
                var output = document.getElementById('img-prev');
                if(event.target.files[0] != null){
                    output.src = URL.createObjectURL(event.target.files[0]);
                }
                else {
                    output.src = "";
                }
            };
        </script>
        <?php
            include 'nav.html';
        ?>
        <?php 
            if($global_msg != ''){
                echo"
                    <div class='c-messagebox'>
                        <span class='c-message'>".$global_msg."</span>
                        <a href='#' class='c-message-disable' onclick='disableMessage()'><b>x</b></a>
                    </div>
                ";

                $_SESSION['global_msg'] = '';
            }
        ?>
        <div class="c-site">
            <div class="c-addbox-dismiss" onclick="turnOffAddbox()">
            </div>
            <div class="c-addbox-content">
                <div class="c-addbox-top">
                    <span class="c-addbox-cancel" onclick="turnOffAddbox()"><b>x</b></span>
                    <span class="c-articlesbox-header">Add new article!</span>
                </div>
                <div class="c-addbox-middle">
                    <div class="c-addbox-inner">
                        <form action="addarticle.php" method="POST" enctype="multipart/form-data" role="form" class="c-addbox-form">
                            <div class="c-addbox-form_row">
                                <input type="text" class="c-addbox-form_textfield" name="title" placeholder="Title" />
                            </div>
                            <div class="c-addbox-form_row">
                                <textarea class="c-addbox-form_textarea" name="content" placeholder="Content (better text editor is available after adding the article)"></textarea>
                            </div>
                            <div class="c-addbox-form_row">
                                <div class="fileUpload c-btn">
                                    <span>Upload</span>
                                    <input type="file" class="c-upload" accept="image/*" id="inputfile" onchange="loadFile(event)" name="bgimg"/>
                                </div>
                            </div>
                            <div class="c-addbox-form_row">
                                <img height="128" id="img-prev" class="c-img-prev"/>
                            </div>
                    </div>
                </div>
                <div class="c-addbox-bottom">
                    <div class="c-addbox-sub">
                        <button type="submit" class="c-btn c-btn-orange" name ="sub-addbox" value="submit-article">Submit</button>
                        <span style="margin-left: 1em;"></span>
                        <button type="reset" onclick="return confirm_reset();" class="c-btn">Reset</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="c-articlesbox">
                <span class="c-articlesbox-header">Recent news</span>
                
                <?php
                    include "connect.php";
                    $getArticles = mysqli_query($con, "SELECT * 
                                            FROM articles
                                            LIMIT 0, 5
                                            ");

                    if(mysqli_num_rows($getArticles) > 0){

                        $articlesArray = array();
                        while($articleRow = mysqli_fetch_array($getArticles)){
                            $articlesArray[] = $articleRow;
                        }
                        
                        usort($articlesArray, function($a, $b) {
                            $ad = new DateTime($a['date']);
                            $bd = new DateTime($b['date']);

                            if ($ad == $bd) {
                                return 0;
                            }

                            return $ad > $bd ? -1 : 1;
                        });
                        
                        foreach($articlesArray as $article){
                            
                            $rawAuthor = $article['author'];
                            $getAuthor = mysqli_query($con, "SELECT username
                                                    FROM users
                                                    WHERE id='$rawAuthor'
                                                    ");
                            $authorResult = mysqli_fetch_assoc($getAuthor);
                            $author = $authorResult['username'];

                            $text = $article['text'];
                            $length = strlen($text);
                            if($length > 500){
                                $text = substr($text, 0, 500);
                            }
                            echo "
                                <div class='c-articlebox'>
                                    <div class='c-articlebox-top'>
                                        <a href='./article?id=".$article['id']."'>
                                            <span class='c-articlebox-title'>".$article['title']."</span>
                                            <span class='c-articlebox-author'> by ".$author."</span>
                                            <span class='c-articlebox-date'>".$article['date']."</span>
                                        </a>
                                    </div>
                                    <div class='c-articlebox-center'>
                                        ".$text;
                            if($length > 500){
                                echo " [...]&nbsp;&nbsp;&nbsp;&nbsp; <strong><a href='./article?id=".$article['id']."' class='c-articlebox-more'>Read more</a></strong>";
                            }
                            echo "
                                    </div>
                                </div>
                            ";
                        }
                    }
                    mysqli_close($con);
                ?>

                
            </div>
            <div class="c-userpanel">
                <span class="c-userpanel-header">User panel</span>
                <div class="c-userpanel-inner">
                    <?php
                        if($_SESSION['user_id'] != ""){
                            echo "
                                <ul class='c-userpanel-list'>
                                    <li class='c-userpanel-list_item'>
                                        <a href='logout' class='c-btn'><i class='material-icons'>person_outline</i>&nbsp;Logout</a>
                                    </li>
                                    <li class='c-userpanel-list_item'>
                                        
                                    </li>
                                    <li class='c-userpanel-list_item'>
                                        <a href='#' class='c-btn' onclick='createArticle()'><i class='material-icons'>add_circle</i>&nbsp;New article</a>
                                    </li>
                                    <li class='c-userpanel-list_item'>
                                        <a href='./articles' class='c-btn'><i class='material-icons'>create</i>&nbsp;Edit articles</a>
                                    </li>
                                </ul>
                            ";
                        }
                        else{
                            echo "
                            <div class='c-login'>
                                <form action='login' method='POST' role='form' class='c-login-form'>
                                    <input type='text' placeholder='Username' name='username' class='c-login-form_input' />
                                    <input type='password' placeholder='Password' name='password' class='c-login-form_input' />
                                    <button type='submit' name='login-submit' class='c-login-form_btn c-btn'><i class='material-icons'>person</i>&nbsp;Login</button>
                                    <a href='./register' class='c-btn'><i class='material-icons'>person_add</i>&nbsp;Register</a>
                                </form>
                            </div>
                            <ul class='c-userpanel-list'>
                                <li class='c-userpanel-list_item'>
                                    <a href='#' class='c-btn' onclick='showLogin()'><i class='material-icons'>person</i>&nbsp;Login</a>
                                </li>
                                <li class='c-userpanel-list_item'>
                                    <a href='./register' class='c-btn'><i class='material-icons'>person_add</i>&nbsp;Register</a>
                                </li>
                            </ul>
                            ";
                        }
                    ?>
                </div>
            </div>
        </div>
        <?php 
            include 'footer.html';
            ob_end_flush();
        ?>
    </body>
</html>