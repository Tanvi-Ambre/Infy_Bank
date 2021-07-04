<?php
require "functions.php";
session_start();

if (!isset($_SESSION['Admin'])) {
    header("location:login.php");
}

if (isset($_POST['btn_add'])) {
    $error_messages = array();

    if (!empty($_POST["card_name"]) && !empty($_POST["card_min_limit"]) && !empty($_POST["card_max_limit"])) {
        $card_name = filter_data($_POST["card_name"]);
        $card_min_limit = (int) filter_data($_POST["card_min_limit"]);
        $card_max_limit = (int) filter_data($_POST["card_max_limit"]);
    } else {
        array_push($error_messages, "Please fill all the fields!");
    }

    if (!preg_match("/^[A-Za-z0-9]+$/", $card_name)) {
        array_push($error_messages, "Please enter a valid card name");
    }

    if ($card_min_limit < 10000) {
        array_push($error_messages, "Please enter a value greater than 10,000");
    }

    if ($card_max_limit < $card_min_limit) {
        array_push($error_messages, "Please enter a value greater than the card's minimum limit");
    }

    if (empty($error_messages)) {
        $connection = connect_to_DB();
        if ($connection) {
            $check_if_card_name_exists_sql = "SELECT Card_Name FROM Credit_Card_Details WHERE Card_Name = '$card_name' LIMIT 1";
            $check_if_card_name_exists_result = mysqli_query($connection, $check_if_card_name_exists_sql);
            if (mysqli_num_rows($check_if_card_name_exists_result) != 0) {
                array_push($error_messages, "Card Name already exists!");
            } else {
                $check_if_min_max_limit_exists_sql = "SELECT Card_Name FROM Credit_Card_Details WHERE Minimum_Amount = '$card_min_limit' AND Maximum_Amount = '$card_max_limit' LIMIT 1";
                $check_if_min_max_limit_exists_result = mysqli_query($connection, $check_if_min_max_limit_exists_sql);
                if (mysqli_num_rows($check_if_min_max_limit_exists_result) != 0) {
                    array_push($error_messages, "Card limits are already existing!");
                }
            }

            if (empty($error_messages)) {
                $annual_income_eligibility = $card_min_limit * 12;
                $insert_card_details_sql = "INSERT INTO Credit_Card_Details (Card_Name, Minimum_Amount, Maximum_Amount, Eligibility) VALUES ('$card_name', $card_min_limit, $card_max_limit, $annual_income_eligibility)";
                if (!mysqli_query($connection, $insert_card_details_sql)) {
                    array_push($error_messages, "ERROR: Could not insert data into table!");
                } else {
                    $updated_card_details = TRUE;
                }
            }
        } else {
            array_push($error_messages, "ERROR: Could not connect to the database");
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>
        Add Credit Card Details | <?php echo $app_name; ?>
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
                <h1 class="text-left">
                    <?php echo $app_name; ?>
                </h1>

            </header>
            <br><br><br>
            <h3  class="text-left">Fill the details to add new credit card</h3>

            <article id="branch">


                <form action="" method="POST" id="branch_form">
                    <div class="horizontal-center">
                        <label for="card-name">Card Name</label>
                        <input type="text" id="card-name" name="card_name" placeholder="Enter credit card name" required>
                        <br><br>

                        <label for="card-min-limit">Minimum Limit</label>
                        <input type="number" id="card-min-limit" name="card_min_limit" placeholder="Enter credit card minimum limit" min="10001" required>
                        <br><br>

                        <label for="card-max-limit">Maximum Limit</label>
                        <input type="number" id="card-max-limit" name="card_max_limit" placeholder="Enter credit card maximum limit" required>
                        <br><br>
                    </div>
                    <br>
                    <div class="barnch_footer">
                        <button type="submit" id="btn-add" name="btn_add">Add</button>
                        <button type="reset" id="btn-reset" name="btn_reset">Reset</button>
                    </div>
                </form>
            </article>
        </section>
        <div class = "error">
            <?php if (!empty($error_messages)) : ?>
                <?php foreach ($error_messages as $message) : ?>
                    <div>
                        <?php echo $message; ?>
                    </div>
                    <br>
                <?php endforeach; ?>
                <?php unset($error_messages); ?>
            <?php else : ?>
                <?php if (isset($updated_card_details)) : ?>
                    <div>
                        <p>Credit Card details added successfully!</p>
                    </div>
                    <?php unset($updated_card_details); ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </main>
</body>

</html>