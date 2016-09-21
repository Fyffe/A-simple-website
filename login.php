<?php
    ob_start();
    session_start();
    $user_id = $_SESSION['user_id'];

    function throwMessage($msg){
        $_SESSION['global_msg'] = $msg;
        header('Location:./');
    }

    if($user_id == ''){
        $username = $_POST['username'];
        $password = $_POST['password'];

        if($username != '' && $password != ''){
            include "connect.php";

            $query = mysqli_query($con, "SELECT id, password, activated, permission
                                        FROM users
                                        WHERE username='$username'
                                        ") or die("Couldn't fetch users");
            
            mysqli_close($con);
            if(mysqli_num_rows($query) > 0){
                $result = mysqli_fetch_assoc($query);
                $pass = md5($password);

                if($pass == $result['password']){
                    if($result['activated'] == 1){
                        $_SESSION['user_id'] = $result['id'];
                        $_SESSION['global_msg'] = "You have been successfully logged in!";
                        $_SESSION['user_permission'] =  $result['permission'];
                        header('Location: ./');
                        exit;
                    }
                    else{
                        throwMessage("This account is not active yet");
                    }
                }
                else{
                    throwMessage("Login or password is incorrect");
                }
            }
            else{
                throwMessage("Login or password is incorrect");
            }
        }
        else{
            throwMessage("You need to fill all fields!");
        }
    }
    else
    {
        throwMessage("Already logged in!");
    }

    ob_end_flush();
?>