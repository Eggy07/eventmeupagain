<?php
include("config.php"); // Database connection
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['rfid'])) {
        echo json_encode(["status" => "error", "message" => "No RFID received"]);
        exit();
    }

    $rfid = $_POST['rfid'];
    $currentTime = date("Y-m-d H:i:s");

    // Debugging: Log received RFID data
    file_put_contents("rfid_log.txt", date("Y-m-d H:i:s") . " - Received RFID: $rfid\n", FILE_APPEND);

    // Fetch user details from tbladmin
    $stmt = $dbh->prepare("
    SELECT COALESCE(CONCAT(FirstName, ' ', LastName), 'Unknown User') AS name 
    FROM tbladmin 
    WHERE UPPER(rfid_tag) = UPPER(:rfid)
");
    $stmt->bindParam(':rfid', $rfid);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // If user does not exist, deny access
    if (!$user || empty($user['name'])) {
        file_put_contents("rfid_log.txt", date("Y-m-d H:i:s") . " - Access Denied: RFID not found ($rfid)\n", FILE_APPEND);
        echo json_encode(["status" => "error", "message" => "Access Denied"]);
        exit();
    }

    // Check if user already has a time-in for today
    $checkLog = $dbh->prepare("
        SELECT * FROM attendance 
        WHERE rfid_tag = :rfid AND DATE(time_in) = CURDATE()
    ");
    $checkLog->bindParam(':rfid', $rfid);
    $checkLog->execute();

    if ($checkLog->rowCount() > 0) {
        // Update time-out
        $updateTimeOut = $dbh->prepare("
            UPDATE attendance 
            SET time_out = :time_out 
            WHERE rfid_tag = :rfid AND DATE(time_in) = CURDATE()
        ");
        $updateTimeOut->bindParam(':time_out', $currentTime);
        $updateTimeOut->bindParam(':rfid', $rfid);
        $updateTimeOut->execute();
    
        echo json_encode([
            "status" => "success",
            "message" => "Access Granted",
            "user" => $user['name'],
            "rfid" => $rfid,
            "action" => "Time Out",
            "timestamp" => $currentTime
        ]);
    } else {
        // Insert new time-in record
        $insertLog = $dbh->prepare("
            INSERT INTO attendance (rfid_tag, name, time_in) VALUES (:rfid, :name, :time_in)
        ");
        $insertLog->bindParam(':rfid', $rfid);
        $insertLog->bindParam(':name', $user['name']);
        $insertLog->bindParam(':time_in', $currentTime);
        $insertLog->execute();
    
        echo json_encode([
            "status" => "success",
            "message" => "Access Granted",
            "user" => $user['name'],
            "rfid" => $rfid,
            "action" => "Time In",
            "timestamp" => $currentTime
        ]);
    }}
    
?>