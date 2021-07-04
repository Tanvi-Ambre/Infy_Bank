<?php
    require "functions.php";
    session_start();

    if(!isset($_SESSION['Admin']))
    {
        header("location:login.php");
    }

    $branch_id = filter_data($_GET["id"]);
    if(empty($branch_id))
    {
        header("location:edit_bank_details.php");
    }

    $error_messages = array();
    $connection = connect_to_DB();
    if($connection)
    {
        $old_branch_details_sql = "SELECT * FROM Branch_Details WHERE IFSC_Code = '$branch_id' LIMIT 1";
        $old_branch_details_result = mysqli_query($connection, $old_branch_details_sql);
        if(mysqli_num_rows($old_branch_details_result) == 1)
        {
            $old_branch_details = mysqli_fetch_array($old_branch_details_result, MYSQLI_ASSOC);
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
        if(!empty($_POST["branch_name"]) && !empty($_POST["branch_address"]) && !empty($_POST["branch_manager"]) && !empty($_POST["branch_rank"]) && !empty($_POST["branch_staff_count"]) && !empty($_POST["branch_customer_count"]))
        {
            $branch_name = filter_data($_POST["branch_name"]);
            $branch_address = filter_data($_POST["branch_address"]);
            $branch_manager = filter_data($_POST["branch_manager"]);
            $branch_rank = (int) filter_data($_POST["branch_rank"]);
            $branch_staff_count = (int) filter_data($_POST["branch_staff_count"]);
            $branch_customer_count = (int) filter_data($_POST["branch_customer_count"]);
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
        elseif(!preg_match("/^[0-9]+$/", $branch_rank) && ($branch_rank > 0 && $branch_rank < 5))
        {
            array_push($error_messages, "Please enter a valid branch rank");
        }
        elseif(!preg_match("/^[0-9]+$/", $branch_staff_count) && $branch_staff_count < 1)
        {
            array_push($error_messages, "Please enter a valid branch staff count");
        }
        elseif(!preg_match("/^[0-9]+$/", $branch_customer_count) && $branch_customer_count < 0)
        {
            array_push($error_messages, "Please enter a valid branch customer count");
        }

        if(strcmp($branch_name, $old_branch_details["Branch_Name"]) != 0 && empty($error_messages))
        {
            $check_if_branch_name_already_exists_sql = "SELECT * FROM Branch_Details WHERE Branch_Name = '$branch_name' LIMIT 1";
            $check_if_branch_name_already_exists_result = mysqli_query($connection, $check_if_branch_name_already_exists_sql);
            if(mysqli_num_rows($check_if_branch_name_already_exists_result) != 0)
            {
                array_push($error_messages, "Branch name already exists!");
            }
        }

        if(strcmp($branch_address, $old_branch_details["Branch_Address"]) != 0 && empty($error_messages))
        {
            $check_if_branch_address_already_exists_sql = "SELECT * FROM Branch_Details WHERE Branch_Address = '$branch_address' LIMIT 1";
            $check_if_branch_address_already_exists_result = mysqli_query($connection, $check_if_branch_address_already_exists_sql);
            if(mysqli_num_rows($check_if_branch_address_already_exists_result) != 0)
            {
                array_push($error_messages, "Branch address already exists!");
            }
        }

        if(empty($error_messages))
        {
            if($branch_customer_count >= 1000)
            {
                $branch_rank = 1;
            }
            elseif($branch_customer_count >= 100 && $branch_customer_count <= 999)
            {
                $branch_rank = 2;
            }
            elseif($branch_customer_count >= 1 && $branch_customer_count <= 99)
            {
                $branch_rank = 3;
            }
            else
            {
                $branch_rank = 4;
            }

            $update_branch_details_sql = "
                UPDATE Branch_Details 
                    SET Branch_Name = '$branch_name',
                    Manager_Name = '$branch_manager',
                    Customer_Count = $branch_customer_count,
                    Staff_Count = $branch_staff_count,
                    Branch_Rank = $branch_rank,
                    Branch_Address = '$branch_address'
                WHERE IFSC_Code = '$branch_id'
                ";
            if(!mysqli_query($connection, $update_branch_details_sql))
            {
                array_push($error_messages, "ERROR: Could not insert data into table!");
            }
            else
            {
                $updated_branch_details = TRUE;
            }
        }

    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>
            Edit Branch Details | <?php echo $app_name; ?>
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
                <h3>Edit Branch Details</h3>
                <form action="" method="POST">
                    <div>
                        <table>
                            <tr>
                                <th>Branch Name</th>
                                <th>Branch Address</th>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" id = "branch-name" name = "branch_name" value = "<?php echo $old_branch_details["Branch_Name"]; ?>" placeholder = "Enter new branch name" required>
                                </td>
                                <td>
                                    <input type="text" id = "branch-address" name = "branch_address" value = "<?php echo $old_branch_details["Branch_Address"]; ?>" placeholder = "Enter new branch address" required>
                                </td>
                            </tr>
                            <tr>
                                <th>Branch Manager</th>
                                <th>Branch Rank</th>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" id = "branch-manager" name = "branch_manager" value = "<?php echo $old_branch_details["Manager_Name"]; ?>" placeholder = "Enter new branch manager" required>
                                </td>
                                <td>
                                    <input type="number" id = "branch-rank" name = "branch_rank" value = "<?php echo $old_branch_details["Branch_Rank"]; ?>" min = "1" max = "4" placeholder = "Enter new branch rank" readonly required>
                                </td>
                            </tr>
                            <tr>
                                <th>Staff count</th>
                                <th>Customer count</th>
                            </tr>
                            <tr>
                                <td>
                                    <input type="number" id = "branch-staff-count" name = "branch_staff_count" value = "<?php echo $old_branch_details["Staff_Count"]; ?>" min = "1" placeholder = "Enter branch staff count " required>
                                </td>
                                <td>
                                    <input type="number" id = "branch-customer-count" name = "branch_customer_count" value = "<?php echo $old_branch_details["Customer_Count"]; ?>" min = "0" placeholder = "Enter branch customer count" required>
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
                    <?php if(isset($updated_branch_details)): ?>
                        <div>
                            <p>Branch details updated successfully!</p>
                        </div>
                        <?php unset($updated_branch_details); ?>
                    <?php endif; ?>        
                <?php endif; ?>
            </div>
        </div>
    </body>
</html>