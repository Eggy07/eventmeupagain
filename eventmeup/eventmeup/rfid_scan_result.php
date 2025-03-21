<?php
include("config.php");

// Fetch latest RFID scan entry
$stmt = $dbh->query("SELECT * FROM attendance ORDER BY time_in DESC LIMIT 1");
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    echo json_encode([
        "status" => "success",
        "user" => $user['name'],
        "rfid" => $user['rfid_tag'],
        "time_in" => $user['time_in'],
        "time_out" => $user['time_out'] ?? "Not yet"
    ]);
} else {
    echo json_encode(["status" => "error"]);
}
?>
