<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection settings
    $host = 'localhost';
    $db = 'edoc';
    $user = '';  // No username
    $pass = '';  // No password
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }

    // Retrieve doctor data from session
    $personalInfo = $_SESSION["personal"];
    $fname = $personalInfo['fname'];
    $lname = $personalInfo['lname'];
    $address = $personalInfo['address'];
    $nic = $personalInfo['nic'];
    $dob = $personalInfo['dob'];
    $email = $personalInfo['email'];
    $password = $personalInfo['password'];
    $tel = $personalInfo['tel'];

    // Additional doctor-specific data
    $specialization = $_POST['specialization'];
    $licenseNumber = $_POST['licenseNumber'];

    // Insert doctor data into database
    $sql = "INSERT INTO doctor (docemail, docname, docpassword, docnic, doctel, specialties) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email, $fname . ' ' . $lname, $password, $nic, $tel, $specialization]);

    // Insert user data into webuser table
    $sqlUser = "INSERT INTO webuser (email, usertype) VALUES (?, 'd')";
    $stmtUser = $pdo->prepare($sqlUser);
    $stmtUser->execute([$email]);

    // Redirect to a success page
    header("location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/animations.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/signup.css">
    <title>Doctor Registration</title>
</head>
<body>
    <center>
        <div class="container">
            <table border="0">
                <tr>
                    <td colspan="2">
                        <p class="header-text">Doctor Registration</p>
                        <p class="sub-text">Provide Additional Details</p>
                    </td>
                </tr>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <tr>
                        <td class="label-td" colspan="2">
                            <label for="specialization" class="form-label">Specialization: </label>
                        </td>
                    </tr>
                    <tr>
                        <td class="label-td" colspan="2">
                            <input type="text" name="specialization" class="input-text" placeholder="Specialization" required>
                        </td>
                    </tr>
                    <tr>
                        <td class="label-td" colspan="2">
                            <label for="licenseNumber" class="form-label">License Number: </label>
                        </td>
                    </tr>
                    <tr>
                        <td class="label-td" colspan="2">
                            <input type="text" name="licenseNumber" class="input-text" placeholder="License Number" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="reset" value="Reset" class="login-btn btn-primary-soft btn">
                        </td>
                        <td>
                            <input type="submit" value="Complete Registration" class="login-btn btn-primary btn">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <br>
                            <label for="" class="sub-text" style="font-weight: 280;">Already have an account&#63; </label>
                            <a href="login.php" class="hover-link1 non-style-link">Login</a>
                            <br><br><br>
                        </td>
                    </tr>
                </form>
            </table>
        </div>
    </center>
</body>
</html>
