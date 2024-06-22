<?php
// Start the session at the very top before any output
session_start();

// Check user session and type
if (isset($_SESSION["user"])) {
    if ($_SESSION["user"] == "" || $_SESSION['usertype'] != 'a') {
        header("location: ../login.php");
        exit();
    }
} else {
    header("location: ../login.php");
    exit();
}

// Import database connection
include("../connection.php");

$error = '3'; // Default error code

if ($_POST) {
    // Fetch data from POST request
    $name = $_POST['name'];
    $nic = $_POST['nic'];
    $spec = $_POST['spec'];
    $email = $_POST['email'];
    $tele = $_POST['Tele'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    if ($password == $cpassword) {
        $result = $database->query("SELECT * FROM webuser WHERE email='$email';");

        if ($result->num_rows == 1) {
            $error = '1'; // Email already exists
        } else {
            $sql1 = "INSERT INTO doctor (docemail, docname, docpassword, docnic, doctel, specialties) VALUES ('$email', '$name', '$password', '$nic', '$tele', '$spec');";
            $sql2 = "INSERT INTO webuser (email, usertype) VALUES ('$email', 'd')"; // Correct the column name to match your schema
            $database->query($sql1);
            $database->query($sql2);

            $error = '4'; // Successful insertion
        }
    } else {
        $error = '2'; // Passwords do not match
    }
}

header("location: doctors.php?action=add&error=" . $error);
exit();
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
    <title>Doctor</title>
    <style>
        .popup {
            animation: transitionIn-Y-bottom 0.5s;
        }
    </style>
</head>
<body>
</body>
</html>
