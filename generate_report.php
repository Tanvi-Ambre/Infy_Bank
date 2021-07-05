<?php
require "functions.php";
session_start();

if (!isset($_SESSION['Admin'])) {
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

<body id="edit_branch">
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
                <h1 class="text-center">
                    <?php echo $app_name; ?>
                </h1>
                <br><br><br>

                <h3 class="text-left">Generate Report</h3>

                <article id="branch">

                    <form action="view_report.php" method="POST" id="branch_form">
                        <div>
                            <table class="edit_table">
                                <tr>
                                    <td style="color: black">Branch IFSC Code</td>
                                    <td>
                                        <input type="text" id="branch-code" name="branch_code" placeholder="Enter IFSC Code">
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color: black">Branch Name</td>
                                    <td>
                                        <input type="text" id="branch-name" name="branch_name" placeholder="Enter Branch name">
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color: black">Branch Rank</td>
                                    <td>
                                        <input type="text" id="branch-rank" name="branch_rank" placeholder="Enter rank">
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <br>
                        <div class="footer">
                            <button type="submit" id="btn-search" name="btn_search">Search</button>
                            <button type="reset" id="btn-reset" name="btn_reset">Reset</button>
                        </div>
                    </form>
                </article>
        </section>
</body>

</html>