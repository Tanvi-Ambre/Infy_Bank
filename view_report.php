<?php
    require "functions.php";
    session_start();

    if(!isset($_SESSION['Admin']))
    {
        header("location:login.php");
    }

    if(isset($_POST["btn_search"]))
    {
        $search_type = NULL;
        $error_messages = array();
        $connection = connect_to_DB();

        if(!$connection)
        {
            array_push($error_messages, "ERROR: Could not connect to the database");
        }

        if(!empty($_POST["branch_code"]) && empty($error_messages))
        {
            $search_type = "branch_code";
            $branch_code = filter_data($_POST["branch_code"]);
            if(!preg_match("/^[A-Za-z0-9]+$/", $branch_code))
            {
                array_push($error_messages, "Please enter a valid branch IFSC code.");
            }
            else
            {
                $get_branch_details_sql = "SELECT Branch_Name, Branch_Address FROM Branch_Details WHERE IFSC_Code = '$branch_code' LIMIT 1";
                $get_branch_details_result = mysqli_query($connection, $get_branch_details_sql);
                if(mysqli_num_rows($get_branch_details_result) == 1)
                {
                    $branch_details = mysqli_fetch_array($get_branch_details_result, MYSQLI_ASSOC);
                }
            }
        }
        elseif(!empty($_POST["branch_name"]) && empty($error_messages))
        {
            $search_type = "branch_name";
            $branch_name = filter_data($_POST["branch_name"]);
            if(!preg_match("/^[A-Za-z,]+$/", $branch_name))
            {
                array_push($error_messages, "Please enter a branch name using alphabets and commas only. Separate multiple names using a comma.");
            }
            else
            {
                $temp_names = explode(',', $branch_name);
                $branch_name = array();
                foreach($temp_names as $name)
                {
                    array_push($branch_name, "'".$name."'");
                }
                $branch_name = implode(',', $branch_name);
                $get_branch_details_sql = "SELECT Branch_Name, Branch_Address FROM Branch_Details WHERE Branch_Name IN ($branch_name)";
                $get_branch_details_result = mysqli_query($connection, $get_branch_details_sql);
                if(mysqli_num_rows($get_branch_details_result) != 0)
                {
                    $branch_details = mysqli_fetch_all($get_branch_details_result, MYSQLI_ASSOC);
                }
            }
        }
        elseif(!empty($_POST["branch_rank"]) && empty($error_messages))
        {
            $search_type = "branch_rank";
            $branch_rank = (int) filter_data($_POST["branch_rank"]);
            if(!preg_match("/^[0-9]+$/", $branch_rank))
            {
                array_push($error_messages, "Please enter a branch rank betweem 0-4.");
            }
            else
            {
                $get_branch_details_sql = "SELECT Branch_Name, Branch_Address FROM Branch_Details WHERE Branch_Rank = '$branch_rank'";
                $get_branch_details_result = mysqli_query($connection, $get_branch_details_sql);
                if(mysqli_num_rows($get_branch_details_result) != 0)
                {
                    $branch_details = mysqli_fetch_all($get_branch_details_result, MYSQLI_ASSOC);
                }
            }
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>
            View Report | <?php echo $app_name; ?>
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
                <?php if(!empty($error_messages)): ?>
                    <?php foreach($error_messages as $message): ?>
                        <div>
                            <?php echo "ERROR: ".$message; ?>
                        </div>
                        <br>
                    <?php endforeach; ?>
                    <?php unset($error_messages); ?>
                <?php else: ?>
                    <h3>Branch Details</h3>
                    <table>
                        <tr>
                            <th>Branch Name</th>
                            <th>Branch Address</th>
                        </tr>
                        <?php if($search_type == "branch_code"): ?>
                            <?php if(empty($branch_details)): ?>
                                <tr>
                                    <td colspan='2'>NO DATA AVAILABLE</td>
                                </tr>
                            <?php else: ?>
                            <tr>
                                <td> <?php echo $branch_details["Branch_Name"]; ?> </td>
                                <td> <?php echo $branch_details["Branch_Address"]; ?> </td>
                            </tr>
                            <?php endif; ?>
                        <?php elseif($search_type == "branch_name" || $search_type == "branch_rank"): ?>
                            <?php if(empty($branch_details)): ?>
                                <tr>
                                    <td colspan='2'>NO DATA AVAILABLE</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($branch_details as $detail): ?>
                                    <tr>
                                        <td> <?php echo $detail["Branch_Name"]; ?> </td>
                                        <td> <?php echo $detail["Branch_Address"]; ?> </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan='2'>NO DATA AVAILABLE</td>
                            </tr>
                        <?php endif; ?>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </body>
</html>