<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registration</title>
    <link rel="icon" href="favicon.png" type="image/x-icon">

    <!-- Load element from internet -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Skranji&display=swap" rel="stylesheet">
</head>
<body class="parallax" data-speed="6">

<?php
    if (isset($_GET["error"])) {
        echo "
                <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                  ERROR!!! Registration failed ＞︿＜
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
    }
?>

<div class="container d-flex justify-content-center align-items-center p-5" style="margin-top: 30px">
    <div class="rounded-5 p-5 shadow-lg ">
        <h1 class="text-center font-title">Registration</h1><br>

        <form action="logic-registration.php" method="post" class="needs-validation" novalidate>
            <div class="form-floating mb-3 rounded-5 has-validation">
                <input type="text" class="form-control shadow-lg bg-inside" name="username" id="username"
                       placeholder="user" required>
                <label for="username">User Name</label>
                <div class="invalid-feedback">Please enter your game username.</div>
            </div>

            <div class="form-floating mb-3 rounded-5 has-validation">
                <input type="text" class="form-control shadow-lg bg-inside" name="email" id="email"
                       placeholder="user" required>
                <label for="email">Email</label>
                <div class="invalid-feedback">Please enter your game email address.</div>
            </div>

            <div class="form-floating mb-3 rounded-5 has-validation">
                <select class="form-select shadow-lg bg-inside" name="server" id="server" aria-label="Server select"
                        required>
                    <option selected disabled value="">Select a server</option>
                    <option value="Aurora">Aurora</option>
                    <option value="Texas">Texas</option>
                    <option value="Nusantara">Nusantara</option>
                </select>
                <label for="server">Server</label>
                <div class="invalid-feedback">Please select a server</div>
            </div>

            <div class="form-floating mb-3 rounded-5 has-validation">
                <input type="password" class="form-control shadow-lg bg-inside" name="password" id="password"
                       placeholder="user" required>
                <label for="password">Password</label>
                <div class="invalid-feedback">Please enter a password.</div>
            </div>

            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-outline-primary shadow-lg" style="width: 50vh; margin-bottom: 10px;">Register</button>
            </div>
        </form>

        <form method="post">
            <div class="d-flex justify-content-center">
                <input type="hidden" name="login" value="true">
                <button type="submit" class="btn btn-outline-light shadow-lg" style="width: 50vh;">Login</button>
                <?php
                    if (isset($_POST["login"])) {
                        header("Location: form-login.php");
                        exit();
                    }
                ?>
            </div>
        </form>
    </div>
</div>

</body>

<script>
    // For validation purposes
    (function () {
        'use strict';
        window.addEventListener('load', function () {
            let forms = document.getElementsByClassName('needs-validation');
            let validation = Array.prototype.filter.call(forms, function (form) {
                form.addEventListener('submit', function (event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();

    // JavaScript for parallax effect
    window.addEventListener("scroll", function () {
        let yOffset = window.pageYOffset;
        let parallaxElements = document.querySelectorAll(".parallax");

        for (let i = 0; i < parallaxElements.length; i++) {
            let speed = parallaxElements[i].getAttribute("data-speed");
            parallaxElements[i].style.backgroundPositionY = -yOffset * speed + "px";
        }
    })

    // Clear post variable
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }

    // Disable scroll
    document.body.style.overflow = "hidden";
</script>

</html>