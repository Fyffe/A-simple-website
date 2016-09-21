<?php
    ob_start();
    session_start();

    $user_id = $_SESSION['user_id'];

    function throwMessage($msg){
        $_SESSION['global_msg'] = $msg;
        header('Location:./');
    }

    if($user_id == ''){
        $rawName = $_GET['username'];
        $rawCode = $_GET['code'];

        include 'connect.php';
        $query = mysqli_query($con, "SELECT code, activated
                                    FROM users
                                    WHERE username = '$rawName'
                                    ");
        
        if(mysqli_num_rows($query) > 0){
            $result = mysqli_fetch_assoc($query);
            $decode = $result['code'];

            if($result['activated'] == 0){
                if($decode == $rawCode){
                    mysqli_query($con, "UPDATE users
                                        SET activated = 1
                                        WHERE username = '$rawName'
                                        ");
                    
                    mysqli_close($con);                
                    throwMessage("Your account has been successfully activated!");
                }
                else{
                    throwMessage("The coded you've provided is incorrect!");
                }
            }
            else{
                throwMessage("This account is already active!");
            }
        }
        else{
            throwMessage("Username $rawName doesn't exist!");
        }
        
        
    }
    else{
        throwMessage("Youre already logged in!");
    }
    
    mysqli_close($con);
    ob_end_flush();
?>