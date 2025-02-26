<?php
include("config.php"); // Connect to database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rfid = $_POST['rfid'];

    // Check if RFID exists in the database
    $stmt = $dbh->prepare("SELECT * FROM tbladmin WHERE rfid_tag = :rfid");
    $stmt->bindParam(':rfid', $rfid);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(["status" => "success", "message" => "Access Granted", "user" => $user['name']]);
    } else {
        echo json_encode(["status" => "error", "message" => "Access Denied"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid Request"]);
}
?>
