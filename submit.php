<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "s_d_c";

// Create connection using MySQLi
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Collect form data safely
$name         = $_POST['name']        ?? '';
$age          = $_POST['age']         ?? '';
$gender       = $_POST['gender']      ?? '';
$location     = $_POST['location']    ?? '';
$month       = $_POST['month']       ?? '';
$ex           = $_POST['ex']          ?? '';
$measurments  = $_POST['Measurments'] ?? '';
$braces       = $_POST['Braces']      ?? '';
$amount       = $_POST['amount']      ?? '';
$tmonths      = $_POST['tmonths']     ?? '';

// Debug print (optional for testing)
"Name: $name";
"Age: $age";
"Gender: $gender";
"Location: $location";
"Month: $month";
"EX: $ex";
"Measurments: $measurments";
"Braces: $braces";
"Amount: $amount";
"Total Months: $tmonths";

// File upload folder
$uploadDir = "uploads/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Upload function
function uploadFile($fileInputName)
{
    global $uploadDir;
    if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] === 0) {
        $fileName = basename($_FILES[$fileInputName]['name']);
        $targetPath = $uploadDir . time() . "_" . $fileName;
        if (move_uploaded_file($_FILES[$fileInputName]['tmp_name'], $targetPath)) {
            return $targetPath;
        }
    }
    return null;
}

// Upload photos
$photo1 = uploadFile('photo1');
$photo2 = uploadFile('photo2');
$photo3 = uploadFile('photo3');
$photo4 = uploadFile('photo4');
$photo5 = uploadFile('photo5');
$photo6 = uploadFile('photo6');

// Insert into database using prepared statement
$stmt = $conn->prepare("INSERT INTO patients (name, age, gender, location, duration, extraction, measurements, braces, amount, tmonths, photo1, photo2, photo3, photo4, photo5, photo6)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

if ($stmt) {
    $stmt->bind_param(
        "ssssssssssssssss",
        $name,
        $age,
        $gender,
        $location,
        $month,
        $ex,
        $measurments,
        $braces,
        $amount,
        $tmonths,
        $photo1,
        $photo2,
        $photo3,
        $photo4,
        $photo5,
        $photo6
    );

    if ($stmt->execute()) {
        echo "Patient record saved successfully!";
    } else {
        echo "Execute error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Prepare failed: " . $conn->error;
}

$conn->close();
?>