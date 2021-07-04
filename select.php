<?php
    require "functions.php";
    session_start();

    if(!isset($_SESSION["Admin"]))
    {
        header("location:index.php");
    }

    $valid_get_types = array("branch", "card", "offers");
    $edit_type = filter_data($_GET["type"]);
    if(!in_array($edit_type, $valid_get_types))
    {
        header("location:edit_bank_details.php");
    }

    $connection = connect_to_DB();
    if($connection)
    {
        if(strcmp($edit_type, "branch") == 0)
        {
            $show_branches = NULL;
            $show_branches_sql = "SELECT IFSC_Code, Branch_Name, Branch_Address FROM Branch_Details";
            $show_branches_result = mysqli_query($connection, $show_branches_sql);
            if(mysqli_num_rows($show_branches_result) != 0)
            {
                $show_branches = mysqli_fetch_all($show_branches_result, MYSQLI_ASSOC);
            }
        }
        elseif(strcmp($edit_type, "card") == 0)
        {
            $show_cards = NULL;
            $show_cards_sql = "SELECT Card_Name FROM Credit_Card_Details";
            $show_cards_result = mysqli_query($connection, $show_cards_sql);
            if(mysqli_num_rows($show_cards_result) != 0)
            {
                $show_cards = mysqli_fetch_all($show_cards_result, MYSQLI_ASSOC);
            }
        }
        else
        {
            $show_offers = NULL;
            $show_offers_sql = "SELECT * FROM Bank_Offers";
            $show_offers_result = mysqli_query($connection, $show_offers_sql);
            if(mysqli_num_rows($show_offers_result) != 0)
            {
                $show_offers = mysqli_fetch_all($show_offers_result, MYSQLI_ASSOC);
            }
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
            Select to edit | <?php echo $app_name; ?>
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

            <?php if(strcmp($edit_type, "branch") == 0): ?>
                <h3 class = "text-center">
                    Select branch to edit
                </h3>
                <table>
                    <tr>
                        <th>Branch Name</th>
                        <th>Branch Address</th>
                    </tr>
                    <?php foreach($show_branches as $branch): ?>
                        <tr>
                            <td>
                                <?php echo "<a href='edit_branch_details.php?id=".$branch["IFSC_Code"]."'>"; ?>
                                    <?php echo $branch["Branch_Name"]; ?>
                                <?php echo "</a>"; ?>
                            </td>
                            <td>
                                <?php echo $branch["Branch_Address"]; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php elseif(strcmp($edit_type, "card") == 0): ?>
                <h3 class = "text-center">
                    Select credit card to edit
                </h3>
                <table>
                    <tr>
                        <th>Card Name</th>
                    </tr>
                    <?php foreach($show_cards as $card): ?>
                        <tr>
                            <td>
                                <?php echo "<a href='edit_credit_card_details.php?id=".$card["Card_Name"]."'>"; ?>
                                    <?php echo $card["Card_Name"]; ?>
                                <?php echo "</a>"; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <h3 class = "text-center">
                    Select offer to edit
                </h3>
                <table>
                    <tr>
                        <th>Offer Name</th>
                        <th>Offer Details</th>
                    </tr>
                    <?php foreach($show_offers as $offer): ?>
                        <tr>
                            <td>
                                <?php echo "<a href='edit_offer_details.php?id=".$offer["Offer_Id"]."'>"; ?>
                                    <?php echo $offer["Offer_Name"]; ?>
                                <?php echo "</a>"; ?>
                            </td>
                            <td>
                                <?php echo $offer["Offer_Details"]; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>