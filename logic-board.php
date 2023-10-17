<?php
function loadDatabase(): mysqli
{
    // Init the database
    $database = "board";
    $userName = "root";
    $password = "";
    $hostname = "localhost";

    try {
        $dbh = new mysqli($hostname, $userName, $password, $database);
    } catch (mysqli_sql_exception) {
        die("
                <div class='alert alert-danger' role='alert'>
                  ERROR!!! Database connection failed ＞︿＜
                </div>
            ");
    }

    return $dbh;
}

function insertDatabase($dbh): void
{
    // Check all get variable with isset()
    if (isset($_POST["userName"]) && isset($_POST["email"]) && isset($_POST["server"]) && isset($_POST["itemName"]) && isset($_POST["tier"]) && isset($_POST["minimumBid"]) && isset($_POST["end"])) {
        // Get the data from the form
        $userName = $_POST["userName"];
        $email = $_POST["email"];
        $server = $_POST["server"];
        $itemName = $_POST["itemName"];
        $tier = $_POST["tier"];
        $minimumBid = $_POST["minimumBid"];
        $currentBid = 0;
        $end = $_POST["end"];

        // Insert the data into the database
        $insertStatement = $dbh->prepare("INSERT INTO entries (userName, email, server, itemName, tier, minimumBid, currentBid, end, posted) VALUES (?, ?, ?, ?, ?, ?, ?, ?, SYSDATE())");
        $insertStatement->bind_param("sssssiis", $userName, $email, $server, $itemName, $tier, $minimumBid, $currentBid, $end);

        $insertStatement->execute();
        $insertStatement->close();

        echo "
                <div class='alert alert-success alert-dismissible fade show' role='alert'>
                  Item added! ＼(＾▽＾)／
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>
                ";
    }

}

function removeDatabase($dbh): void
{
    // Remove entries
    if (isset($_POST["remove"])) {
        $id = $_POST["remove"];

        $removeStatement = $dbh->prepare("DELETE FROM entries WHERE id = ?");
        $removeStatement->bind_param("i", $id);

        $removeStatement->execute();
        $removeStatement->close();

        echo "
                <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                  Post removed! (´･ω･`)
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>
                ";
    }
}

function bid($dbh): void
{
    // Bid, alert if less than currentBid or minimumBid
    if (isset($_POST["bid"])) {
        $id = $_POST["bid"];
        $currentBid = $_POST["currentBid"];
        $bidAmount = $_POST["bidAmount"];

        if ($bidAmount <= $currentBid) {
            echo "
                <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                  ERROR!!! Bid amount needs to be higher than current bid amount ＞︿＜
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        } else {
            $bidStatement = $dbh->prepare("UPDATE entries SET currentBid = ? WHERE id = ?");
            $bidStatement->bind_param("ii", $bidAmount, $id);

            $bidStatement->execute();
            $bidStatement->close();

            echo "
                <div class='alert alert-success alert-dismissible fade show' role='alert'>
                  Bid successful! ＼(＾▽＾)／
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>
                ";
        }
    }
}

function updateDate($dbh)
{
    if (isset($_POST["dateUpdate"])) {
        $id = $_POST["dateUpdate"];
        $end = $_POST["endUpdate"];

        if ($end < date("Y-m-d")) {
            echo "
                <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                  ERROR!!! End date cannot be in the past ＞︿＜
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
            return;
        }

        $updateStatement = $dbh->prepare("UPDATE entries SET end = ? WHERE id = ?");
        $updateStatement->bind_param("si", $end, $id);

        $updateStatement->execute();
        $updateStatement->close();

        echo "
                <div class='alert alert-success alert-dismissible fade show' role='alert'>
                  Date updated! ＼(＾▽＾)／
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>
                ";
    }
}

function getDatabase($dbh): array
{
    // Get the entries from the database
    $entries = [];

    // Get the data
    $result = $dbh->query("SELECT * FROM entries;");

    // Add the data from database onto an entry list
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Create an entry dictionary to be added into an entry list
            $entry = [
                "id" => $row["id"],
                "userName" => $row["userName"],
                "email" => $row["email"],
                "server" => $row["server"],
                "itemName" => $row["itemName"],
                "tier" => $row["tier"],
                "minimumBid" => $row["minimumBid"],
                "currentBid" => $row["currentBid"],
                "end" => $row["end"],
                "posted" => $row["posted"]
            ];

            $entries[] = $entry;
        }
    }

    // close the database connection
    $dbh->close();

    return $entries;
}

function displayTable($entries): void
{
// Add data into database
    $no = 1;
    foreach ($entries as $entry) {
        echo "
        <tr class='text-center'>
             <th scope='row' style='background-color: #d1e7dd'>" . $no . "</th>
             <th scope='row'>" . $entry["userName"] . "</th>
             <th scope='row'>" . $entry["email"] . "</th>
             <th scope='row'>" . $entry["server"] . "</th>
             <th scope='row' style='background-color: #598598;'>" . $entry["itemName"] . "</th>
             <th scope='row' style='background-color: #7cad91'>" . $entry["tier"] . "</th>
             <th scope='row'> $" . number_format($entry["minimumBid"]) . "</th>
             <th scope='row'> $" . number_format($entry["currentBid"]) . "</th>
             <th scope='row' >" . $entry["posted"] . " </th>
             <th scope='row' style='background-color: #ad4a29;'>" . $entry["end"] . "</th>";

        if ($entry["email"] == $_SESSION["email"]) {
            echo "
            <th>
                  <form method='post'>
                       <input type='hidden' name='remove' id='remove' value='" . $entry['id'] . "'>
                       <button type='submit' class='btn btn-outline-danger' style='margin-bottom: 5px; width: 100%'>Remove</button>
                  </form>  
                  <form method='post' class='needs-validation' novalidate>
                        <div class='has-validation'>
                            <input type='hidden' name='dateUpdate' id='dateUpdate' value='" . $entry['id'] . "'>
                            <input type='date' class='form-control shadow-lg bg-inside' name='endUpdate' id='endUpdate'
                            placeholder='date' style='margin-bottom: 5px; width: 100px;' required>                           
                        <div class='invalid-feedback'>Please enter a date and time.</div>
                        </div>
                        <button type='submit' class='btn btn-outline-primary' style='width: 100%'>Update</button>
                  </form>                                       
             </th>
            ";
        } else {
            echo "
            <th>
                  <form method='post' class='align-items-baseline'>
                        <input type='hidden' name='bid' id='bid' value='" . $entry['id'] . "'>
                        <input type='hidden' name='currentBid' id='currentBid' value='" . $entry['currentBid'] . "'>
                        <input type='number' class='form-control text-center bg-inside' name='bidAmount' id='bidAmount' 
                        placeholder='" . $entry["currentBid"] . "' style='margin-bottom: 5px; width: 100px;' min='" . $entry["minimumBid"] . "' required>
                        <button type='submit' class='btn btn-outline-primary' style='width: 100px'>Bid</button>
                  </form>                                         
             </th>
            ";
        }
        echo "</tr>";
        $no++;
    }
}