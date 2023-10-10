<?php

session_start();
if (!isset($_SESSION["logged_in"])) {
    header("Location: form-login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>

    <!-- Load element from internet -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container d-flex justify-content-center align-items-center p-5">
    <div class="rounded-5 p-5 shadow-lg ">
        <h1 class="text-center ">Dashboard</h1><br>

        <p class="text-center"><b>Welcome</b>, <?php echo $_SESSION["username"]; ?>!</p>
        <p class="text-center">Your email is '<?php echo $_SESSION["email"]; ?>'</p>
        <p class="text-md-center">
            This is the dashboard that you cannot access without logging in.<br>
            So, if you are seeing this, it means that you have successfully logged in.<br>
            <b>Congratulations! \(^o^)/</b>
        </p>

        <br>

        <form action="logic-logout.php" method="post">
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-outline-danger shadow-lg" style="width: 50vh;">Logout</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>