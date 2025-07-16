<?php
session_start();
include '../includes/db.php';

// 🚨 Check session
if (!isset($_SESSION['verified_email'])) {
    echo "❌ Unauthorized access.";
    exit();
}

$email = $conn->real_escape_string($_SESSION['verified_email']);

// ✅ Check if user already submitted full registration
$stmt = $conn->prepare("SELECT fullname FROM contestants WHERE email = ? AND fullname IS NOT NULL");
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // 🎉 Already registered
    echo "<h2>🎉 You have already completed your registration for Miss South East Nigeria.</h2>";
    echo "<p>If you need to update your details, please contact the admin.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contestant Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f7f7f7;
            padding: 40px;
        }
        form {
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }
        input, textarea, button {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        button {
            background: #4CAF50;
            color: #fff;
            border: none;
        }
        button:hover {
            background: #45a049;
        }
    </style>
</head>
<body>
    <form method="post" action="submit_registration.php" enctype="multipart/form-data">
        <h2>Contestant Registration</h2>
        <p>Complete your details below:</p>

        Full Name: <input type="text" name="fullname" required><br>
        Age: <input type="number" name="age" required><br>
        Date of Birth: <input type="date" name="dob" required><br>
        Marital Status: <input type="text" name="marital_status" required><br>
        Nationality: <input type="text" name="nationality" required><br>
        City: <input type="text" name="city" required><br>
        Town: <input type="text" name="town" required><br>
        Religion: <input type="text" name="religion" required><br>
        Tribe: <input type="text" name="tribe" required><br>
        Teller No: <input type="text" name="teller" required><br>
        Phone No: <input type="text" name="phone" required><br>
        Occupation: <input type="text" name="occupation" required><br>
        Waist: <input type="number" name="waist" required><br>
        Height: <input type="number" name="height" required><br>
        Burst: <input type="number" name="burst" required><br>
        Interest: <input type="text" name="interest" required><br>
        Hobby: <input type="text" name="hobby" required><br>
        Favourite Meal: <input type="text" name="meal" required><br>
        About: <textarea name="about" required></textarea><br>
        Upload Photo: <input type="file" name="photo" accept="image/*" required><br>
        Agree to Terms: <input type="checkbox" name="agree" required> Yes<br>

        <button type="submit">Submit</button>
    </form>
</body>
</html>
