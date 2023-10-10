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
    <title>Rei's Auction Board</title>
    <link rel="icon" href="favicon.png" type="image/x-icon">

    <!-- Load element from internet -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Skranji&display=swap" rel="stylesheet">
</head>
<body class="parallax" data-speed="0.5">

<!-- This is the table for displaying auction entries from a database -->
<div class="container d-flex justify-content-center align-items-center p-4">
    <div class="rounded-5 p-5 shadow-lg bg-bg">
        <h1 class="text-center font-title">REI's Auction Board</h1><br>

        <?php
        require_once "logic-board.php";
        $dbh = loadDatabase();
        insertDatabase($dbh);
        removeDatabase($dbh);
        bid($dbh);
        $entries = getDatabase($dbh);
        ?>

        <!-- This is the actual table with the php code to display the data -->
        <div class="table-responsive">
            <table class="table table-hover">
                <caption style="color: white">Table might include duplicates :></caption>
                <thead style="--bs-table-bg: #deb480">
                <tr class="text-center">
                    <th scope="col" style="background-color: #d1e7dd">#</th>
                    <th scope="col">Username</th>
                    <th scope="col">Email</th>
                    <th scope="col">Server</th>
                    <th scope="col" style='background-color: #598598;'>Item name</th>
                    <th scope="col" style='background-color: #7cad91;'>Tier</th>
                    <th scope="col">Minimum bid</th>
                    <th scope="col">Current bid</th>
                    <th scope="col">Posted at</th>
                    <th scope="col" style='background-color: #ad4a29;'>End date</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody style="--bs-table-bg: #edc590">
                <?php
                displayTable($entries);
                ?>
                </tbody>
            </table>
        </div>

        <br>
        <form action="logic-logout.php" method="post">
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-outline-danger shadow-lg" style="width: 50vh;">Logout</button>
            </div>
        </form>
    </div>
</div>

<!-- This is the main form. Yes, I like grayscale :) -->
<div class="container d-flex justify-content-center align-items-center p-4">
    <div class="rounded-5 p-5 shadow-lg bg-bg">
        <h1 class="text-center font-title">Auction your items!</h1><br>

        <div class="text-center font-subtitle">
            <h3>Your details</h3>
            <hr class="bg-danger border-2 border-top"/>

            <h2>Username: <?php echo $_SESSION["username"]?></h2>

            <h2>Email: <?php echo $_SESSION["email"]?></h2>

            <h2>Server: <?php echo $_SESSION["server"]?></h2>

            <br>
        </div>

        <form method="post" class="needs-validation" novalidate>

            <input type="hidden" name="userName" id="userName" value="<?php echo $_SESSION["username"]?>">
            <input type="hidden" name="email" id="email" value="<?php echo $_SESSION["email"]?>">
            <input type="hidden" name="server" id="server" value="<?php echo $_SESSION["server"]?>">

            <h3 class="text-center font-subtitle">Input item details to sell</h3>
            <hr class="bg-danger border-2 border-top"/>

            <div class="form-floating mb-3 rounded-5 has-validation">
                <input type="text" class="form-control shadow-lg bg-inside" name="itemName" id="itemName"
                       placeholder="item" required>
                <label for="itemName">Item</label>
                <div class="invalid-feedback">Please enter an item name</div>
            </div>

            <div class="form-floating mb-3 rounded-5 has-validation">
                <select class="form-select shadow-lg bg-inside" name="tier" id="tier" aria-label="Tier select" required>
                    <option selected disabled value="">Select a Tier</option>
                    <option value="Common">Common</option>
                    <option value="Uncommon">Uncommon</option>
                    <option value="Rare">Rare</option>
                    <option value="Super rare">Super Rare</option>
                    <option value="Ultra rare">Ultra Rare</option>
                    <option value="Epic">Epic</option>
                    <option value="Legendary">Legendary</option>
                    <option value="Mythical">Mythical</option>
                    <option value="God">God</option>
                </select>
                <label for="tier">Tier</label>
                <div class="invalid-feedback">Please select a tier</div>
            </div>

            <div class="input-group mb-3 rounded-5 has-validation">
                <span class="input-group-text shadow-lg bg-inside">$</span>
                <div class="form-floating">
                    <input type="number" class="form-control shadow-lg bg-inside" name="minimumBid" id="minimumBid"
                           placeholder="1" min="1" required>
                    <label for="minimumBid">Minimum Bid</label>
                    <div class="invalid-feedback">Please enter a valid minimum bid amount.</div>
                </div>
            </div>

            <div class="form-floating mb-3 rounded-5 has-validation">
                <input type="date" class="form-control shadow-lg bg-inside" name="end" id="end"
                       placeholder="date" required>
                <label for="end">End date</label>
                <div class="invalid-feedback">Please enter a date and time.</div>
            </div>

            <br>
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-outline-light" style="width: 50vh">Post</button>
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
</script>

</html>