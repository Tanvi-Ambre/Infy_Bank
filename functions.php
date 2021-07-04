<?php

$app_name = "InfyBank";

function connect_to_DB()
{
    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "InfyBank";

    $connection = mysqli_connect($host, $user, $password, $database);
    if(mysqli_connect_errno())
    {
        return NULL;
    }
    return $connection;
}

function filter_data($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES);

    return $data;
}

?>