<?php
    require "functions.php";
    session_start();

    if(!isset($_SESSION['Admin']))
    {
        header("location:login.php");
    }

    if(isset($_POST['btn_add']))
    {
        $error_messages = array();

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
            $connection = connect_to_DB();
            if($connection)
            {
                $check_if_offer_name_exists_sql = "SELECT Offer_Id FROM Bank_Offers WHERE Offer_Name = '$offer_name' LIMIT 1";
                $check_if_offer_name_exists_result = mysqli_query($connection, $check_if_offer_name_exists_sql);
                if(mysqli_num_rows($check_if_offer_name_exists_result) != 0)
                {
                    array_push($error_messages, "Offer Name already exists!");
                }
                else
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
                    $latest_offer_id_sql = "SELECT Offer_Id FROM Bank_Offers ORDER BY Offer_Id DESC LIMIT 1";
                    $latest_offer_id_result = mysqli_query($connection, $latest_offer_id_sql);
                    if(mysqli_num_rows($latest_offer_id_result) != 0)
                    {
                        $latest_offer_id = mysqli_fetch_array($latest_offer_id_result, MYSQLI_ASSOC);
                        $latest_offer_id = $latest_offer_id["Offer_Id"] + 1;
                    }
                    else
                    {
                        $latest_offer_id = 100;
                    }

                    $insert_offer_details_sql = "INSERT INTO Bank_Offers(Offer_Id, Offer_Name, Offer_Details) VALUES ('$latest_offer_id', '$offer_name', '$offer_details')";
                    if(!mysqli_query($connection, $insert_offer_details_sql))
                    {
                        array_push($error_messages, "ERROR: Could not insert data into table!");
                    }
                    else
                    {
                        $inserted = TRUE;
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
            Add Offer Details | <?php echo $app_name; ?>
        </title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
    <main>
        <section>
            <p>Hi Admin!</p>
            <br>

            <div>
                <?php include "sidebar.php"; ?>
            </div>
            </section>
        <section class="offer">
            <header>
            <h1 class = "text-center">
                <?php echo $app_name; ?>
            </h1>
            </header>
            <br><br><br>

            
                <h3  class="text-left">Fill the details to add new offers</h3>
                <article id="branch">
                <form action="" method="POST" id="branch_form">
                    <div class="horizontal-center">
                        <label for = "offer-name">Offer Name</label>
                        <input type="text" id = "offer-name" name = "offer_name" placeholder = "Enter offer name" required>
                        <br><br>

                        <label for = "offer-details">Offer Details</label>
                        <input type="text" id = "offer-details" name = "offer_details" placeholder = "Enter offer details" required>
                        <br><br>
                    </div>
                    <br>
                    <div class="barnch_footer">
                        <button type="submit" id = "btn-add" name = "btn_add">Add</button>
                        <button type="reset" id = "btn-reset" name = "btn_reset">Reset</button>
                    </div>
                </form>
            </div>
            <div class = "error">
                <?php if(!empty($error_messages)): ?>
                    <?php foreach($error_messages as $message): ?>
                        <div>
                            <?php echo $message; ?>
                        </div>
                        <br>
                    <?php endforeach; ?>
                    <?php unset($error_messages); ?>
                <?php else: ?>
                    <?php if(isset($inserted)): ?>
                        <div>
                            <p>Bank offer added successfully!</p>
                        </div>
                        <?php unset($inserted); ?>
                    <?php endif; ?>        
                <?php endif; ?>
            </div>
        </div>
    </body>
</html>