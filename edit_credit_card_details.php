<?php
    require "functions.php";
    session_start();

    if(!isset($_SESSION['Admin']))
    {
        header("location:login.php");
    }

    $card_id = filter_data($_GET["id"]);
    if(empty($card_id))
    {
        header("location:edit_bank_details.php");
    }

    $error_messages = array();
    $connection = connect_to_DB();
    if($connection)
    {
        $card_details_sql = "SELECT * FROM Credit_Card_Details WHERE Card_Name = '$card_id' LIMIT 1";
        $card_details_result = mysqli_query($connection, $card_details_sql);
        if(mysqli_num_rows($card_details_result) == 1)
        {
            $card_details = mysqli_fetch_array($card_details_result, MYSQLI_ASSOC);
        }
        else
        {
            header("location:edit_bank_details.php");
        }
    }
    else
    {
        array_push($error_messages, "ERROR: Could not connect to the database");
    }
        
    if(isset($_POST["btn_save"]))
    {
        if(!empty($_POST["card_name"]) && !empty($_POST["card_min_limit"]) && !empty($_POST["card_max_limit"]))
        {
            $card_name = filter_data($_POST["card_name"]);
            $card_min_limit = (int) filter_data($_POST["card_min_limit"]);
            $card_max_limit = (int) filter_data($_POST["card_max_limit"]);
        }
        else
        {
            array_push($error_messages, "Please fill all the fields!");
        }

        if(!preg_match("/^[A-Za-z0-9]+$/", $card_name))
        {
            array_push($error_messages, "Please enter a valid card name");
        }

        if($card_min_limit < 10000)
        {
            array_push($error_messages, "Please enter a value greater than 10,000");
        }

        if($card_max_limit < $card_min_limit)
        {
            array_push($error_messages, "Please enter a value greater than the card's minimum limit");
        }

        if(empty($error_messages))
        {
            if(strcmp($card_name, $card_details["Card_Name"]) != 0)
            {
                $check_if_card_name_exists_sql = "SELECT Card_Name FROM Credit_Card_Details WHERE Card_Name = '$card_name' LIMIT 1";
                $check_if_card_name_exists_result = mysqli_query($connection, $check_if_card_name_exists_sql);
                if(mysqli_num_rows($check_if_card_name_exists_result) != 0)
                {
                    array_push($error_messages, "Card Name already exists!");
                }
            }

            if(($card_min_limit != (int) $card_details["Minimum_Amount"]) || ($card_max_limit != (int) $card_details["Maximum_Amount"]))
            {
                $check_if_min_max_limit_exists_sql = "SELECT Card_Name FROM Credit_Card_Details WHERE Minimum_Amount = '$card_min_limit' AND Maximum_Amount = '$card_max_limit' LIMIT 1";
                $check_if_min_max_limit_exists_result = mysqli_query($connection, $check_if_min_max_limit_exists_sql);
                if(mysqli_num_rows($check_if_min_max_limit_exists_result) != 0)
                {
                    array_push($error_messages, "Card limits are already existing!");
                }
            }

            if(empty($error_messages))
            {
                $annual_income_eligibility = $card_min_limit * 12;
                $insert_card_details_sql = "
                    UPDATE Credit_Card_Details 
                    SET Card_Name = '$card_name', 
                        Minimum_Amount = $card_min_limit, 
                        Maximum_Amount = $card_max_limit, 
                        Eligibility = $annual_income_eligibility
                    WHERE Card_Name = '$card_id'
                ";
                if(!mysqli_query($connection, $insert_card_details_sql))
                {
                    array_push($error_messages, "ERROR: Could not update credit card details!");
                }
                else
                {
                    $updated_card_details = TRUE;
                }
            }
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>
            Edit Credit Card Details | <?php echo $app_name; ?>
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
                Edit Credit Card Details
            </h3>

            <div>
                <form action="" method="POST">
                    <div>
                        <table>
                            <tr>
                                <th>Card Name</th>
                                <th>Minimum Limit</th>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" id = "card-name" name = "card_name" value = "<?php echo $card_details["Card_Name"]; ?>" placeholder = "Enter credit card name" required>
                                </td>
                                <td>
                                    <input type="number" id = "card-min-limit" name = "card_min_limit" min="10001" value = "<?php echo $card_details["Minimum_Amount"]; ?>" placeholder = "Enter credit card minimum limit" required>
                                </td>
                            </tr>
                            <tr>
                                <th>Maximum Limit</th>
                                <th>Annual Income Eligibility</th>
                            </tr>
                            <tr>
                                <td>
                                    <input type="number" id = "card-max-limit" name = "card_max_limit" value = "<?php echo $card_details["Maximum_Amount"]; ?>" placeholder = "Enter credit card maximum limit" required>
                                </td>
                                <td>
                                    <?php echo $card_details["Eligibility"]; ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <br><br>
                    <div>
                        <button type="submit" id = "btn-save" name = "btn_save">Save</button>
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
                    <?php if(isset($updated_card_details)): ?>
                        <div>
                            <p>Credit Card details updated successfully!</p>
                        </div>
                        <?php unset($updated_card_details); ?>
                    <?php endif; ?>        
                <?php endif; ?>
            </div>
        </div>
    </body>
</html>