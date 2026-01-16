<?php include("../start-session.php");
include("util.php");

header("Access-Control-Allow-Origin: cost.nathcat.net");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");
header("Accept: application/json");

if (!array_key_exists("user", $_SESSION)) {
    die(json_encode([
        "status" => "fail",
        "message" => "You are not logged in!"
    ]));
}

if (!array_key_exists("group", $_GET)) {
    die(json_encode([
        "status" => "fail",
        "message" => "You must specify the group."
    ]));
}

$conn = new mysqli("localhost:3306", "costcat", "", "CostCat");

if ($conn->connect_error) {
    die(json_encode([
        "status" => "fail",
        "message" => "Could not connect to the DB: " . $conn->connect_error
    ]));
}

try {
    $is_member = is_member_of_group($conn, $_GET["group"], $_SESSION["user"]["id"]);
}
catch (Exception $e) {
    die(json_encode([
        "status" => "fail",
        "message" => "Failed to determine group membership status: " . $e->getMessage()
    ]));
}

if ($is_member) {
    try {
        $stmt = $conn->prepare("SELECT * FROM Transactions WHERE `group` = ? ORDER BY `timestamp` DESC");
        $stmt->bind_param("i", $_GET["group"]);
        $stmt->execute();
        $set = $stmt->get_result();

        $t = [];
        while ($row = $set->fetch_assoc()) {
            array_push($t, $row);
        }

        $stmt->close();
    }
    catch (Exception $e) {
        die(json_encode([
            "status" => "fail",
            "message" => "Couldn't get transactions: " . $e->getMessage()
        ]));
    }
}
else {
    die(json_encode([
        "status" => "fail",
        "message" => "You are not a member of this group."
    ]));
}


$balance = 0;
foreach ($debts as $debt) {
    $balance += $debt;
}

echo json_encode([
    "status" => "success",
    "transactions" => $t
]);
?>
