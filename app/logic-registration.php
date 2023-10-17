<?php

require_once "../config.php";

try {
    $dbh = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
} catch (mysqli_sql_exception) {
    die("ERROR!!! Database connection failed ＞︿＜");
}

$username = $_POST["username"];
$email = $_POST["email"];
$server = $_POST["server"];
$password = password_hash($_POST["password"], PASSWORD_ARGON2ID);

$dbh->begin_transaction();

try {
    $sql = "INSERT INTO users (username, email, server, password) VALUES (?, ?, ?, ?)";
    $stmt = $dbh->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssss", $username, $email, $server, $password);

        if ($stmt->execute()) {
            $dbh->commit();
            header("Location: form-login.php");
        } else {
            header("Location: form-register.php?error='true'");
        }
        $stmt->close();
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $dbh->error;
    }
} catch (mysqli_sql_exception $e) {
    $dbh->rollback();
    echo "Error: " . $e->getMessage();
}