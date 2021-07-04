<?php
    require "functions.php";
    session_start();

    if(!isset($_SESSION['Admin']))
    {
        header("location:login.php");
    }

    $error_messages = array();
    $connection = connect_to_DB();

    if($connection)
    {
        $get_offer_details_sql = "SELECT Offer_Details FROM Bank_Offers";
        $get_offer_details_result = mysqli_query($connection, $get_offer_details_sql);
        if(mysqli_num_rows($get_offer_details_result) != 0)
        {
            $offer_details = mysqli_fetch_all($get_offer_details_result, MYSQLI_ASSOC);
        }
        else
        {
            $offer_details = NULL;
        }
    }
    else
    {
        array_push($error_messages, "ERROR: Could not connect to the database");
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>
            Homepage | <?php echo $app_name; ?>
        </title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div>
            <h1 class = "text-center">
                <?php echo $app_name; ?>
            </h1>

            <p>Welcome Admin!</p>
            <br>

            <div>
                <?php include "sidebar.php"; ?>
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
                    <h3>Offers</h3>
                    <?php if(!empty($offer_details)): ?>
                        <?php foreach($offer_details as $offer): ?>
                            <div>
                                <?php echo $offer["Offer_Details"]; ?>
                            </div>
                            <br>
                        <?php endforeach; ?>
                    <?php else:?>
                        <div>No Offers available</div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </body>
</html>