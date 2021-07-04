<?php
    require "functions.php";
    session_start();

    if(!isset($_SESSION['Admin']))
    {
        header("location:login.php");
    }

    $offer_id = filter_data($_GET["id"]);
    if(empty($offer_id))
    {
        header("location:edit_bank_details.php");
    }

    $error_messages = array();
    $connection = connect_to_DB();
    if($connection)
    {
        $offer_details_sql = "SELECT * FROM Bank_Offers WHERE Offer_Id  = '$offer_id' LIMIT 1";
        $offer_details_result = mysqli_query($connection, $offer_details_sql);
        if(mysqli_num_rows($offer_details_result) == 1)
        {
            $old_offer_details = mysqli_fetch_array($offer_details_result, MYSQLI_ASSOC);
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
        if(!empty($_POST["offer_name"]) && !empty($_POST["offer_details"]))
        {
            $offer_name = filter_data($_POST["offer_name"]);
            $offer_details = filter_data($_POST["offer_details"]);
        }
        else
        {
            array_push($error_messages, "Please fill all the fields!");
        }

        if(!preg_match("/^[A-Za-z0-9-]+$/", $offer_name))
        {
            array_push($error_messages, "Please enter a offer name");
        }
        
        if(empty($error_messages))
        {
            if(strcmp($offer_name, $old_offer_details["Offer_Name"]) != 0)
            {
                $check_if_offer_name_exists_sql = "SELECT Offer_Id FROM Bank_Offers WHERE Offer_Name = '$offer_name' LIMIT 1";
                $check_if_offer_name_exists_result = mysqli_query($connection, $check_if_offer_name_exists_sql);
                if(mysqli_num_rows($check_if_offer_name_exists_result) != 0)
                {
                    array_push($error_messages, "Offer Name already exists!");
                }
            }
            
            if(strcmp($offer_details, $old_offer_details["Offer_Details"]) != 0)
            {
                $check_if_offer_details_exists_sql = "SELECT Offer_Id FROM Bank_Offers WHERE Offer_Details = '$offer_details' LIMIT 1";
                $check_if_offer_details_exists_result = mysqli_query($connection, $check_if_offer_details_exists_sql);
                if(mysqli_num_rows($check_if_offer_details_exists_result) != 0)
                {
                    array_push($error_messages, "Offer Details already exist!");
                }
            }

            if(empty($error_messages))
            {
                $insert_offer_details_sql = "
                    UPDATE Bank_Offers 
                        SET Offer_Name = '$offer_name', 
                        Offer_Details = '$offer_details' 
                    WHERE Offer_Id = '$offer_id'";
                if(!mysqli_query($connection, $insert_offer_details_sql))
                {
                    array_push($error_messages, "ERROR: Could not insert data into table!");
                }
                else
                {
                    $updated_bank_offers = TRUE;
                }
            }
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>
            Edit Offer Details | <?php echo $app_name; ?>
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

            <div>
                <h3>Edit Offer Details</h3>

                <form action="" method="POST">
                    <div>
                        <table>
                            <tr>
                                <th>
                                    Offer Name
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" id = "offer-name" name = "offer_name" value = "<?php echo $old_offer_details["Offer_Name"] ?>" placeholder = "Enter offer name" required>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Offer Details
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" id = "offer-details" name = "offer_details" value = "<?php echo $old_offer_details["Offer_Details"] ?>" placeholder = "Enter offer details" required>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <br>
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
                    <?php if(isset($updated_bank_offers)): ?>
                        <div>
                            <p>Bank offer updated successfully!</p>
                        </div>
                        <?php unset($updated_bank_offers); ?>
                    <?php endif; ?>        
                <?php endif; ?>
            </div>
        </div>
    </body>
</html>