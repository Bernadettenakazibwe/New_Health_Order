<?php
session_start();

if (isset($_SESSION["user"])) {
    if (($_SESSION["user"]) == "" || $_SESSION['usertype'] != 'p') {
        header("location: ../login.php");
        exit();
    } else {
        $useremail = $_SESSION["user"];
    }
} else {
    header("location: ../login.php");
    exit();
}

include("../connection.php");

// Fetch user details
$sqlmain = "SELECT * FROM patient WHERE pemail=?";
$stmt = $database->prepare($sqlmain);
$stmt->bind_param("s", $useremail);
$stmt->execute();
$result = $stmt->get_result();
$userfetch = $result->fetch_assoc();
$userid = $userfetch["pid"];
$username = $userfetch["pname"];

// Fetch session details based on session ID
if (isset($_GET['id'])) {
    $session_id = $_GET['id'];

    $sqlsession = "SELECT schedule.*, doctor.docname FROM schedule 
                   INNER JOIN doctor ON schedule.docid = doctor.docid 
                   WHERE schedule.scheduleid=?";
    $stmt = $database->prepare($sqlsession);
    $stmt->bind_param("i", $session_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $sessionfetch = $result->fetch_assoc();

    if (!$sessionfetch) {
        echo "Invalid session ID.";
        exit();
    }
} else {
    echo "No session ID provided.";
    exit();
}

date_default_timezone_set('Asia/Kolkata');
$today = date('Y-m-d');

// Handle booking form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $session_id = $_POST['session_id'];
    $patient_id = $userid;
    $booking_date = $today;

    // Insert booking into database
    $sqlbooking = "INSERT INTO appointment (pid, scheduleid, appodate) VALUES (?, ?, ?)";
    $stmt = $database->prepare($sqlbooking);
    $stmt->bind_param("iis", $patient_id, $session_id, $booking_date);
    if ($stmt->execute()) {
        echo "<script>alert('Booking successful!'); window.location.href='appointment.php';</script>";
    } else {
        echo "<script>alert('Booking failed! Please try again later.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/admin.css">
    <title>Book Session</title>
    <style>
        .popup {
            animation: transitionIn-Y-bottom 0.5s;
        }
        .sub-table {
            animation: transitionIn-Y-bottom 0.5s;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="menu">
        <table class="menu-container" border="0">
            <tr>
                <td style="padding:10px" colspan="2">
                    <table border="0" class="profile-container">
                        <tr>
                            <td width="30%" style="padding-left:20px">
                                <img src="../img/user.png" alt="" width="100%" style="border-radius:50%">
                            </td>
                            <td style="padding:0px;margin:0px;">
                                <p class="profile-title"><?php echo substr($username, 0, 13) ?>..</p>
                                <p class="profile-subtitle"><?php echo substr($useremail, 0, 22) ?></p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <a href="../logout.php"><input type="button" value="Log out" class="logout-btn btn-primary-soft btn"></a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="menu-row">
                <td class="menu-btn menu-icon-home">
                    <a href="index.php" class="non-style-link-menu"><div><p class="menu-text">Home</p></div></a>
                </td>
            </tr>
            <tr class="menu-row">
                <td class="menu-btn menu-icon-doctor">
                    <a href="doctors.php" class="non-style-link-menu"><div><p class="menu-text">All Doctors</p></div></a>
                </td>
            </tr>
            <tr class="menu-row">
                <td class="menu-btn menu-icon-session menu-active menu-icon-session-active">
                    <a href="schedule.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Scheduled Sessions</p></div></a>
                </td>
            </tr>
            <tr class="menu-row">
                <td class="menu-btn menu-icon-appoinment">
                    <a href="appointment.php" class="non-style-link-menu"><div><p class="menu-text">My Bookings</p></div></a>
                </td>
            </tr>
            <tr class="menu-row">
                <td class="menu-btn menu-icon-settings">
                    <a href="settings.php" class="non-style-link-menu"><div><p class="menu-text">Settings</p></div></a>
                </td>
            </tr>
        </table>
    </div>
    <div class="dash-body">
        <table border="0" width="100%" style="border-spacing: 0; margin:0; padding:0; margin-top:25px;">
            <tr>
                <td width="13%">
                    <a href="schedule.php"><button class="login-btn btn-primary-soft btn btn-icon-back" style="padding-top:11px; padding-bottom:11px; margin-left:20px; width:125px"><font class="tn-in-text">Back</font></button></a>
                </td>
                <td colspan="2">
                    <p style="font-size: 24px; color: #333;">Book Session</p>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <center>
                        <div class="abc scroll">
                            <table width="60%" class="sub-table scrolldown" border="0" style="padding: 50px; border:none">
                                <tbody>
                                <tr>
                                    <td>
                                        <form action="booking.php?id=<?php echo $session_id; ?>" method="post">
                                            <input type="hidden" name="session_id" value="<?php echo $session_id; ?>">
                                            <p class="heading-main12" style="margin-left: 45px; font-size: 18px; color: rgb(49, 49, 49)">Session Details</p>
                                            <p>Doctor Name: <?php echo $sessionfetch['docname']; ?></p>
                                            <p>Session Title: <?php echo $sessionfetch['title']; ?></p>
                                            <p>Date: <?php echo $sessionfetch['scheduledate']; ?></p>
                                            <p>Time: <?php echo $sessionfetch['scheduletime']; ?></p>
                                            <br>
                                            <input type="submit" value="Confirm Booking" class="login-btn btn-primary btn" style="padding: 10px; width: 100%;">
                                        </form>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </center>
                </td>
            </tr>
        </table>
    </div>
</div>
</body>
</html>
