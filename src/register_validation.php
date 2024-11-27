<?php
// Create Initial Table:
function isValidEmail($email) {
    $emailRegex = '/^[^\s@]+@[^\s@]+\.[^\s@]+$/';
    return preg_match($emailRegex, $email) === 1;
}

function isPasswordStrongEnough($password) {
    $passwordRegex = '/[a-zA-Z\d!@#$%^&*(),.?":{}|<>]/';
    return preg_match($passwordRegex, $password) === 1;
}

function insertRegisterToTable($fn, $mn, $ln, $bd, $un, $em, $pass) {
    $filtered_first_name = filter_var($fn, FILTER_SANITIZE_SPECIAL_CHARS);
    $filtered_middle_name = filter_var($mn, FILTER_SANITIZE_SPECIAL_CHARS);
    $filtered_last_name = filter_var($ln, FILTER_SANITIZE_SPECIAL_CHARS);
    $raw_birth_date = $bd;
    $filtered_username = filter_var($un, FILTER_SANITIZE_SPECIAL_CHARS);
    $filtered_email = filter_var($em, FILTER_SANITIZE_EMAIL);
    $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
    $ip = $_SERVER['REMOTE_ADDR'];

    require("config.php");
    require("db_connect.php");
    $sql_insert_query = "
    INSERT INTO " . $initial_table_name . " 
       (first_name, 
        middle_name, 
        last_name, 
        birth_date, 
        username, 
        email, 
        password, 
        latest_login_ip_address
    ) VALUES (?,?,?,?,?,?,?,?)";
    $stmt = $conn->prepare($sql_insert_query);
    if ($stmt === false) {
        echo "<script>alert('Register statement preparation failed: " . $conn->error . "');</script>";
        return;
    }
    $stmt->bind_param("ssssssss",
        $filtered_first_name,
        $filtered_middle_name,
        $filtered_last_name,
        $raw_birth_date,
        $filtered_username,
        $filtered_email,
        $hashed_password,
        $ip
    );
    if ($stmt->execute()) {
        echo "<script>alert('You are now registered, you may login now.');</script>";
    } else {
        echo "<script>alert('Register execution error:".$stmt->error."')</script>";
    }
    $stmt->close();
    $conn->close();
}

function findExistingUsernameOrEmail($username, $email) {
    require("config.php");
    require("db_connect.php");

    $sql_check_query = "
    SELECT COUNT(*) as count FROM " . $initial_table_name . " 
    WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql_check_query);
    if ($stmt === false) {
        echo "<script>alert('Fetching preparation failed:".$conn->error."');</script>";
        return false;
    }
    $stmt->bind_param("ss", $username, $email);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $count = $row['count'];
        return $count > 0;
    } else {
        echo "<script>alert('Fetching execution failed:".$stmt->error."');</script>";
        return false;
    }
    $stmt->close();
    $conn->close();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (
        isset($_POST["reg_firstname"]) &&
        isset($_POST["reg_lastname"]) &&
        isset($_POST["reg_middlename"]) &&
        isset($_POST["reg_birthdate"]) &&
        isset($_POST["reg_username"]) &&
        isset($_POST["reg_email"]) &&
        isset($_POST["reg_password"]) &&
        isset($_POST["reg_confirmpassword"])
    ) {
        // variables
        $reg_firstname = $_POST["reg_firstname"];
        $reg_lastname = $_POST["reg_lastname"];
        $reg_middlename = $_POST["reg_middlename"];
        $reg_birthdate = $_POST["reg_birthdate"];
        $reg_username = $_POST["reg_username"];
        $reg_email = $_POST["reg_email"];
        $reg_password = $_POST["reg_password"];
        $reg_confirmpassword = $_POST["reg_confirmpassword"];

        // conditions
        if (
            trim($reg_firstname) == "" ||
            trim($reg_lastname) == "" ||
            trim($reg_middlename) == "" ||
            empty($reg_birthdate) ||
            !preg_match('/^\d{4}-\d{2}-\d{2}$/', $reg_birthdate) ||
            trim($reg_username) == "" ||
            trim($reg_email) == "" ||
            trim($reg_password) == "" ||
            trim($reg_confirmpassword) == "" ||
            strlen($reg_password) < 8 ||
            strlen($reg_password) > 100 ||
            !isValidEmail($reg_email) ||
            !isPasswordStrongEnough($reg_password) ||
            $reg_password != $reg_confirmpassword
        ) {
            echo "<script>alert('PHP: One or more input is invalid!');</script>";
        } else {
            if (findExistingUsernameOrEmail($reg_username, $reg_email)) {
                echo "<script>alert('Username or Gmail Already taken, try another.');</script>";
                $home = 'signup';
            } else {
                insertRegisterToTable(
                    $reg_firstname,
                    $reg_middlename,
                    $reg_lastname,
                    $reg_birthdate,
                    $reg_username,
                    $reg_email,
                    $reg_password
                );
                $home = 'login';
            }
        }
    }
}

?>
