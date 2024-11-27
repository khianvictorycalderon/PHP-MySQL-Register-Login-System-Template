<?php
   function getUserInfo($name, $pass, $password_bypass = false) {
    global $fetched_id, $fetched_first_name, $fetched_middle_name, $fetched_last_name,
           $fetched_birth_date, $fetched_username, $fetched_email,
           $fetched_date_created;

    include("config.php");
    include("db_connect.php");

    // Fetch user info and hashed password
    $sql_query = "
    SELECT id, first_name, middle_name, last_name, birth_date, username, email, date_created, password
    FROM " . $initial_table_name . "
    WHERE username = ? OR email = ?
    ";
    $stmt = $conn->prepare($sql_query);
    if ($stmt === false) {
        echo "<script>alert('Finding user preparation statement failed: " . $conn->error . "');</script>";
        return false;
    }

    $stmt->bind_param("ss", $name, $name);
    $stmt->execute();
    $stmt->bind_result(
        $fetched_id,
        $fetched_first_name,
        $fetched_middle_name,
        $fetched_last_name,
        $fetched_birth_date,
        $fetched_username,
        $fetched_email,
        $fetched_date_created,
        $hashed_password
    );

    if ($stmt->fetch()) {
        $stmt->close();
        if (password_verify($pass, $hashed_password) || $password_bypass) {
            $sql_update_query = "
            UPDATE " . $initial_table_name . "
            SET latest_login_session = CURRENT_TIMESTAMP,
                latest_login_ip_address = ?
            WHERE username = ? OR email = ?
            ";
            $stmt_update = $conn->prepare($sql_update_query);
            if ($stmt_update === false) {
                echo "<script>alert('Update preparation statement failed: " . $conn->error . "');</script>";
                $conn->close();
                return false;
            }

            $ip_address = $_SERVER['REMOTE_ADDR'];
            $stmt_update->bind_param("sss", $ip_address, $fetched_username, $fetched_email);
            if ($stmt_update->execute()) {
                $stmt_update->close();
                $conn->close();
                $_SESSION["fetched_id"] = $fetched_id;
                $_SESSION["fetched_first_name"] = $fetched_first_name;
                $_SESSION["fetched_middle_name"] = $fetched_middle_name;
                $_SESSION["fetched_last_name"] = $fetched_last_name;
                $_SESSION["fetched_birth_date"] = $fetched_birth_date;
                $_SESSION["fetched_username"] = $fetched_username;
                $_SESSION["fetched_email"] = $fetched_email;
                $_SESSION["fetched_date_created"] = $fetched_date_created;
                return true;
            } else {
                echo "<script>alert('Update execution failed: " . $stmt_update->error . "');</script>";
                $stmt_update->close();
                $conn->close();
                return false;
            }
        } else {
            echo "<script>alert('Invalid username or password');</script>";
            $conn->close();
            return false;
        }
    } else {
        echo "<script>alert('Invalid username or password');</script>";
        $stmt->close();
        $conn->close();
        return false;
    }
}

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
      if(isset($_POST["log_name"]) && isset($_POST["log_pass"])) {
          $log_name = $_POST["log_name"];
          $log_pass = $_POST["log_pass"];
          if(trim($log_name)=="" || trim($log_pass)=="") {
            echo "<script>alert('PHP: One or more input is invalid!');</script>";
          } else if(getUserInfo($log_name, $log_pass)) {
            $home = 'loggedin';
        }
      }
  }

?>