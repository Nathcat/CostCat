<?php include("../start-session.php");
include("util.php");

header("Access-Control-Allow-Origin: cost.nathcat.net");
header("Access-Control-Allow-Methods: GET");
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
    $is_member = is_member_of_group($conn, $conn, $_SESSION["user"]["id"]);
}
catch (Exception $e) {
    die(json_encode([
        "status" => "fail",
        "message" => "Failed to determine group membership status: "+ $e->getMessage()
    ]));
}

if ($is_member) {
    $debts = [];

    // Determine the user's balance.
    try {
        // Start by totalling their debts
        $stmt = $conn->prepare("SELECT Transactions.* FROM Payees JOIN Transactions ON Payee.transaction = Transactions.id WHERE Payee.user = ? AND Transactions.group = ?");
        $stmt->bind_param("ii", $_SESSION["user"]["id"], $_GET["group"]);
        $stmt->execute();
        $set = $stmt->get_result();

        while ($row = $set->fetch_assoc()) {
            $debts[$row["payer"]] -= $row["amount"] / $row["payeeCount"];
        }

        $stmt->close();

        // ... and then totalling their repayments.
        foreach (array_keys($debts) as $debtor) {
            $stmt = $conn->prepare("SELECT Transactions.* FROM Payees JOIN Transactions ON Payee.transaction = Transaction.id WHERE Payee.user = ? AND Transactions.payer = ?");
            $stmt->bind_param("ii", $debtor, $_SESSION["user"]["id"]);
            $stmt->execute();
            $set = $stmt->get_result();

            while ($row = $set->fetch_assoc()) {
                $debts[$debtor] += $row["amount"] / $row["payeeCount"];
            }
        }
        $stmt->close();
    }
    catch (Exception $e) {
        die(json_encode([
            "status"=> "fail",
            "message" => "Failed to calculate balances: " . $e->getMessage()
        ]));
    }
}
else {
    echo json_encode([
        "status" => "fail",
        "message" => "You are not a member of this group."
    ]);
}


$balance = 0;
foreach ($debts as $debt) {
    $balance += $debt;
}

echo json_encode([
    "status" => "success",
    "overall" => round($balance, 2),
    "individual" => array_map(function ($v) {
        return round($v, 2);
    }, $debts)
]);
?>
