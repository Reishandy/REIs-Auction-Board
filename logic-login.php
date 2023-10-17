<?php

$hostname = "localhost";
$username = "root";
$password = "";
$database = "board";

try {
    $dbh = new mysqli($hostname, $username, $password, $database);
} catch (mysqli_sql_exception) {
    die("ERROR!!! Database connection failed ＞︿＜");
}

$username = $_POST["username"];
$password = $_POST["password"];

$dbh->begin_transaction();

try {
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $dbh->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $username);

        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                if (password_verify($password, $row["password"])) {
                    echo "Login successfully \(^o^)/";
                    session_start();
                    $_SESSION["logged_in"] = true;
                    $_SESSION["id"] = $row["id"];
                    $_SESSION["username"] = $row["username"];
                    $_SESSION["email"] = $row["email"];
                    $_SESSION["server"] = $row["server"];

                    header("Location: form-board.php");
                    exit();
                } else {
                    header("Location: form-login.php?error='Wrong password'");
                    exit();
                }
            } else {
                header("Location: form-login.php?error='No user found with the username of " . $username . "'");
                exit();
            }
        } else {
            header("Location: form-login.php?error='Login failed'");
            exit();
        }

        $stmt->close();
    } else {
        echo "Error: " . $sql . "<br>" . $dbh->error;
    }
} catch (mysqli_sql_exception $e) {
    $dbh->rollback();
    echo "Error: " . $e->getMessage();
}