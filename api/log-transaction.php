<?php include("../start-session.php");
include("util.php");

header("Access-Control-Allow-Origin: cost.nathcat.net");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");
header("Accept: application/json");

if (!array_key_exists("user", $_SESSION)) {
    die(json_encode([
        "status" => "fail",
        "message" => "You are not logged in!"
    ]));
}

$request = json_decode(file_get_contents("php://input"), true);

if (!array_key_exists("group", $request) || !array_key_exists("amount", $request) || !array_key_exists("payees", $request)) {
    die(json_encode([
        "status" => "fail",
        "message" => "You must specify the group, amount, and payees."
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
    $is_member = is_member_of_group($conn, $request["group"], $_SESSION["user"]["id"]);
}
catch (Exception $e) {
    die(json_encode([
        "status" => "fail",
        "message" => "Failed to determine group membership status: "+ $e->getMessage()
    ]));
}

if ($is_member) {
    $stmt = $conn->prepare("INSERT INTO Transactions (`group`, payer, amount, payeeCount, `timestamp`) VALUES (?, ?, ?, ?, unix_timestamp())");
    $payeeCount = count($request["payees"]);
    $stmt->bind_param("iiii", $request["group"], $_SESSION["user"]["id"], $request["amount"], $payeeCount);
    
    if (!$stmt->execute()) {
        die(json_encode([
            "status" => "fail",
            "message" => "Failed to create new transaction!"
        ]));
    }

    $stmt->close();
    $transaction_id = $conn->insert_id;

    foreach($request["payees"] as $payee) {
        $stmt = $conn->prepare("INSERT INTO Payees (`transaction`, `user`) VALUES (?, ?)");
        $stmt->bind_param("ii", $transaction_id, $payee);
        
        if (!$stmt->execute()) {
            die(json_encode([
                "status" => "fail",
                "message" => "Failed to register payees!"
            ]));
        }
        
        $stmt->close();
    }
}
else {
    die(json_encode([
        "status" => "fail",
        "message" => "You are not a member of this group."
    ]));
}


echo json_encode([
    "status" => "success"
]);
?>
