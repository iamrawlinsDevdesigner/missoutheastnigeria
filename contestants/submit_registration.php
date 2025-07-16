<?php
session_start();
include '../includes/db.php';

// 🚨 Check session
if (!isset($_SESSION['verified_email'])) {
    echo "❌ Unauthorized access.";
    exit();
}

$email = $conn->real_escape_string($_SESSION['verified_email']);

// ✅ Check again if already submitted
$stmt = $conn->prepare("SELECT fullname FROM contestants WHERE email = ? AND fullname IS NOT NULL");
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "🎉 You have already submitted your registration.";
    exit();
}

// 📝 Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $age = $conn->real_escape_string($_POST['age']);
    $dob = $conn->real_escape_string($_POST['dob']);
    $marital_status = $conn->real_escape_string($_POST['marital_status']);
    $nationality = $conn->real_escape_string($_POST['nationality']);
    $city = $conn->real_escape_string($_POST['city']);
    $town = $conn->real_escape_string($_POST['town']);
    $religion = $conn->real_escape_string($_POST['religion']);
    $tribe = $conn->real_escape_string($_POST['tribe']);
    $teller = $conn->real_escape_string($_POST['teller']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $occupation = $conn->real_escape_string($_POST['occupation']);
    $waist = $conn->real_escape_string($_POST['waist']);
    $height = $conn->real_escape_string($_POST['height']);
    $burst = $conn->real_escape_string($_POST['burst']);
    $interest = $conn->real_escape_string($_POST['interest']);
    $hobby = $conn->real_escape_string($_POST['hobby']);
    $meal = $conn->real_escape_string($_POST['meal']);
    $about = $conn->real_escape_string($_POST['about']);
    $agree = isset($_POST['agree']) ? 1 : 0;

    // 📸 Handle file upload
    $target_dir = "../uploads/";
    $photo = basename($_FILES["photo"]["name"]);
    $target_file = $target_dir . uniqid() . "_" . $photo;
    if (!move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
        echo "❌ Failed to upload photo.";
        exit();
    }

    // 💾 Update contestant record
    $sql = "UPDATE contestants SET fullname=?, age=?, dob=?, marital_status=?, nationality=?, city=?, town=?, religion=?, tribe=?, teller=?, phone=?, occupation=?, waist=?, height=?, burst=?, interest=?, hobby=?, meal=?, about=?, agree=?, photo=? WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sissssssssssissssssss', $fullname, $age, $dob, $marital_status, $nationality, $city, $town, $religion, $tribe, $teller, $phone, $occupation, $waist, $height, $burst, $interest, $hobby, $meal, $about, $agree, $target_file, $email);

    if ($stmt->execute()) {
        echo "🎉 Registration successful!";
    } else {
        echo "❌ Error: " . $conn->error;
    }
}
?>
