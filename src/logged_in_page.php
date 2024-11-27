<?php
 function insertToTable($values, $columns, $table_name) {
    if (count($values) !== count($columns)) {
        throw new Exception("The number of values must match the number of columns.");
    }
    $sanitized_values = array_map(function($value) {
        return filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
    }, $values);
    $placeholders = array_fill(0, count($sanitized_values), '?');
    $columns_str = implode(", ", $columns);
    $placeholders_str = implode(", ", $placeholders);
    $sql = "INSERT INTO $table_name ($columns_str) VALUES ($placeholders_str)";
    require("config.php"); // database variables
    require("db_connect.php"); // Connect to the database
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        throw new Exception("Prepare statement failed: " . $conn->error);
    }
    
    $types = str_repeat('s', count($sanitized_values));
    $stmt->bind_param($types, ...$sanitized_values);
    
    if ($stmt->execute() === false) {
        throw new Exception("Execute statement failed: " . $stmt->error);
    }
    $stmt->close();
    $conn->close();
}

function updateRow($value, $column, $id, $table_name) {
    $sanitized_value = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
    $sanitized_column = filter_var($column, FILTER_SANITIZE_SPECIAL_CHARS);
    $sanitized_id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $sanitized_column)) {
        throw new Exception("Invalid column name.");
    }
    $sql = "UPDATE $table_name SET $sanitized_column = ? WHERE id = ?";
    require("config.php");
    require("db_connect.php");

    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        throw new Exception("Prepare statement failed: " . $conn->error);
    }
    $stmt->bind_param('si', $sanitized_value, $sanitized_id);
    if ($stmt->execute() === false) {
        throw new Exception("Execute statement failed: " . $stmt->error);
    }
    $stmt->close();
    $conn->close();
}

function deleteRow($table_name, $id) {
    $sanitized_table_name = filter_var($table_name, FILTER_SANITIZE_SPECIAL_CHARS);
    $sanitized_id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    $sql = "DELETE FROM $sanitized_table_name WHERE id = ?";
    require("config.php");
    require("db_connect.php");
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        throw new Exception("Prepare statement failed: " . $conn->error);
    }
    $stmt->bind_param('i', $sanitized_id);
    if ($stmt->execute() === false) {
        throw new Exception("Execute statement failed: " . $stmt->error);
    }
    $stmt->close();
    $conn->close();
}

function setSessionVariables() {
    global $session_id,
    $session_first_name,
    $session_middle_name,
    $session_last_name,
    $session_birth_date,
    $session_username,
    $session_email;
    $session_id = $_SESSION["fetched_id"];
    $session_first_name = $_SESSION["fetched_first_name"];
    $session_middle_name = $_SESSION["fetched_middle_name"];
    $session_last_name = $_SESSION["fetched_last_name"];
    $session_birth_date = $_SESSION["fetched_birth_date"];
    $session_username = $_SESSION["fetched_username"];
    $session_email = $_SESSION["fetched_email"];
}

 require_once("page.php");
 if($_SERVER["REQUEST_METHOD"]==="POST") {
     if(isset($_SESSION["fetched_username"]) || isset($_SESSION["fetched_email"])) {
       setSessionVariables();
    }
    if(isset($_POST["logout"])) {
        session_destroy();
        $home = 'login';
    }
    if(isset($_POST["deactivate_account"])) {
        updateRow("THIS_ACCOUNT_HAS_BEEN_DEACTIVATED", "password", $session_id, $initial_table_name);
        $home = 'login';
    }
    if(isset($_POST["delete_account"])) {
        deleteRow($initial_table_name, $session_id);
        $home = 'login';
    }
    if(isset($_POST["save_change"])) {
        if(isset($_POST["change_first_name"]) && trim($_POST["change_first_name"]) !== "") {
            try {
                updateRow($_POST["change_first_name"], "first_name", $session_id, $initial_table_name);
                getUserInfo($session_username, "", true);
                setSessionVariables();
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        }
        if(isset($_POST["change_middle_name"]) && trim($_POST["change_middle_name"]) !== "") {
            try {
                updateRow($_POST["change_middle_name"], "middle_name", $session_id, $initial_table_name);
                getUserInfo($session_username, "", true);
                setSessionVariables();
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        }
        if(isset($_POST["change_last_name"]) && trim($_POST["change_last_name"]) !== "") {
            try {
                updateRow($_POST["change_last_name"], "last_name", $session_id, $initial_table_name);
                getUserInfo($session_username, "", true);
                setSessionVariables();
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        }
        if(isset($_POST["change_birth_date"]) && trim($_POST["change_birth_date"]) !== "") {
            try {
                updateRow($_POST["change_birth_date"], "birth_date", $session_id, $initial_table_name);
                getUserInfo($session_username, "", true);
                setSessionVariables();
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        }
        $home = 'loggedin';
    }
 }

 function LoggedIn() {
    global $session_id,
    $session_first_name,
    $session_middle_name,
    $session_last_name,
    $session_birth_date,
    $session_username,
    $session_email;
 $LoggedIn = '
 <div class="logged-in-page fs-4">
    <h1>Hello '.$session_first_name.', Welcome to my demo project!</h1>
    <hr/>
    <h2>Account Info:</h2>
    <form method="POST">
        User ID: '.$session_id.' <br/>
        First Name: '.$session_first_name.' <label class="form-label"><input id="change_first_name" name="change_first_name" type="text" class="form-control" placeholder="Change first name..." maxlength="100"></label><br/>
        Middle Name: '.$session_middle_name.' <label class="form-label"><input id="change_middle_name" name="change_middle_name" type="text" class="form-control" placeholder="Change middle name..." maxlength="100"></label><br/>
        Last Name: '.$session_last_name.' <label class="form-label"><input id="change_last_name" name="change_last_name" type="text" class="form-control" placeholder="Change last name..." maxlength="100"></label><br/>
        Birth Date: '.$session_birth_date.' <label class="form-label"><input id="change_birth_date" name="change_birth_date" type="date" class="form-control" placeholder="Change birth date..." maxlength="100"></label><br/>
        Username: '.$session_username.'<br/>
        Email: '.$session_email.'<br/>
        <input name="save_change" type="submit" class="btn btn-primary" value="Save Changes" />
    </form>
    <hr/>
    <form method="POST">
        <h2>Account Deactivation & Deletion</h2>
        <br/>
        <input name="deactivate_account" type="submit" class="btn btn-primary" value="Deactivate Account" />
        <label for="deactivate_account">Deactivating means you can no longer use this account but your data remains in the database.</label>
        <input name="delete_account" type="submit" class="btn btn-primary" value="Delete Account" />
        <label for="delete_account">Deleting means your data will be removed from the database and this action cannot be undone.</label>
    </form> 
    <hr/>
    <form method="POST">
        <input name="logout" type="submit" class="btn btn-primary" value="Log Out" />
    </form>
 </div>
 ';
 echo $LoggedIn;
 }

?>
