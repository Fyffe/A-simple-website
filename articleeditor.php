<?php
    ob_start();
    session_start();

    $user_id = $_SESSION['user_id'];

    function throwMessage($msg){
        $_SESSION['global_msg'] = $msg;
        header('Location:./index.php');
    }

    if($user_id == ''){
        throwMessage("You need to be logged in to your account in order to edit articles!");
    }
    else{
        include 'connect.php';

        $checkPermissions = mysqli_query($con, "SELECT username, permission
                                                FROM users
                                                WHERE id = '$user_id'
                                                ") or die("Couldn't check permissions");

        if(mysqli_num_rows($checkPermissions) > 0){
            $permissionsResult = mysqli_fetch_assoc($checkPermissions);

            if($permissionsResult['permission'] != 99){
                throwMessage("Insufficent permission!");
            }
        }
        else{
            throwMessage("Couldn't find user!");
        }
    }
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
        <div class='c-editor-main'>
            
        </div>
        <?php
            include 'footer.html';
        ?>
    </body>
</html>
