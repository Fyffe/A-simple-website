<?php
    ob_start();
    session_start();

    $user_id = $_SESSION['user_id'];

    function throwMsg($msg){
        $_SESSION['global_msg'] = $msg;
        header("Location:./");
    }

    if($user_id != ''){
        include "connect.php";
        $getUser = mysqli_query($con, "SELECT id, username, permission
                                        FROM users
                                        WHERE id='$user_id'
                                        ");
        mysqli_close($con);

        if(mysqli_num_rows($getUser) > 0){
            $result = mysqli_fetch_assoc($getUser);
            $author = $result['username'];
            $permission = $result['permission'];

            if($permission == 99){
                $title = $_POST['title'];
                $content = $_POST['content'];
                $img = 'default.png';

                if($_FILES["bgimg"]["name"] != ''){
                        
                    $temp = $_FILES["bgimg"]["name"];
                    $ext = end((explode(".", $temp)));
                    $img = round(microtime(true)) . '.' . $ext;

                    $target_dir = "images/articles/";
                    $target_file = $target_dir . $img;
                    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

                    $check = getimagesize($_FILES["bgimg"]["tmp_name"]);

                    if($check !== false){
                        if(!file_exists($target_file)){
                            if($_FILES["bgimg"]["size"] < 5000000){
                                if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif"){
                                    throwMsg("Only .jpg, .png, .jpeg, .gif filetypes are accepted!");
                                }
                            }
                            else{
                                throwMsg("Sorry, your file is too big! Max filesize is 5mb!");
                            }
                        }
                        else{
                            throwMsg("Sorry, you're trying to upload a duplicate!");
                        }
                    }
                    else{
                        throwMsg("This is not an image!");
                    }

                    if (move_uploaded_file($_FILES["bgimg"]["tmp_name"], $target_file)) {
                                        
                    } 
                    else {
                        throwMsg("Sorry, there was an error uploading your file.");
                    }
                }

                include "connect.php";
                $addArticle = mysqli_query($con, "INSERT INTO articles(title, author, date, text, img)
                                                VALUES ('$title', '$author', now(), '$content', '$img')
                                                ") or die("Couln't add article :(");

                mysqli_close($con);

                throwMsg("Article succesfully added!");
            }
            else{
                throwMsg("Your account has insufficent permissions! ".$permission);
            }
        }
        else{
            throwMsg("Couln't find user!");
        }
    }
    else{
        throwMsg("You're not logged in!");
    }

    ob_end_flush();
?>