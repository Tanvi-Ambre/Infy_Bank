<?php
    require "functions.php";
    session_start();

    if(!isset($_SESSION['Admin']))
    {
        header("location:login.php");
    }

    if(isset($_POST["btn_submit"]))
    {
        $error_messages = array();

        if(!empty($_POST["old_login_pass"]) && !empty($_POST["new_login_pass"]) && !empty($_POST["confirm_new_login_pass"]))
        {
            $old_login_pass = filter_data($_POST["old_login_pass"]);
            $new_login_pass = filter_data($_POST["new_login_pass"]);
            $confirm_new_login_pass = filter_data($_POST["confirm_new_login_pass"]);
        }
        else
        {
            array_push($error_messages, "Please fill all the fields!");
        }

        if(strlen($new_login_pass) < 6)
        {
            array_push($error_messages, "Password should be atleast 6 characters!");
        }
        elseif(!preg_match("#[0-9]+#", $new_login_pass))
        {
            array_push($error_messages, "Your password should contain atleast 1 number!");
        }
        elseif(!preg_match("#[A-Z]+#", $new_login_pass))
        {
            array_push($error_messages, "Your password should contain atleast 1 capital letter!");
        }
        elseif(!preg_match("#[a-z]+#", $new_login_pass))
        {
            array_push($error_messages, "Your password should contain atleast 1 small letter!");
        }
        elseif(!preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $new_login_pass))
        {
            array_push($error_messages, "Your password should contain atleast 1 special character!");
        }

        if(strcmp($new_login_pass, $confirm_new_login_pass) != 0)
        {
            array_push($error_messages, "Your password confirmation does not match the new password!");
        }

        if(empty($error_messages))
        {
            $connection = connect_to_DB();
            if($connection)
            {
                $login_id = $_SESSION['Admin'];
                $login_id_is_email = FALSE;
                if(filter_var($login_id, FILTER_VALIDATE_EMAIL))
                {
                    $login_id_is_email = TRUE;
                }

                if($login_id_is_email)
                {
                    $verify_user_sql = "SELECT Email_Id FROM Admin_Details WHERE Email_Id = '$login_id' AND Password = '$old_login_pass' LIMIT 1";
                }
                else
                {
                    $verify_user_sql = "SELECT Phone_Number FROM Admin_Details WHERE Phone_Number = '$login_id' AND Password = '$old_login_pass' LIMIT 1";
                }
                $verify_user_sql_result = mysqli_query($connection, $verify_user_sql);
                if(mysqli_num_rows($verify_user_sql_result) == 1)
                {
                    if($login_id_is_email)
                    {
                        $update_password_sql = "UPDATE Admin_Details SET Password = '$new_login_pass' WHERE Email_Id = '$login_id'";
                    }
                    else
                    {
                        $update_password_sql = "UPDATE Admin_Details SET Password = '$new_login_pass' WHERE Phone_Number = '$login_id'";
                    }

                    if(!mysqli_query($connection, $update_password_sql))
                    {
                        array_push($error_messages, "ERROR: Could not update password!");
                    }
                    else
                    {
                        $updated_password = TRUE;
                    }
                }
                else
                {
                    array_push($error_messages, "Your old login password is incorrect! Please verify your old login password.");
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
            Change Password | <?php echo $app_name; ?>
        </title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div>
            <h1 class = "text-center">
                <?php echo $app_name; ?>
            </h1>

            <p>Hi Admin!</p>
            <br>

            <div>
                <?php include "sidebar.php"; ?>
            </div>

            <h3 class = "text-center">
                Change Password
            </h3>

            <div>
                <form action="" method="POST">
                    <div>
                        <table>
                            <tr>
                                <td>
                                    Old Login Password
                                </td>
                                <td>
                                    <input type="password" id = "old-login-pass" name = "old_login_pass" placeholder = "Enter old login password" required>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    New Login Password
                                </td>
                                <td>
                                    <input type="password" id = "new-login-pass" name = "new_login_pass" placeholder = "Enter new login password" required>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Confirm New Login Password
                                </td>
                                <td>
                                    <input type="password" id = "confirm-new-login-pass" name = "confirm_new_login_pass" placeholder = "Confim new login password" required>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <br>
                    <div>
                        <button type="submit" id = "btn-submit" name = "btn_submit">Submit</button>
                        <button type="reset" id = "btn-reset" name = "btn_reset">Reset</button>
                    </div>
                </form>
            </div>
            <div>
                <?php if(!empty($error_messages)): ?>
                    <?php foreach($error_messages as $message): ?>
                        <div>
                            <?php echo $message; ?>
                        </div>
                        <br>
                    <?php endforeach; ?>
                    <?php unset($error_messages); ?>
                <?php else: ?>
                    <?php if(isset($updated_password)): ?>
                        <div>
                            <p>Password updated successfully!</p>
                        </div>
                        <?php unset($updated_password); ?>
                    <?php endif; ?>        
                <?php endif; ?>
            </div>
        </div>
    </body>
</html>