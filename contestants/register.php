<?php
include '../includes/db.php';

if (!isset($_GET['token'])) {
    echo "Unauthorized access.";
    exit();
}

$token = $conn->real_escape_string($_GET['token']);
$sql = "SELECT * FROM email_verifications WHERE token='$token' AND is_verified=1";
$result = $conn->query($sql);
if ($result->num_rows != 1) {
    echo "Email not verified.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
    $email = $result->fetch_assoc()['email'];

    $target_dir = "../uploads/";
    $photo = basename($_FILES["photo"]["name"]);
    $target_file = $target_dir . uniqid() . "_" . $photo;
    move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file);

    $sql = "INSERT INTO contestants (email, fullname, age, dob, marital_status, nationality, city, town, religion, tribe, teller, phone, occupation, waist, height, burst, interest, hobby, meal, about, agree, photo) 
            VALUES ('$email', '$fullname', '$age', '$dob', '$marital_status', '$nationality', '$city', '$town', '$religion', '$tribe', '$teller', '$phone', '$occupation', '$waist', '$height', '$burst', '$interest', '$hobby', '$meal', '$about', '$agree', '$target_file')";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<form method="post" enctype="multipart/form-data">
    <h2>Contestant Registration</h2>
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