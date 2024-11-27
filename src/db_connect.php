<?php


$conn = new mysqli($db_servername, $db_username, $db_password, $db_name);
if ($conn->connect_error) {
    echo "<script>alert('Database connection failed:".$conn->connect_error."');</script>";
    die();
}
// Connected Successfully;

/*

 Usage:
 require("db_connect.php");
 <Query>
 $conn->close();

*/
?>
