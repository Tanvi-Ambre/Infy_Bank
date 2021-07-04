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
            Generate Report | <?php echo $app_name; ?>
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
                <h3>Generate Report</h3>

                <form action="view_report.php" method="POST">
                    <div>
                        <table>
                            <tr>
                                <td>Branch IFSC Code</td>
                                <td>
                                    <input type="text" id = "branch-code" name = "branch_code" placeholder = "Enter IFSC Code">
                                </td>
                            </tr>
                            <tr>
                                <td>Branch Name</td>
                                <td>
                                    <input type="text" id = "branch-name" name = "branch_name" placeholder = "Enter Branch name">
                                </td>
                            </tr>
                            <tr>
                                <td>Branch Rank</td>
                                <td>
                                    <input type="text" id = "branch-rank" name = "branch_rank" placeholder = "Enter rank">
                                </td>
                            </tr>
                        </table>
                    </div>
                    <br>
                    <div>
                        <button type="submit" id = "btn-search" name = "btn_search">Search</button>
                        <button type="reset" id = "btn-reset" name = "btn_reset">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>