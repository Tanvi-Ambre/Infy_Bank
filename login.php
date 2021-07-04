<?php
    require "functions.php";
    session_start();

    if(isset($_SESSION["Admin"]))
    {
        header("location:index.php");
    }

    if(isset($_POST["btn_login"]))
    {
        $error_messages = array();
        $login_id_is_email = TRUE;

        if(!empty($_POST["login_id"]) && !empty($_POST["login_pass"]))
        {
            $login_id = filter_data($_POST["login_id"]);
            $login_pass = filter_data($_POST["login_pass"]);
        }
        else
        {
            array_push($error_messages, "Please fill all the fields!");
        }

        if(!filter_var($login_id, FILTER_VALIDATE_EMAIL))
        {
            $login_id_is_email = FALSE;
            if(!preg_match("/^[0-9]{10}+$/", $login_id))
            {
                array_push($error_messages, "Please enter a valid Email ID / Phone Number!");
            }
        }
        
        if(strlen($login_pass) < 6)
        {
            array_push($error_messages, "Password should be atleast 6 characters!");
        }
        elseif(!preg_match("#[0-9]+#", $login_pass))
        {
            array_push($error_messages, "Your password should contain atleast 1 number!");
        }
        elseif(!preg_match("#[A-Z]+#", $login_pass))
        {
            array_push($error_messages, "Your password should contain atleast 1 capital letter!");
        }
        elseif(!preg_match("#[a-z]+#", $login_pass))
        {
            array_push($error_messages, "Your password should contain atleast 1 small letter!");
        }
        elseif(!preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $login_pass))
        {
            array_push($error_messages, "Your password should contain atleast 1 special character!");
        }
        
        if(empty($error_messages))
        {
            $connection = connect_to_DB();
            if($connection)
            {
                if($login_id_is_email)
                {
                    $check_user_sql = "SELECT Email_Id FROM Admin_Details WHERE Email_Id = '$login_id' AND Password = '$login_pass' LIMIT 1";
                }
                else
                {
                    $check_user_sql = "SELECT Phone_Number FROM Admin_Details WHERE Phone_Number = '$login_id' AND Password = '$login_pass' LIMIT 1";
                }
                $check_user_sql_result = mysqli_query($connection, $check_user_sql);
                if(mysqli_num_rows($check_user_sql_result) == 1)
                {
                    $check_user_sql_row = mysqli_fetch_array($check_user_sql_result, MYSQLI_NUM);
                    $_SESSION["Admin"] = $check_user_sql_row[0];
                    header("location:index.php");
                }
                else
                {
                    if($login_id_is_email)
                    {
                        $check_user_exists_sql = "SELECT Email_Id FROM Admin_Details WHERE Email_Id = '$login_id' LIMIT 1";
                    }
                    else
                    {
                        $check_user_exists_sql = "SELECT Phone_Number FROM Admin_Details WHERE Phone_Number = '$login_id' LIMIT 1";
                    }
                    $check_user_exists_result = mysqli_query($connection, $check_user_exists_sql);
                    if(mysqli_num_rows($check_user_exists_result) == 1)
                    {
                        array_push($error_messages, "Please enter the correct password!");
                    }
                    else
                    {
                        array_push($error_messages, "You are not authorized to login as admin!");
                    }
                }
            }
            else
            {
                array_push($error_messages, "ERROR: Could not connect to the database");
            }
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>
            Login | <?php echo $app_name; ?>
        </title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div>
            <h1 class = "text-center">
                    <?php echo $app_name; ?>
            </h1>
            <h3 class = "text-center">
                Login
            </h3>
            <form action="" method="POST">
                <div class = "horizontal-center">
                    <label for = "login-id">Login ID / Phone Number</label>
                    <input type="text" id = "login-id" name = "login_id" placeholder = "Enter Email ID/Phone Number" required>
                    <br><br>
                    <label for = "login-pass">Password</label>
                    <input type="password" id = "login-pass" name = "login_pass" placeholder = "Enter password" required>
                </div>
                <br>
                <div class="footer">
                    <button type="submit" id = "btn-login" name = "btn_login">Login</button>
                    <button type="reset" id = "btn-reset" name = "btn_reset">Reset</button>
                </div>
            </form>
            <br>
            <div>
                <?php if(!empty($error_messages)): ?>
                    <?php foreach($error_messages as $message): ?>
                        <div>
                            <?php echo $message; ?>
                        </div>
                        <br>
                    <?php endforeach; ?>
                    <?php unset($error_messages); ?>
                <?php endif; ?>
            </div>
        </div>
    </body>
</html>