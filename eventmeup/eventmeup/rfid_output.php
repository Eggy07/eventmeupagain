<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>RFID Attendance Log</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>RFID Attendance Log</h1>
    <div id="access-log">
        <h2>Latest Scan</h2>
        <p id="latest-scan">Waiting for scan...</p>
        <h2>Full Access Log</h2>
        <ul id="log-list"></ul>
    </div>

    <script>
    let lastScannedRFID = ""; // Store last scanned RFID to prevent duplicate logs
    let userStatus = {}; // Track each RFID's last status (Time In or Time Out)

    function fetchLatestScan() {
        $.ajax({
            url: 'rfid_scan_result.php', // Fetch latest scan from DB
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    let currentRFID = response.rfid;
                    let user = response.user;
                    let currentTime = new Date().toLocaleString();

                    // Determine if Time In or Time Out
                    let status = "Time In";
                    if (userStatus[currentRFID] === "Time In") {
                        status = "Time Out";
                    }
                    userStatus[currentRFID] = status; // Update last status

                    // Only update if a new scan is detected
                    if (currentRFID !== lastScannedRFID) {
                        lastScannedRFID = currentRFID;
                        $("#latest-scan").html(`Latest: ${user} (RFID: ${currentRFID}) - <b>${status}</b>`);

                        // Prepend to log list
                        $("#log-list").prepend(
                            `<li>${currentTime} - ${user} (RFID: ${currentRFID}) - <b>${status}</b></li>`
                        );
                    }
                } else {
                    $("#latest-scan").html("No scans yet.");
                }
            },
            error: function() {
                $("#latest-scan").html("Error loading scans.");
            }
        });
    }

    $(document).ready(function() {
        fetchLatestScan(); // Fetch immediately
        setInterval(fetchLatestScan, 2000); // Auto-refresh every 2 seconds
    });
    </script>
</body>
</html>
