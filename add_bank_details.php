<?php
    require "functions.php";
    session_start();

    if(!isset($_SESSION['Admin']))
    {
        header("location:login.php");
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>
            Add Bank Details | <?php echo $app_name; ?>
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
        <section class= "offer">
            <header>
                <h1 class = "text-center">
                    <?php echo $app_name; ?>
                </h1>
            </header>

            <article>
                <ul>
                    <li><a href="add_branch_details.php">Add Branch Details</a></li>
                    <li><a href="add_credit_card_details.php">Add Credit Card Details</a></li>
                    <li><a href="add_offer_details.php">Add Offers</a></li>
                </ul>
            </article>
        </section>
    </main>
    </body>
</html>