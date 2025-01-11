<?php

include 'database.php';

$sql="SELECT * FROM users";
$result = $conn->query($sql);  // For MySQLi

if ($result) {
    $dataArray = array();
    $count = 0;

    // Fetch all rows and store them in an array
    while ($row = $result->fetch_assoc()) {  // For MySQLi
        // $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC); // For MSSQL
        $dataArray[] = $row;
        $count++;
    }
    echo 'Rows fetched: ' . $count . '<br>';
    // Display the data in array form
    echo '<pre>';
    print_r($dataArray);
    echo '</pre>';
} else {
    echo 'Error: ' . $mysqli_conn->error;  // For MySQLi
    // echo "Error: " . print_r(sqlsrv_errors(), true); // For MSSQL
}

?>