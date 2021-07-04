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

        if(!empty($_POST["branch_name"]) && !empty($_POST["branch_address"]) && !empty($_POST["branch_manager"]))
        {
            $branch_name = filter_data($_POST["branch_name"]);
            $branch_address = filter_data($_POST["branch_address"]);
            $branch_manager = filter_data($_POST["branch_manager"]);
        }
        else
        {
            array_push($error_messages, "Please fill all the fields!");
        }

        if(!preg_match("/^[A-Za-z ]+$/", $branch_name))
        {
            array_push($error_messages, "Please enter a valid branch name");
        }
        elseif(!preg_match("/^[A-Za-z, ]+?\-?[0-9]+$/", $branch_address))
        {
            array_push($error_messages, "Please enter a valid branch address");
        }
        elseif(!preg_match("/^[A-Za-z ]+$/", $branch_manager))
        {
            array_push($error_messages, "Please enter a valid branch manager name");
        }

        if(empty($error_messages))
        {
            $connection = connect_to_DB();
            if($connection)
            {
                $check_if_branch_name_already_exists_sql = "SELECT * FROM Branch_Details WHERE Branch_Name = '$branch_name' LIMIT 1";
                $check_if_branch_name_already_exists_result = mysqli_query($connection, $check_if_branch_name_already_exists_sql);
                if(mysqli_num_rows($check_if_branch_name_already_exists_result) != 0)
                {
                    array_push($error_messages, "Branch name already exists!");
                }
                if(!in_array("Branch name already exists!", $error_messages))
                {
                    $check_if_branch_address_already_exists_sql = "SELECT * FROM Branch_Details WHERE Branch_Address = '$branch_address' LIMIT 1";
                    $check_if_branch_address_already_exists_result = mysqli_query($connection, $check_if_branch_address_already_exists_sql);
                    if(mysqli_num_rows($check_if_branch_address_already_exists_result) != 0)
                    {
                        array_push($error_messages, "Branch address already exists!");
                    }
                }
                if(!in_array("Branch name already exists!", $error_messages) && !in_array("Branch address already exists!", $error_messages))
                {
                    $check_if_branch_manager_already_exists_sql = "SELECT * FROM Branch_Details WHERE Manager_Name = '$branch_manager' LIMIT 1";
                    $check_if_branch_manager_already_exists_result = mysqli_query($connection, $check_if_branch_manager_already_exists_sql);
                    if(mysqli_num_rows($check_if_branch_manager_already_exists_result) != 0)
                    {
                        array_push($error_messages, "Branch manager already exists!");
                    }
                }

                if(empty($error_messages))
                {
                    $get_latest_IFSC_code_sql = "SELECT IFSC_Code FROM Branch_Details ORDER BY IFSC_Code DESC LIMIT 1";
                    $get_latest_IFSC_code_result = mysqli_query($connection, $get_latest_IFSC_code_sql);
                    if(mysqli_num_rows($get_latest_IFSC_code_result) != 0)
                    {
                        $latest_IFSC_code = mysqli_fetch_array($get_latest_IFSC_code_result, MYSQLI_ASSOC);
                        $latest_IFSC_code = $latest_IFSC_code['IFSC_Code'];
                        $int_value = explode("B", $latest_IFSC_code);
                        $int_value = $int_value[1] + 1;
                        $latest_IFSC_code = str_pad($int_value, 7, '0', STR_PAD_LEFT);
                        $latest_IFSC_code = "IB".$latest_IFSC_code;
                    }
                    else
                    {
                        $latest_IFSC_code = "IB0000000";
                    }

                    $insert_new_branch_details_sql = "
                        INSERT INTO Branch_Details (IFSC_Code, Branch_Name, Manager_Name, Customer_Count, Staff_Count, Branch_Rank, Branch_Address)
                        VALUES('$latest_IFSC_code', '$branch_name', '$branch_manager', 0, 0, 4, '$branch_address');
                    ";
                    if(!mysqli_query($connection, $insert_new_branch_details_sql))
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
            Add Branch Details | <?php echo $app_name; ?>
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
                <h3>Fill the details to add new branch</h3>

                <form action="" method="POST">
                    <div>
                        <label for = "branch-name">Branch Name</label>
                        <input type="text" id = "branch-name" name = "branch_name" placeholder = "Enter new branch name" required>
                        <br><br>

                        <label for = "branch-address">Branch Address</label>
                        <input type="text" id = "branch-address" name = "branch_address" placeholder = "Enter new branch address" required>
                        <br><br>

                        <label for = "branch-manager">Branch Manager</label>
                        <input type="text" id = "branch-manager" name = "branch_manager" placeholder = "Enter new branch manager" required>
                        <br><br>
                    </div>
                    <br>
                    <div>
                        <button type="submit" id = "btn-add" name = "btn_add">Add</button>
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
                    <?php if(isset($inserted)): ?>
                        <div>
                            <p>Branch Details added successfully!</p>
                        </div>
                        <?php unset($inserted); ?>
                    <?php endif; ?>        
                <?php endif; ?>
            </div>
        </div>
    </body>
</html>