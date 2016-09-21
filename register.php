<?php
    ob_start();
    session_start();
    $user_id = $_SESSION['user_id'];

    if($user_id != '')
    {
        $_SESSION['global_msg'] = "Already logged in!";
        header('Location: ./');
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
                $('.c-register-form').validate({
                    rules: {
                        username: {
                            required: true,
                            minlength: 4,
                            maxlength: 16
                        },
                        email: {
                            required: true,
                            email: true
                        },
                        pwd: {
                            required: true,
                            minlength: 6,
                            maxlength: 20
                        },
                        cpwd: {
                            required: true,
                            minlength: 6,
                            maxlength: 20,
                            equalTo: "#register-pwd"
                        }
                    },
                    messages: {
                        username: {
                            required: "You need to provide us your username!"
                        },
                        email: {
                            required: "Your email is also required!"
                        },
                        pwd: {
                            required: "Password is required!"
                        },
                        cpwd: {
                            required: "You need to confirm your password!",
                        }
                    }
                });
            });
        </script>
        <?php
            include "nav.html"; 
        ?>
        <div class="c-mainbox">
            <div class="c-register-box">
                <h2 class="c-label_big">Orange Stripes</h2>
                <h4 class="c-label_small">Account Registration</h4>
                <div class="c-formbox">
                    <form action="register.php" method="POST" role="form" class="c-register-form">
                        <?php

                            function generateMailMessage($address, $name, $acode){
                                $msg = "Click this link to activate your account on OrangeStripes: http://www.orangestripes.xaa.pl/mailactivation?code=".$acode."&username=".$name;

                                mail($address, "Account activation for OrangeStripes!", $msg, "From: OrangeStripes");
                            }

                            function generateActivationCode(){
                                $length = 12;
                                $charsToUse = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                                $range = strlen($charsToUse);
                                $accode = '';

                                for($i = 0; $i < $length; $i++){
                                    $accode .= $charsToUse[rand(0, $range - 1)];
                                }

                                return $accode;
                            }

                            if($_POST['btn'] == 'submit-registration-form'){
                                if($_POST['username'] != '' && $_POST['pwd'] != '' && $_POST['cpwd'] != '' && $_POST['email'] != ''){
                                    if($_POST['pwd'] == $_POST['cpwd']){
                                        if(!preg_match('/\s/', $_POST['username'])){
                                            if(!preg_match('/[^a-z0-9]/i', $_POST['username'])){
                                                if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
                                                    include 'connect.php';

                                                    $username = strtolower($_POST['username']);
                                                    $email = $_POST['email'];

                                                    $mailcheck = mysqli_query($con, "SELECT email
                                                                            FROM users 
                                                                            WHERE email='$email'
                                                                            ");

                                                    if(!mysqli_num_rows($mailcheck) >= 1){
                                                        $namecheck = mysqli_query($con, "SELECT username
                                                                                FROM users
                                                                                WHERE username='$username'
                                                                                ");

                                                        if(!mysqli_num_rows($namecheck) >= 1){
                                                            $pass = md5($_POST['pwd']);
                                                            $code = generateActivationCode();

                                                            mysqli_query($con, "INSERT INTO users(username, password, email, code)
                                                                                VALUES ('$username', '$pass', '$email', '$code')
                                                                                ") or die("Couldn't insert into users");

                                                            generateMailMessage($email, $username, $code);
                                                            mysqli_close($con);
                                                            $_SESSION['registered'] = "true";
                                                            header("Location: ./");
                                                        }
                                                        else{
                                                            $error_msg = "This username is already in use!";
                                                        }
                                                    }
                                                    else{
                                                        $error_msg = "This email is already in use!";
                                                    }
                                                }
                                                else{
                                                    $error_msg = "The email address you have provided is incorrect!";
                                                }
                                            }
                                            else{
                                                $error_msg = "Username can't contain special characters!";
                                            }
                                        }
                                        else{
                                            $error_msg = "Username can't contain white spaces!";
                                        }
                                    }
                                    else{
                                        $error_msg = "Passwords don't match!";
                                    }
                                }
                                else{
                                    $error_msg = "You can't leave empty fields!";
                                }
                            }
                        ?>

                        <div class="c-register-form-row">
                            <input type="text" class="c-register-form_input" id="register-name" placeholder="Username" name="username" value="<?php echo $_POST['username']; ?>" />
                        </div>
                        <div class="c-register-form-row">
                            <input type="text" class="c-register-form_input" id="register-mail" placeholder="Email" name="email" value="<?php echo $_POST['email']; ?>" />
                        </div>
                        <div class="c-register-form-row">
                            <input type="password" class="c-register-form_input" id="register-pwd" placeholder="Password" name="pwd" value="<?php echo $_POST['pwd']; ?>" />
                        </div>
                        <div class="c-register-form-row">
                            <input type="password" class="c-register-form_input" id="register-pwd-c" placeholder="Confirm password" name="cpwd" 
                            value="<?php echo $_POST['cpwd']; ?>" />
                        </div>
                        <div class="c-register-form-row">
                            <button type="submit" class="c-btn c-btn-orange c-register-form_btn" name="btn" value="submit-registration-form">Register</button>
                        </div>
                    </form>
                </div>
                <?php
                    if($error_msg){
                        echo "<div class='c-alert'>".$error_msg."</div>";
                    }
                ?>
            </div>
        </div>
        <?php
            include 'footer.html'; 
            ob_end_flush();
        ?>
     </body>
</html>